<?php

namespace App\Http\Controllers\Sector\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Biller\PaymentMethod;
use App\Models\Admin\Product;
use App\Models\Biller\PaymentLog;
use App\Models\Admin\LocalProduct;
use App\Models\Admin\Local;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;
use App\Models\Biller\CreditLog;
use App\Models\Admin\Kardex;
use App\Models\Admin\SuperAdmin\Company;
use App\Models\Admin\Cash;

use App\Traits\Receipts\BillTrait;
use App\Traits\Receipts\TicketTrait;
use DB;
use Session;

use App\Helpers\CompanyHelper;

class ShopController extends Controller
{
    use BillTrait, TicketTrait;
    
    public function index($quote = null){
        $code = date('YmdHis').''.Session::get('user_id');

        $products = Product::select(DB::raw("CONCAT_WS(' ', products.name,' ',products.description, ' ',products.price) AS name"), 'products.id')
                    ->join('local_products as lp', 'products.id', '=', 'lp.product_id')
                    ->where('lp.local_id', Session::get('local_id'))
                    ->pluck('products.name', 'products.id');
        $customers = DB::table('customers')->where('local_id', Session::get('company_id'))->get();            
        //  dd($products);
        $payment_methods = PaymentMethod::where('company_id', Session::get('company_id'))->get();
        $temps = new TempSale(); 
        $local = Local::select('id', 'local_name')->where('id', Session::get('local_id'))->first();
        $cash = Cash::where('seller', Session::get('user_id'))->where('status', 1)->exists();

        // dd($cash);

        return view('sectorr.shop.index', compact('payment_methods', 'products', 'code', 'customers', 'temps', 'local', 'cash'));
        // dd("aqui tipo Tienda");
    }

    public function addOrder(Request $req){

        // return response()->json(['ok' => 1, 'orders' => $req->order['code']]);
        $local = Session::get('local_id');
        $company = Session::get('company_id');
        // $code = date('YmdHis');
        $check = TempSale::where('company_id', $company)->where('local_id', $local)->where('code', $req->order['code'])
        ->where('status', '<', 2)
        ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
        ->value('code');

        $check22 = empty($check) ? $req->order['code'] : $check ;

        $id_order=TempSale::create(['company_id' => $company, 'local_id'=>$local, 'user_id'=>Session::get('user_id'), 'customer_id'=>1, 'code'=>$check22, 'product_id'=>$req->order['id'], 'amount'=>$req->order['amount'], 'price'=> $req->order['price'], 'status' => 1 ]);
        $orders = Product::select("products.name", "products.price", "ts.id", "ts.status", "ts.amount")->join("temp_sales as ts", "ts.product_id", "=", "products.id")->where('ts.code', $check22)->get();
        return response()->json(['ok' => 1, 'orders' => $orders]);

        die();
    }

    public function modifyAmount(Request $req){
        if($req->amount < 1){
            return response()->json(['ok' => 0, 'orders' => []]);
        }
        $orders = TempSale::where('id', $req->id)->update(['amount' => $req->amount]);
        return response()->json(['ok' => 1, 'orders' => $orders]);
    }

    public function deleteOrder(Request $req){
        $order = TempSale::find($req->id);
        $check = $order->code;
        $order->delete();

        $numberOrders = TempSale::where('code', $check)->count();
        $ordersSent = TempSale::where('code', $check)->where('status', 3)->count();
        $sign = $numberOrders == $ordersSent ? 1 : 0;

        $orders = Product::select("products.name", "products.price", "ts.id", "ts.status", "ts.amount")->join("temp_sales as ts", "ts.product_id", "=", "products.id")->where('ts.company_id', Session::get('company_id'))->where('ts.local_id', Session::get('local_id'))->where('ts.code', $check)->get();
        return response()->json(['ok' => 1, 'orders' => $orders, 'sign'=> $sign]);
    }

