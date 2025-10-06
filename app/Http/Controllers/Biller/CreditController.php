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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use DB;

use App\Helpers\CompanyHelper;

class CreditController extends Controller
{

    public function index(Request $request): View
    {
        $text = $request->search;
        $select = ['attentions.id', 'attentions.document_code', 'attentions.customer_id', 'c.name', DB::raw('SUM(attentions.total) as total') ];
        $where = ['attentions.local_id'=> ['=', request()->session()->get('local_id')], 'attentions.type_payment'=> ['=', 2]];
        $orWhere = ['c.name'=>['like', '%'.$text.'%'], 'attentions.total'=>['like', '%'.$text.'%'] ];
        $join = ['customers as c' => ['attentions.customer_id', '=', 'c.id']];

        $query  = Attention::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $attentions = $result->groupBy('attentions.customer_id')->paginate();
    
        $methods = PaymentMethod::where('company_id', request()->session()->get('company_id'))->pluck('name', 'id');
        // $attentions = Attention::where('type_payment', 2)->paginate();

        return view('credit.index', compact('attentions', 'text', 'methods'))
            ->with('i', ($request->input('page', 1) - 1) * $attentions->perPage());
    }

    public function show(Request $request, $id){

        $text = $request->search;
        $select = ['attentions.id', 'attentions.document_code', 'c.name', 'attentions.total', 'attentions.identifier', 'attentions.status', 'attentions.created_at', 'u.name as seller'];
        $where = ['attentions.customer_id'=> ['=', $id ], 'attentions.type_payment'=> ['=', 2]];
        $orWhere = ['c.name'=>['like', '%'.$text.'%'], 'attentions.total'=>['like', '%'.$text.'%'], 'attentions.identifier'=>['like', '%'.$text.'%'], 'attentions.status'=>['like', '%'.$text.'%'], 'attentions.created_at' =>['like', '%'.$text.'%'], 'u.name'=>['like', '%'.$text.'%'] ];
        $join = ['customers as c' => ['attentions.customer_id', '=', 'c.id'], 'users as u' => ['attentions.seller', '=', 'u.id']];

        $query  = Attention::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $attentions = $result->paginate();
    
        $methods = PaymentMethod::where('company_id', request()->session()->get('company_id'))->pluck('name', 'id');

        return view('credit.show', compact('attentions', 'text', 'methods'))
            ->with('i', ($request->input('page', 1) - 1) * $attentions->perPage());
    }

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

    public function storeTotal(Request $request): RedirectResponse
    {
        $campos = [
            "customer_id"=>"required",
            "type_payment" => "required",
            "methods" => "required" 
        ];

        // $mensajes =[
        // ]; 

        Validator::make($request->all(), $campos)->validate(); //DEVUELVE ERROR 403
    
        try{
            $attentions = Attention::select('id', 'document_code', 'total' )->where('customer_id', $request->customer_id)->where('type_payment', 2)->get();

            foreach($attentions as $att){

                $update = Attention::find($att->id);
                $update->type_payment = $request->type_payment;
                $update->save();

                TempSale::where('code', $att->document_code)->update(['status'=>2]);
                PaymentLog::create([
                    'company_id' => request()->session()->get('company_id'),
                    'local_id' => request()->session()->get('local_id'),
                    'attention_id'=>$att->id,
                    'method_id'=>$request->methods,
                    'total'=>$att->total
                ]);
            }

            return Redirect::route('credits.index')->with('success', 'La Venta de credito fue Actualizada a pagada.');
        
        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        } 
    }
}
