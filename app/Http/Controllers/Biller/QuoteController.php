<?php

namespace App\Http\Controllers\Biller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Notification;
use App\Models\Admin\Product;
use App\Models\Biller\Quote;
use App\Models\Biller\TempQuote;
use App\Models\Biller\TempSale;
use App\Models\Biller\EditQuote;
use App\Models\Biller\PaymentMethod;
// use App\Models\Biller\PaymentLog;
use App\Models\Admin\LocalProduct;
use App\Models\Admin\Local;
use App\Models\Admin\Cash;
use App\Models\Admin\SuperAdmin\Company;


use App\Helpers\CompanyHelper;
use App\Helpers\OperationHelper;

use Session;
use DB;

class QuoteController extends Controller
{

    public function index(Request $request): View
    {
        $noty = null;
        if(session()->has('notification')) {
            $noty_id=request()->session()->get('notification');
            $noty = Notification::find($noty_id);
        }
        
        $text = $request->search;
        $select = ['quotes.id', 'quotes.document_code', 'quotes.identifier', 'c.name', DB::raw("format(quotes.total, 2) as total"), DB::raw("SUBSTRING(quotes.message, -17) as message"), 'quotes.created_at', 'quotes.status'];
        $where = ['quotes.local_id'=> ['=', request()->session()->get('local_id')] ];
        $orWhere = ['quotes.identifier'=>['like', '%'.$text.'%'], 'c.name'=>['like', '%'.$text.'%'], 'quotes.created_at'=>['like', '%'.$text.'%'], 'quotes.total'=>['like', '%'.$text.'%'], 'quotes.status'=>['like', '%'.$text.'%']];
        $join = ['customers as c' => ['quotes.customer_id', '=', 'c.id']];

        $query  = Quote::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $quotes = $result->orderBy('quotes.id', 'desc')->paginate();

        return view('biller.quote.index', compact('quotes', 'text', 'noty'))
            ->with('i', ($request->input('page', 1) - 1) * $quotes->perPage());    
    }