    public function store(Request $request){

        $local = Session::get('local_id');
        $company = Session::get('company_id');

        $campos = [
            "receipt"=>"required",
            "customer_id"=>"required",
            "code"=>"required",
            "type_payment" => "required",
            "payMethod" => "required_if:type_payment,1",
            
        ];

        // $mensajes =[
        // ]; 

        // $validator = Validator::make($request->all(), $campos);
        Validator::make($request->all(), $campos)->validate(); //DEVUELVE ERROR 403
        
        try{
            // dd($request,$request->input('payMethod'));
            $payMethod = is_array($request->input('payMethod')) ? true : false;

            if (TempSale::where('company_id', $company)->where('code', $request->code)->exists()) {

                $combinado = [];
                $total = TempSale::select(DB::raw('SUM(price * amount) as total'))->where('code', $request->code)->value('total');

                if($request->type_payment == 1){

                    $filteredArray = array_filter($request->input('payMethodVal'), function($value) {
                        return !is_null($value);
                    });

                    if($payMethod){
                        if(count($filteredArray) !== count($request->input('payMethod'))){
                        /**SI ES DIFERENTE MP CON VALUE-MP */
                        
                            // dd($total, $total_provi, $filteredArray, $request->input('payMethod'), array_combine($request->input('payMethod'), $filteredArray), $request, count($filteredArray), count($request->input('payMethod')));
                            // return redirect()->route('pay.show', ['order'=> $request->code])->with('danger', 'Elegio formas de pagos que no coninciden con monto total');
                            dd("joder1");
                        }
                    }
                    
                    $total_provi = (float)array_sum($filteredArray);
                    $total = TempSale::select(DB::raw('SUM(price * amount) as total'))->where('code', $request->code)->value('total');
                    
                    if($total !== $total_provi){
                        /**COMPARA VALORES LE TEMP_SALE CON LOS TRAIDO EL EL ARREGLO "$filteredArray" **/

                        //dd($total, $total_provi, $filteredArray, $request->input('payMethod'), array_combine($request->input('payMethod'), $filteredArray), $request, count($filteredArray), count($request->input('payMethod')));
                        dd("joder2");
                        // return redirect()->route('pay.show', ['order'=> $request->code])->with('danger', 'Elegio formas de pagos que no coninciden con monto total');
                    }
                
                    $combinado = $payMethod == false ? 1 : array_combine($request->input('payMethod'), $filteredArray);
                }
                // dd($request,$request->input('payMethod'),  $filteredArray, count($filteredArray),count($request->input('payMethod')));
                $numeration = $this->setCorrelative($request->receipt); 
                $attention_id = Attention::create([
                    'local_id'=> Session::get('local_id'),
                    'customer_id'=>$request->customer_id,
                    'sunat_code'=>$request->receipt,
                    'document_code'=>$request->code,
                    'reference _document'=>'',
                    'currency'=>1,
                    'type_payment'=>$request->type_payment,
                    'total'=>$total,
                    'seller'=>Session::get('user_id'),
                    'serie'=>1,
                    'numeration'=> $numeration,
                ]);
                /*AQUI DEBERIA VER UN ESTADO PARA VER LA CASH*/
                TempSale::where('code', $request->code)->update(['customer_id'=>$request->customer_id, 'type_payment'=>$request->type_payment]);
                
                if($attention_id->id ){

                    if($request->type_payment == 1){
                        if($payMethod){
                            foreach($combinado as $key => $val){
                                PaymentLog::create([
                                    'company_id' => $company,
                                    'local_id' => Session::get('local_id'),
                                    'attention_id'=>$attention_id->id,
                                    'method_id'=>$key,
                                    'total'=>$val
                                ]);
                            }
                        }else{
                            PaymentLog::create([
                                    'company_id' => $company,
                                    'local_id' => Session::get('local_id'),
                                    'attention_id'=>$attention_id->id,
                                    'method_id'=>$request->payMethod,
                                    'total'=>$total
                            ]);
                        }
                        
                    }else{
                        
                        CreditLog::create([
                            'local_id' => $local,
                            'company_id' =>$company,
                            'attention_id'=> $attention_id->id,
                            'customer_id'=>$request->customer_id,
                            'total'=>$total,
                        ]);
                    }
                    
                    $temps=TempSale::where('code', $request->code);
                    $product_ids = $temps->get();
                    foreach($product_ids as $pid){
                    //RECORDAR QUE EL ID_PRODUXCT ES EL ID DEL LOCAL_PRODUCT
                        LocalProduct::where('local_id', $local)->where('product_id', $pid->product_id)->decrement('stock', $pid->amount);
                        Product::where('id', $pid->product_id)->decrement('stock', $pid->amount);

                        $check = Kardex::where('company_id', $company)->where('local_id', Session::get('local_id'))
                        ->where('product_id', $pid->product_id)
                        ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
                        ->value('id');

                        if($check){
                            Kardex::where('id', $check)->increment('output', $pid->amount);
                        }
                        else{
                            Kardex::create(['company_id' => $company, 'local_id'=>$local, 'product_id'=>$pid->product_id, 'entry'=>0, 'output'=>$pid->amount]);
                        } 
                    }

                    switch($request->receipt){
                        case '03' :
                                $respo = $this->setTicket($request->code);
                                $voucher = 'Boleta';
                                break;
                        case '01' :
                                $respo = $this->setBill($request->code); //TRAIT BILL
                                // dd($respo);
                                $voucher = 'Factura';
                                break; 
                        default :
                                $voucher = 'Ticket';
                                $identifier = 'T001-'.str_pad($attention_id->numeration, 8, "0", STR_PAD_LEFT);
                                TempSale::where('code', $request->code)->update(['status'=> 2]);
                                Attention::where('id', $attention_id->id)->update(['identifier' => $identifier, 'message' => 'Ticket Generado', 'success'=> 1, 'completed' => 1, 'status'=>1]);
                                
                                return CompanyHelper::a_casa('shop.generated', $request->code, 'success', 'Ticket Generado .....Se realizo correctamente la venta');      
                    }

                    return CompanyHelper::a_casa('shop.generated', $request->code, $respo['alert'], $respo['message']);
                    
                    // return redirect()->route('shop.generated', ['order' => $request->code ])->with($respo['alert'], $respo['message']);
                }

            }

            return redirect()->route('shop.index')->with('danger', 'No se pudo realizar la venta');
            
        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        } 
    }

