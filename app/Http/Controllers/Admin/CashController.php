<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin\Cash;
use App\Models\Admin\IoCash;
use App\Models\User;
use App\Models\Admin\Local;
use App\Models\Admin\BelongLocal;
use App\Models\Biller\Attention;



use App\Models\Biller\TempSale; //<----- borrar

use App\Http\Requests\CashRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Helpers\CompanyHelper;
use DB;
use App\Models\Biller\PaymentLog;
use App\Models\Biller\PaymentMethod;

class CashController extends Controller
{
    protected $total = 0;
    public function index(Request $request): View
    {
        $text = $request->search;
        $select = ['cashes.id', 'cashes.amount', 'cashes.created_at', 'cashes.close_amount', 'close_cash', 'u.name', 'l.local_name', 'cashes.status'];
        $where = ['cashes.company_id'=> ['=', $request->session()->get('company_id')]];
        $orWhere = ['u.name'=>['like', '%'.$text.'%'], 'l.local_name' => ['like', '%'.$text.'%'], 'cashes.amount' => ['like', '%'.$text.'%'], 'cashes.created_at'=> ['like', '%'.$text.'%'], 'cashes.status'=>['like', '%'.$text.'%'], 'cashes.close_cash'=>['like', '%'.$text.'%']];
        $join = ['users as u' => ['cashes.seller', '=', 'u.id'], 'locals as l' => ['cashes.local_cash', '=', 'l.id']];

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

    public function store(CashRequest $request): RedirectResponse
    {
        // dd('llego', $request);
        try{
            Cash::create($request->validated() + ['company_id' => $request->session()->get('company_id'), 'local_id'=>$request->session()->get('local_id'), 'user_id'=>$request->session()->get('user_id'), 'type'=>1]);

            // return Redirect::route('cashes.index')->with('success', 'Cash created successfully.');
            return back()->with('success', 'Cash created successfully.');

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
        $payAll = $this->paymentMethods($cash);
        $payCredit = $this->creditSale($cash);
        $total = (float)$this->total;
        $input = (float)$this->io(1, $cash->id)->sum('io_amount');
        $output = (float)$this->io(2, $cash->id)->sum('io_amount');
        $indetails = $this->io(1, $cash->id)->get();
        $outdetails = $this->io(2, $cash->id)->get();
// dd($payCredit, $payAll, $total, $input, $output, $indetails, $outdetails);
        return view('admin.cash.show', compact('cash', 'payAll', 'payCredit', 'total', 'input', 'output', 'indetails', 'outdetails'));
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

    public function ioCash(Request $request): RedirectResponse
    {
        // dd($request);
        $campos = [
            "type" => "required",
            "io_amount" => "required",
            "cash_id" => "required",
            "observation" => "string|nullable"
        ];
        
        $validator = Validator::make($request->all(), $campos)->validated(); //DEVUELVE ERROR 403

        IoCash::create($validator + ['local_id'=>$request->session()->get('local_id'), 'user_id'=>$request->session()->get('user_id') ]);

        return Redirect::route('cashes.index')
            ->with('success', 'Cash updated successfully');
    }

    public function close(Request $request, $id): View
    {
        $cash = Cash::find($id);

        if(isset($cash->status)){

            if($cash->status == 1) //open
            {
                dd('llego');
                $cash->status = 0;
                $cash->save();
                // $timestamp = strtotime($cash->created_at); // Convertir a marca de tiempo Unix
                // $create = date("Y-m-d", $timestamp);
                // $now = date("Y-m-d");

                // $paymelogs = Attention::select(DB::raw('SUM(pl.total) as total'))
                // ->join('payment_logs as pl', 'attentions.id', '=', 'pl.attention_id')
                // ->where('attentions.local_id', request()->session()->get('local_id'))
                // ->where('attentions.seller', $cash->seller)
                // ->where('attentions.created_at', '>', $cash->created_at)
                // ->whereBetween(DB::raw('CAST(attentions.created_at as DATE)'), [$create, $now])
                // ->groupBy('pl.method_id')
                // ->get();
                // // $paymelogs = Attention::select(DB::raw('SUM(pl.total) as total'))->join('payment_logs as pl', 'attentions.id', '=', 'pl.attention_id')->whereBetween(DB::raw('CAST(pl.created_at as DATE)'), [$create, $now])->groupBy('pl.method_id')->get();

                // $yape = isset($paymelogs[1]->total) ? $paymelogs[1]->total : 0;
                // $contado = isset($paymelogs[0]->total) ? $paymelogs[0]->total : 0;

                // return view('admin.cash.show', compact('cash', 'yape', 'contado'));
            // dd($now, $create, $paymelogs);
            return Redirect::route('cashes.index')->with('danger', 'Esta Caja ya esta cerrada');
            }else{
                return Redirect::route('cashes.index')->with('danger', 'Esta Caja ya esta cerrada');
            }
        }

        return Redirect::route('cashes.index')->with('danger', 'No se encontro este elemento');
    }

    public function endCash(Request $request, $id): RedirectResponse
    {

        try{
            $cash = Cash::find($id);
            $cash->status = 0;
            $cash->close_cash = now();
            $cash->close_amount = $request->amount;
            $cash->observation = $request->observation;
            $cash->save();

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

    public function getCash(Request $request, $cash){

        $cashes = Cash::select('id', 'amount')->where('id', $cash)->first();
        
        return response()->json($cashes);
    }

    public function paymentMethods($cash){

        $dateEnd = $this->validateDate($cash->close_cash);

        $this->total = Attention::select(DB::raw('SUM(total) as total'))
                    ->whereBetween('created_at', [$cash->created_at, $dateEnd])
                    ->where('type_payment', 1)
                    ->where('seller', $cash->seller)
                    ->value('total');

// dd($this->total);
        $methods = PaymentMethod::select(DB::raw('SUM(pl.total) as total'), 'payment_methods.name', 'payment_methods.image')
                    ->leftJoin('payment_logs as pl', 'payment_methods.id', '=', 'pl.method_id')
                    ->join('attentions as at', 'pl.attention_id', '=', 'at.id')
                    ->join('cashes as ca', 'at.seller', '=', 'ca.seller')
                    ->where('payment_methods.company_id', request()->session()->get('company_id'))
                    // ->where(DB::raw('MONTH(at.created_at)'), $month)
                    ->whereBetween('at.created_at', [$cash->created_at, $dateEnd])
                    ->where('ca.id', $cash->id)
                    ->where('ca.status', 1)
                    ->groupBy('payment_methods.name')
                    ->get();
        // dd($methods, $cash, empty($cash->close_cash), $dateEnd);
        //             dd($methods);
        return $methods;         
    }

    public function creditSale($cash){

        $dateEnd = $this->validateDate($cash->close_cash);
        $credits = Attention::select(DB::raw('SUM(attentions.total) as total'),)
                    ->join('cashes as ca', 'attentions.seller', '=', 'ca.seller')
                    ->whereBetween('attentions.created_at', [$cash->created_at, $dateEnd])
                    ->where('attentions.type_payment', 2)
                    ->where('ca.id', $cash->id)
                    ->where('ca.status', 1)
                    ->first();

        return $credits;            
    }

    protected function validateDate($date){

        $dateEnd = empty($cash->close_cash) ? now() : $cash->close_cash;

        return $dateEnd;
    }

    protected function io($type, $cash){
        $total = IoCash::where('cash_id', $cash)->where('type', $type);
        return $total;
    }

    protected function details($type, $cash){
        IoCash::where('cash_id', $cash)->where('type', $type)->get();
    }
}
