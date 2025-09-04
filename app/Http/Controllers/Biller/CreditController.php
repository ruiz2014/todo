<?php

namespace App\Http\Controllers\Biller;

use App\Models\Biller\Attention;
use App\Models\Biller\PaymentMethod;
use App\Models\Biller\PaymentLog;
use App\Models\Biller\TempSale;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AttentionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Helpers\CompanyHelper;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $text = $request->search;
        $select = ['attentions.id', 'attentions.document_code', 'c.name', 'attentions.total', 'attentions.identifier', 'attentions.status', 'u.name as seller'];
        $where = ['attentions.local_id'=> ['=', request()->session()->get('local_id')], 'attentions.type_payment'=> ['=', 2]];
        $orWhere = ['c.name'=>['like', '%'.$text.'%'], 'attentions.total'=>['like', '%'.$text.'%'], 'attentions.identifier'=>['like', '%'.$text.'%'], 'attentions.status'=>['like', '%'.$text.'%'], 'u.name'=>['like', '%'.$text.'%'] ];
        $join = ['customers as c' => ['attentions.customer_id', '=', 'c.id'], 'users as u' => ['attentions.seller', '=', 'u.id']];

        $query  = Attention::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $attentions = $result->paginate();
    
        $methods = PaymentMethod::where('company_id', request()->session()->get('company_id'))->pluck('name', 'id');
        // $attentions = Attention::where('type_payment', 2)->paginate();

        return view('credit.index', compact('attentions', 'text', 'methods'))
            ->with('i', ($request->input('page', 1) - 1) * $attentions->perPage());
        
        // $attentions = Attention::paginate();

        // return view('attention.index', compact('attentions'))
        //     ->with('i', ($request->input('page', 1) - 1) * $attentions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create(): View
    // {
    //     $attention = new Attention();

    //     return view('attention.create', compact('attention'));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttentionRequest $request): RedirectResponse
    {
        try{
            $update = Attention::find($request->sale_id);
            $update->type_payment = $request->type_payment;
            $update->save();

            TempSale::where('code', $request->document_code)->update(['status'=>2]);
            PaymentLog::create([
                                'company_id' => request()->session()->get('company_id'),
                                'local_id' => request()->session()->get('local_id'),
                                'attention_id'=>$update->id,
                                'method_id'=>$request->methods,
                                'total'=>$update->total
                        ]);
                    // Attention::create($request->validated());
            return Redirect::route('credits.index')->with('success', 'La Venta de credito fue Actualizada a pagada.');
        
        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }     
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show($id): View
    // {
    //     $attention = Attention::find($id);

    //     return view('attention.show', compact('attention'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit($id): View
    // {
    //     $attention = Attention::find($id);

    //     return view('attention.edit', compact('attention'));
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(AttentionRequest $request, Attention $attention): RedirectResponse
    // {
    //     $attention->update($request->validated());

    //     return Redirect::route('attentions.index')
    //         ->with('success', 'Attention updated successfully');
    // }

    // public function destroy($id): RedirectResponse
    // {
    //     Attention::find($id)->delete();

    //     return Redirect::route('attentions.index')
    //         ->with('success', 'Attention deleted successfully');
    // }
}
