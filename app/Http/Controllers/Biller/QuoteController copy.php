<?php

namespace App\Http\Controllers\Biller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Admin\Notification;
use App\Models\Admin\Product;
use App\Models\Biller\Quote;
use App\Models\Biller\TempQuote;
// use App\Models\Biller\PaymentMethod;
// use App\Models\Biller\PaymentLog;
use App\Models\Admin\LocalProduct;
use App\Models\Admin\SuperAdmin\Company;

use App\Helpers\CompanyHelper;
use App\Helpers\OperationHelper;

use Session;
use DB;

class QuoteController extends Controller
{
    protected $products;
    protected $customers; 
    protected $temps;
    protected $quote;

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

    /**
     * Show the form for creating a new resource.
     */

    public function create(Request $request): View
    {
        $code = date('YmdHis').''.Session::get('user_id');
        $old_code = false;
        $route = 'quotes.store';
        $parameter = false;
        $btn_txt = 'Generar Comprobante';
        // $quote = new Quote();
        $products = Product::select(DB::raw("CONCAT_WS(' ', products.name,' ',products.description, ' ',products.price) AS name"), 'products.id')
                    ->join('local_products as lp', 'products.id', '=', 'lp.product_id')
                    ->where('lp.local_id', Session::get('local_id'))
                    ->pluck('products.name', 'products.id');
        $customers = DB::table('customers')->where('company_id', Session::get('company_id'))->get(); 
        $temps = new TempQuote();           
        // $payment_methods = PaymentMethod::where('company_id', Session::get('company_id'))->get();

        $edit = 0;
    
        return view('biller.quote.form', compact('edit', 'products', 'code', 'old_code', 'customers', 'temps', 'route', 'btn_txt', 'parameter'));
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

    /**
     * Show the form for editing the specified resource.
     */
    public function keep($id){

        if (DB::table('oldQuote')->where('old_code', $id)->exists()) {
          dd("si hay");
        } else {
            $oldtemps = TempQuote::where('code', $id)->get();

            foreach($oldtemps as $oldtemp){
                DB::table('oldQuote')->insert([
                    'old_code' => $oldtemp->code,
                    'old_prod_id' => $oldtemp->product_id,
                    'old_price' => $oldtemp->price,
                    'old_amount' => $oldtemp->amount
                ]);
            }

        }
        dd("copio");
        /** como si editaramos cotizacion  */
        $code = $this->data($id);
        $route = 'quotes.update';
        $btn_txt = 'Editar Cotizacion';
        $old_code = false;
        $parameter = true; //false;

        $products = $this->products;
        $customers = $this->customers; 
        $temps = $this->temps;
        $quote = $this->quote;
        $edit = 1;

        // dd($code, $this->products, $this->customers, $this->temps);

        return view('biller.quote.edit', compact('edit', 'quote', 'code', 'old_code', 'products', 'customers', 'temps', 'route', 'btn_txt', 'parameter'));

    }
    public function edit($id): View
    {
        // $code = $this->data($id);
        // dd($code, $this->products, $this->customers, $this->temps);

        $quote = Quote::where('document_code', $id)->first();
        $old_code = $quote->document_code;
        $code = date('YmdHis').''.Session::get('user_id').'_0';
        $route = 'quotes.update';
        $btn_txt = 'Crear nueva Cotizacion';
        $parameter = $code;

        $products = Product::select(DB::raw("CONCAT_WS(' ', products.name,' ',products.description, ' ',products.price) AS name"), 'products.id')
                    ->join('local_products as lp', 'products.id', '=', 'lp.product_id')
                    ->where('lp.local_id', Session::get('local_id'))
                    ->pluck('products.name', 'products.id');

        $customers = DB::table('customers')->where('company_id', Session::get('company_id'))->get(); 

        $oldtemps = TempQuote::where('code', $old_code)->get();

        foreach($oldtemps as $oldtemp){
            $new = $oldtemp->replicate();
            $new->code = $code;
            $new->customer_id = 1;
            $new->created_at = now();
            $new->updated_at = now();
            $new->save();
        }
// $this->mensaje = 'azul el mar azul';
// $joder = $this->mensaje;
        $edit = 1;

        $temps = TempQuote::select('temp_quotes.id', 'p.name', 'temp_quotes.price', 'temp_quotes.amount')->join('products as p', 'temp_quotes.product_id', '=', 'p.id')->where('temp_quotes.code', $code)->get();

        return view('biller.quote.form', compact('edit', 'quote', 'code', 'old_code', 'products', 'customers', 'temps', 'route', 'btn_txt', 'parameter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    // public function update(QuoteRequest $request, Quote $quote): RedirectResponse
    {
        // dd($request);
        
        $local = Session::get('local_id');
        $company = Session::get('company_id');

        $campos = [
            "customer_id"=>"required",
            "code"=>"required",
            "old_code"=> "nullable",//"required",
        ];
        // $validator = Validator::make($request->all(), $campos);
        Validator::make($request->all(), $campos)->validate(); //DEVUELVE ERROR 403
        try{
            if (TempQuote::where('company_id', $company)->where('code', $request->code)->exists()) {

                $total = TempQuote::select(DB::raw('SUM(price * amount) as total'))->where('code', $request->code)->value('total');

                // $quote = Quote::where('document_code', $request->code)->first();
                // $quote->total = $total;
                // $quote->customer_id = $request->customer_id;
                // $quote->save();
                $numeration = $this->setCorrelative($request->receipt); 
                $identifier = 'CTZ1-'.str_pad($numeration, 8, "0", STR_PAD_LEFT);

                $modify = explode('_', $request->code);
                $code_2 = $modify[0];
// dd($total, $request->code, $request->old_code, $quote);
                $quote_id = Quote::create([
                    'company_id' => $company,
                    'local_id'=> $local,
                    'customer_id'=>$request->customer_id,
                    'document_code'=>$code_2,
                    'reference_document'=>'',
                    'currency'=>1,
                    'total'=>$total,
                    'seller'=>Session::get('user_id'),
                    'serie'=>1,
                    'numeration'=> $numeration,
                    'identifier'=> $identifier,
                ]);

                
                TempQuote::where('code', $request->code)->update(['code'=>$code_2, 'customer_id'=>$request->customer_id, 'status'=> 2]);
                return redirect()->route('quotes.generated', ['order' => $code_2 ])->with('success', 'Su cotizacion se genero con exito');
            }
            else{
                return Redirect::route('quotes.index')
                ->with('danger', 'No se pudo generar la cotizacion ..... Hubo algun error');
            }


            $quote->update($request->validated());

            return Redirect::route('quotes.index')->with('success', 'Quote updated successfully');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        } 
    }



/***************ORIGIN******************/
    // public function update(Request $request, $id)
    // // public function update(QuoteRequest $request, Quote $quote): RedirectResponse
    // {
    //     // dd($request);
        
    //     $local = Session::get('local_id');
    //     $company = Session::get('company_id');

    //     $campos = [
    //         "customer_id"=>"required",
    //         "code"=>"required",
    //         "old_code"=> "required",
    //     ];
    //     // $validator = Validator::make($request->all(), $campos);
    //     Validator::make($request->all(), $campos)->validate(); //DEVUELVE ERROR 403
    //     try{
    //         if (TempQuote::where('company_id', $company)->where('code', $request->code)->exists()) {

    //             $total = TempQuote::select(DB::raw('SUM(price * amount) as total'))->where('code', $request->code)->value('total');

    //             $numeration = $this->setCorrelative($request->receipt); 
    //             $identifier = 'CTZ1-'.str_pad($numeration, 8, "0", STR_PAD_LEFT);

    //             $modify = explode('_', $request->code);
    //             $code_2 = $modify[0];

    //             $quote_id = Quote::create([
    //                 'company_id' => $company,
    //                 'local_id'=> $local,
    //                 'customer_id'=>$request->customer_id,
    //                 'document_code'=>$code_2,
    //                 'reference_document'=>'',
    //                 'currency'=>1,
    //                 'total'=>$total,
    //                 'seller'=>Session::get('user_id'),
    //                 'serie'=>1,
    //                 'numeration'=> $numeration,
    //                 'identifier'=> $identifier,
    //             ]);

                
    //             TempQuote::where('code', $request->code)->update(['code'=>$code_2, 'customer_id'=>$request->customer_id, 'status'=> 2]);
    //             return redirect()->route('quotes.generated', ['order' => $code_2 ])->with('success', 'Su cotizacion se genero con exito');
    //         }
    //         else{
    //             return Redirect::route('quotes.index')
    //             ->with('danger', 'No se pudo generar la cotizacion ..... Hubo algun error');
    //         }


    //         $quote->update($request->validated());

    //         return Redirect::route('quotes.index')->with('success', 'Quote updated successfully');

    //     }catch (\Throwable $th) {

    //         Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
    //         Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
    //         return back()->with('danger', 'Hubo error al generar este procedimiento');
    //     } 
    // }


    public function generatedReceipt(Request $request, $code){
        
        $company = Company::find($request->session()->get('company_id'));
        $quote = Quote::where('company_id', $request->session()->get('company_id'))->where('document_code', $code)->first();
        $total =  $quote->total;//TempQuote::where('company_id', $request->session()->get('company_id'))->where('document_code', $code)->sum(DB::raw('price * amount'));
        $temps = TempQuote::select('p.name', 'temp_quotes.price', 'temp_quotes.amount')->join('products as p', 'temp_quotes.product_id', '=', 'p.id')->where('temp_quotes.code', $code)->get();
        $methods = [];
        // dd($quote, $temps, $total);
        return view('biller.quote.generated', compact('company', 'quote', 'total', 'temps', 'methods'));
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

    protected function data($id){

        $this->quote = Quote::where('document_code', $id)->first(); 
        $code = $this->quote->document_code;

        $this->products = Product::select(DB::raw("CONCAT_WS(' ', products.name,' ',products.description, ' ',products.price) AS name"), 'products.id')
                    ->join('local_products as lp', 'products.id', '=', 'lp.product_id')
                    ->where('lp.local_id', Session::get('local_id'))
                    ->pluck('products.name', 'products.id');

        $this->customers = DB::table('customers')->where('company_id', Session::get('company_id'))->get(); 
        $this->temps = TempQuote::select('temp_quotes.id', 'p.name', 'temp_quotes.price', 'temp_quotes.amount')->join('products as p', 'temp_quotes.product_id', '=', 'p.id')->where('temp_quotes.code', $code)->get();
        
        return $code;

    }
}
