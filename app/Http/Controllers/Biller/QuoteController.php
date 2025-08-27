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
        $customers = DB::table('customers')->where('local_id', Session::get('company_id'))->get(); 
        $temps = new TempQuote();           
        // $payment_methods = PaymentMethod::where('company_id', Session::get('company_id'))->get();
    
        return view('biller.quote.form', compact('products', 'code', 'old_code', 'customers', 'temps', 'route', 'btn_txt', 'parameter'));
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

        if (TempQuote::where('company_id', $company)->where('code', $request->code)->exists()) {

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
    public function edit($id): View
    {
        $quote = Quote::where('document_code', $id)->first();
        $old_code = $quote->document_code;
        $code = date('YmdHis').''.Session::get('user_id').'_0';
        $route = 'quotes.update';
        $btn_txt = 'Editar Comprobante';
        $parameter = $code;

        $products = Product::select(DB::raw("CONCAT_WS(' ', products.name,' ',products.description, ' ',products.price) AS name"), 'products.id')
                    ->join('local_products as lp', 'products.id', '=', 'lp.product_id')
                    ->where('lp.local_id', Session::get('local_id'))
                    ->pluck('products.name', 'products.id');
        $customers = DB::table('customers')->where('local_id', Session::get('company_id'))->get(); 

        $oldtemps = TempQuote::where('code', $old_code)->get();

        foreach($oldtemps as $oldtemp){
            $new = $oldtemp->replicate();
            $new->code = $code;
            $new->customer_id = 1;
            $new->created_at = now();
            $new->updated_at = now();
            $new->save();
        }

        $temps = TempQuote::select('temp_quotes.id', 'p.name', 'temp_quotes.price', 'temp_quotes.amount')->join('products as p', 'temp_quotes.product_id', '=', 'p.id')->where('temp_quotes.code', $code)->get();

        return view('biller.quote.edit', compact('quote', 'code', 'old_code', 'products', 'customers', 'temps', 'route', 'btn_txt', 'parameter'));
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
            "old_code"=> "required",
        ];
        // $validator = Validator::make($request->all(), $campos);
        Validator::make($request->all(), $campos)->validate(); //DEVUELVE ERROR 403

        if (TempQuote::where('company_id', $company)->where('code', $request->code)->exists()) {

            $total = TempQuote::select(DB::raw('SUM(price * amount) as total'))->where('code', $request->code)->value('total');

            $numeration = $this->setCorrelative($request->receipt); 
            $identifier = 'CTZ1-'.str_pad($numeration, 8, "0", STR_PAD_LEFT);

            $modify = explode('_', $request->code);
            $code_2 = $modify[0];

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

        return Redirect::route('quotes.index')
            ->with('success', 'Quote updated successfully');
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