    public function create(Request $request): View
    {
        $code = date('YmdHis').''.Session::get('user_id');
        $route = 'quotes.store';
        $parameter = false;
        $btn_txt = 'Generar Nueva Cotizacion';
        $btn_txt_edit = 'Editar esta Cotizacion.';
        $url_add = 'add_order_quote';
        $url_modify = 'modify_amount_2';
        $url_delete = 'delete_order_2';
        // $quote = new Quote();
        $products = Product::select(DB::raw("CONCAT_WS(' ', products.name,' ',products.description, ' ',products.price) AS name"), 'products.id')
                    ->join('local_products as lp', 'products.id', '=', 'lp.product_id')
                    ->where('lp.local_id', Session::get('local_id'))
                    ->pluck('products.name', 'products.id');
        $customers = DB::table('customers')->where('company_id', Session::get('company_id'))->get(); 
        $temps = new TempQuote(); 

        $local = Local::select('id', 'local_name')->where('id', Session::get('local_id'))->first();
        $cash = Cash::where('seller', Session::get('user_id'))->where('status', 1)->exists();         
        // $payment_methods = PaymentMethod::where('company_id', Session::get('company_id'))->get();

        return view('biller.quote.form', compact('url_add', 'url_modify', 'url_delete', 'products', 'code', 'customers', 'temps', 'route', 'btn_txt', 'btn_txt_edit', 'parameter', 'local', 'cash'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    // public function store(QuoteRequest $request): RedirectResponse
    {
        $local = Session::get('local_id');
        $company = Session::get('company_id');

        $campos = [
            "customer_id"=>"required",
            "code"=>"required",
        ];
        // $validator = Validator::make($request->all(), $campos);
        Validator::make($request->all(), $campos)->validate(); //DEVUELVE ERROR 403

        try{
            if(TempQuote::where('company_id', $company)->where('code', $request->code)->exists()) {

                $total = TempQuote::select(DB::raw('SUM(price * amount) as total'))->where('code', $request->code)->value('total');

                $numeration = $this->setCorrelative($request->receipt); 
                $identifier = 'CTZ1-'.str_pad($numeration, 8, "0", STR_PAD_LEFT);

                $quote_id = Quote::create([
                    'company_id' => $company,
                    'local_id'=> $local,
                    'customer_id'=>$request->customer_id,
                    'document_code'=>$request->code,
                    'reference_document'=>'',
                    'currency'=>1,
                    'total'=>$total,
                    'seller'=>Session::get('user_id'),
                    'serie'=>1,
                    'numeration'=> $numeration,
                    'identifier'=> $identifier,
                ]);

                TempQuote::where('code', $request->code)->update(['customer_id'=>$request->customer_id, 'status'=> 2]);
                return redirect()->route('quotes.generated', ['order' => $request->code ])->with('success', 'Su cotizacion se genero con exito');
            }
            else{
                return Redirect::route('quotes.index')
                ->with('danger', 'No se pudo generar la cotizacion ..... Hubo algun error');
            }

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
        $quote = Quote::find($id);

        return view('biller.quote.show', compact('quote'));
    }

    public function edit($id): View
    {
        EditQuote::where('edit_code', $id)->delete();
        ///////////////////////////////////
        // if (EditQuote::where('edit_code', $id)->exists()) {
        //     EditQuote::where('edit_code', $id)->delete();
        // }
        ///////////////////////////////

        $quote = Quote::where('document_code', $id)->first();
        $code =  $quote->document_code; //date('YmdHis').''.Session::get('user_id').'_0';
        $route = 'quotes.update';
        $btn_txt = 'Crear nueva Cotizacion';
        $btn_txt_edit = 'Editar esta Cotizacion.';
        $parameter = $code;
        $url_add = 'add_order_quote_edit';
        $url_modify = 'modify_amount_edit';
        $url_delete = 'delete_order_edit';

        $products = Product::select(DB::raw("CONCAT_WS(' ', products.name,' ',products.description, ' ',products.price) AS name"), 'products.id')
                    ->join('local_products as lp', 'products.id', '=', 'lp.product_id')
                    ->where('lp.local_id', Session::get('local_id'))
                    ->pluck('products.name', 'products.id');

        $customers = DB::table('customers')->where('company_id', Session::get('company_id'))->get(); 

        $oldtemps = TempQuote::where('code', $code)->get();

        foreach($oldtemps as $oldtemp){
                EditQuote::create([
                    'edit_code' => $oldtemp->code,
                    'temp_id' => $oldtemp->id,
                    'edit_prod_id' => $oldtemp->product_id,
                    'edit_price' => $oldtemp->price,
                    'edit_amount' => $oldtemp->amount
                ]);
        }

        // $temps = TempQuote::select('temp_quotes.id', 'p.name', 'temp_quotes.price', 'temp_quotes.amount')->join('products as p', 'temp_quotes.product_id', '=', 'p.id')->where('temp_quotes.code', $code)->get();
        $temps = EditQuote::select('edit_quotes.id', 'p.name', 'temp_id', 'edit_quotes.edit_price as price', 'edit_quotes.edit_amount as amount')->join('products as p', 'edit_quotes.edit_prod_id', '=', 'p.id')->where('edit_quotes.edit_code', $code)->get();

        return view('biller.quote.form', compact('url_add', 'url_modify', 'url_delete', 'quote', 'code', 'products', 'customers', 'temps', 'route', 'btn_txt', 'btn_txt_edit', 'parameter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $local = Session::get('local_id');
        $company = Session::get('company_id');

        $campos = [
            "customer_id"=>"required",
            "code"=>"required",
            "old_code"=> "nullable",//"required",
            "option" => "required"
        ];
        // $validator = Validator::make($request->all(), $campos);
        Validator::make($request->all(), $campos)->validate(); //DEVUELVE ERROR 403
        // dd($request);
        try{
            $op = $request->option;
            $newCode = date('YmdHis').''.Session::get('user_id');
            if (EditQuote::where('edit_code', $request->code)->exists()) {

                $edittemps = EditQuote::where('edit_code', $request->code);
                $total = EditQuote::select(DB::raw('SUM(edit_price * edit_amount) as total'))->where('edit_code', $request->code)->value('total');
                $message = 'Se edito correctamente la cotizacion...';
                // crear una nueva cotixacion o editarla ????
               
                if($op==1){ //creo una nueva y mantengo la antigua igual

                    $edit = $edittemps->get(); //DB::table('oldQuote')->where('old_code', $request->code)->get();
                    $temp = TempQuote::where('code', $request->code)->get();

                    foreach($temp as $t){

                        if($edittemps->where('temp_id', $t->id)->exists()){
                            $et = $edittemps->where('temp_id', $t->id)->first();
                            TempQuote::where('id', $t->id)->update(['product_id'=>$et->edit_prod_id, 'price'=>$et->edit_price, 'amount'=>$et->edit_amount]);
                            $edittemps->where('temp_id', $t->id)->delete();
                        }else{
                            TempQuote::where('id', $t->id)->delete();
                        }
                    }

                    $copyEdit = EditQuote::where('edit_code', $request->code)->get();

                    foreach($copyEdit as $newtemp){
                        // dd($newtemp);
                        TempQuote::create([
                            'company_id' =>Session::get('company_id'),
                            'local_id'=> Session::get('company_id'),
                            'user_id'=> Session::get('company_id'),
                            'code' => $request->code,
                            'customer_id' => $request->customer_id,
                            'product_id' => $newtemp->edit_prod_id,
                            'price' => $newtemp->edit_price,
                            'amount' => $newtemp->edit_amount,
                        ]);
                    }

                        // $copyEdit->delete();
                    $quote_id = Quote::where('document_code', $request->code)->update(['total'=>$total]);

                }else{ 
                    
                    foreach($edittemps->get() as $newtemp){
                        // dd($newtemp);
                        TempQuote::create([
                            'company_id' =>Session::get('company_id'),
                            'local_id'=> Session::get('company_id'),
                            'user_id'=> Session::get('company_id'),
                            'code' => $newCode,
                            'customer_id' => $request->customer_id,
                            'product_id' => $newtemp->edit_prod_id,
                            'price' => $newtemp->edit_price,
                            'amount' => $newtemp->edit_amount,
                        ]);
                    }

                    $edittemps->delete();
 
                    $numeration = $this->setCorrelative($request->receipt); 
                    $identifier = 'CTZ1-'.str_pad($numeration, 8, "0", STR_PAD_LEFT);

                    $quote_id = Quote::create([
                        'company_id' => $company,
                        'local_id'=> $local,
                        'customer_id'=>$request->customer_id,
                        'document_code'=>$newCode,
                        'reference_document'=>'',
                        'currency'=>1,
                        'total'=>$total,
                        'seller'=>Session::get('user_id'),
                        'serie'=>1,
                        'numeration'=> $numeration,
                        'identifier'=> $identifier,
                    ]);

                    $message = 'Se creo una nueva cotizacion ....';
                }
                
                 return Redirect::route('quotes.index')->with('success', $message);
            }
            else{
                return Redirect::route('quotes.index')
                ->with('danger', 'No se pudo generar la cotizacion ..... Hubo algun error');
            }

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".$th->getFile()." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>$th]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        } 
    }

    public function generatedReceipt(Request $request, $code){
        
        $company = Company::find($request->session()->get('company_id'));
        $quote = Quote::where('company_id', $request->session()->get('company_id'))->where('document_code', $code)->first();
        $total =  $quote->total;//TempQuote::where('company_id', $request->session()->get('company_id'))->where('document_code', $code)->sum(DB::raw('price * amount'));
        $temps = TempQuote::select('p.name', 'temp_quotes.price', 'temp_quotes.amount')->join('products as p', 'temp_quotes.product_id', '=', 'p.id')->where('temp_quotes.code', $code)->get();
        $methods = [];
        // dd($quote, $temps, $total);
        return view('biller.quote.generated', compact('company', 'quote', 'total', 'temps', 'methods'));
    }

    public function convert(Request $request, $quote_code){
        
        $code = date('YmdHis').''.Session::get('user_id');

        $products = Product::select(DB::raw("CONCAT_WS(' ', products.name,' ',products.description, ' ',products.price) AS name"), 'products.id')
                    ->join('local_products as lp', 'products.id', '=', 'lp.product_id')
                    ->where('lp.local_id', Session::get('local_id'))
                    ->pluck('products.name', 'products.id');
        $customers = DB::table('customers')->where('local_id', Session::get('company_id'))->get();            

        $payment_methods = PaymentMethod::where('company_id', Session::get('company_id'))->get();

        $temps = Product::select("products.name", "products.price", "ts.id", "ts.status", "ts.amount")->join("temp_quotes as ts", "ts.product_id", "=", "products.id")->where('ts.code', $quote_code)->get();
        
        $alltemps = TempQuote::where('code', $quote_code)->get();
        
        foreach($alltemps as $allt){ //COPY TEMPQUOTE IN TEMPSALE
            TempSale::create([
                'company_id' => Session::get('company_id'),
                'local_id'=> Session::get('local_id'),
                'user_id' => Session::get('user_id'),
                'code' => $code,
                'customer_id' => 1,
                'product_id' => $allt->product_id,
                'price' => $allt->price,
                'amount' => $allt->amount,
                'status' => 1
            ]);
        }
        
        return view('sectorr.shop.index', compact('payment_methods', 'products', 'code', 'customers', 'temps'));
    }

    protected function setCorrelative($type){
        $correlative = Quote::orderBy('numeration', 'desc')
                            ->first();
        if($correlative){
            $number = $correlative->numeration;
            return $number + 1;
        } 
        
        return 1;
    }
}