    public function generatedReceipt(Request $request, $order){
        $notify = 0;
        // $uri = $request->path();
        // $previousURL= url()->previous();

        // if(strpos($previousURL, "admin/attentions/") !== false) {
        //     $notify = 1;
        // }
// dd($order);
        // $article=Attention::with("voucher")->first();
        // dd($article);
        $attention = Attention::where('document_code', $order)->first();
        // dd($attention);
        $company = Company::find($request->session()->get('company_id'));
        $temps = TempSale::where('code', $attention->document_code)->get();
        $methods = PaymentMethod::join('payment_logs as pl', 'payment_methods.id', '=', 'pl.method_id')
                                ->join('attentions as at', 'pl.attention_id', '=', 'at.id')
                                ->where('at.document_code', $attention->document_code)
                                ->select('pl.total', 'payment_methods.name')
                                ->get();
                  
        // $table = $temps->value("table_id");
        $payment_methods = PaymentMethod::all();
        // dd($table, $temps, $attention);  
        $total = TempSale::where('code', $attention->document_code)->sum(DB::raw('amount * price'));
        // dd($payment_methods, $attention, $company, $temps, $methods);
        return view('nose.generated_receipt', compact('notify', 'company', 'attention', 'total', 'payment_methods', 'temps', 'methods'));
    }

    public function shopReport(Request $request){

        // DB::raw('SUM(price * amount) as total')
        $search = date("Y-m-d");
        if ($request->isMethod('post')) {
          
            $validated = $request->validate([
                'date' => 'required|date',
            ]);

            $search = $request->date;
        }

        $sales = TempSale::select('pro.name', 'pro.description', DB::raw('SUM(att.total) as total'), DB::raw('SUM(temp_sales.amount) as amount'), 'pro.price', DB::raw('CAST(att.created_at AS DATE) as date'), 'us.name as seller')
            ->join('products as pro', 'temp_sales.product_id', '=', 'pro.id')
            ->join('attentions as att', 'temp_sales.code', '=', 'att.document_code')
            ->join('users as us', 'temp_sales.user_id', '=', 'us.id')
            ->where(DB::raw("CAST(att.created_at AS DATE)"), $search)
            ->groupBy('pro.name')
            ->get();
        // dd($sales);
        return view('admin.sale.report', compact('sales', 'search'));    
    }

    protected function setCorrelative($type){
        $correlative = Attention::where('sunat_code', $type)
                            ->orderBy('numeration', 'desc')
                            ->first();
        if($correlative){
            $number = $correlative->numeration;
            return $number + 1;
        } 
        
        return 1;
    }

}