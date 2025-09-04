<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Admin\Cash;
use App\Models\User;
use App\Models\Admin\Local;
use App\Models\Admin\BelongLocal;
use App\Models\Biller\Attention;

use App\Http\Requests\CashRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Helpers\CompanyHelper;
use DB;
use App\Models\Biller\PaymentLog;

class CashController extends Controller
{
     public function index(Request $request): View
    {
        $text = $request->search;
        $select = ['cashes.id', 'cashes.amount', 'cashes.created_at', 'cashes.close_amount', 'close_cash', 'u.name', 'l.local_name', 'cashes.status'];
        $where = ['cashes.company_id'=> ['=', $request->session()->get('company_id')]];
        $orWhere = ['u.name'=>['like', '%'.$text.'%'], 'l.local_name' => ['like', '%'.$text.'%'], 'cashes.amount' => ['like', '%'.$text.'%'], 'cashes.created_at'=> ['like', '%'.$text.'%'], 'cashes.status'=>['like', '%'.$text.'%'], 'cashes.close_cash'=>['like', '%'.$text.'%']];
        $join = ['users as u' => ['cashes.user_id', '=', 'u.id'], 'locals as l' => ['cashes.local_cash', '=', 'l.id']];

        $query  = Cash::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $cashes = $result->orderBy('cashes.id', 'desc')->paginate();
        
        // $cashes = Cash::paginate();
        $seller = new User();

        $local_cash = Local::where('company_id', request()->session()->get('company_id'))->pluck('local_name', 'id');
// dd($local_cash);
        return view('admin.cash.index', compact('cashes', 'local_cash', 'text'))
            ->with('i', ($request->input('page', 1) - 1) * $cashes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(): View
    // {
    //     $cash = new Cash();

    //     return view('admin.cash.create', compact('cash'));
    // }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CashRequest $request): RedirectResponse
    {
        // dd('llego', $request);
        try{
            Cash::create($request->validated() + ['company_id' => $request->session()->get('company_id'), 'local_id'=>$request->session()->get('local_id'), 'user_id'=>$request->session()->get('user_id'), 'type'=>1]);

            return Redirect::route('cashes.index')->with('success', 'Cash created successfully.');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cash = Cash::find($id);

        return view('admin.cash.show', compact('cash'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cash = Cash::find($id);

        return view('admin.cash.edit', compact('cash'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CashRequest $request, Cash $cash): RedirectResponse
    {
        $cash->update($request->validated());

        return Redirect::route('cashes.index')
            ->with('success', 'Cash updated successfully');
    }

    public function close(Request $request, $id): View
    {
        $cash = Cash::find($id);

        if(isset($cash->status)){
            if($cash->status == 'Abierto') //open
            {
                $timestamp = strtotime($cash->created_at); // Convertir a marca de tiempo Unix
                $create = date("Y-m-d", $timestamp);
                $now = date("Y-m-d");

                $paymelogs = Attention::select(DB::raw('SUM(pl.total) as total'))->join('payment_logs as pl', 'attentions.id', '=', 'pl.attention_id')->where('attentions.local_id', request()->session()->get('local_id'))->where('attentions.seller', $cash->seller)->where('attentions.created_at', '>', $cash->created_at)->whereBetween(DB::raw('CAST(attentions.created_at as DATE)'), [$create, $now])->groupBy('pl.method_id')->get();
                // $paymelogs = Attention::select(DB::raw('SUM(pl.total) as total'))->join('payment_logs as pl', 'attentions.id', '=', 'pl.attention_id')->whereBetween(DB::raw('CAST(pl.created_at as DATE)'), [$create, $now])->groupBy('pl.method_id')->get();

                $yape = isset($paymelogs[1]->total) ? $paymelogs[1]->total : 0;
                $contado = isset($paymelogs[0]->total) ? $paymelogs[0]->total : 0;

                return view('admin.cash.show', compact('cash', 'yape', 'contado'));
            // dd($now, $create, $paymelogs);
            }else{
                return Redirect::route('cashes.index')->with('danger', 'Esta Caja ya esta cerrada');
            }
        }

        return Redirect::route('cashes.index')->with('danger', 'No se encontro este elemento');
    }

    public function endCash(Request $request, $id): RedirectResponse
    {
         $validated = $request->validate([
            'amount' => 'required|numeric', 
            'observation' => 'string|nullable'
        ]);

        try{
            $cash = Cash::find($id);

            $cash->update([
                'close_cash' =>date('Y-m-d :H:i:s'),
                'close_amount'=>$request->amount,
                'observation'=>$request->observation,
                'status' => 'Cerrado',
            ]);

            return Redirect::route('cashes.index')->with('success', 'Cash deleted successfully');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }
        
    }

    public function getSeller(Request $request){
        $id = $request->id;

        $users = BelongLocal::select('u.id', 'u.name')->join('users as u', 'belong_locals.user_id', '=', 'u.id')->where('establishment_id', '<', 2)->get();
        //  = Local::User('id', 'name')->where('company_id', request()->session()->get('company_id'))->where('rol', '>', 2)->get();
        return response()->json($users);
    }

    // public function checkAmount(Request $request){

    // }
}
