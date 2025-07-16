<?php

namespace App\Http\Controllers\Sector\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Biller\PaymentMethod;
use App\Models\Admin\Product;
use App\Models\Biller\PaymentLog;
use App\Models\Admin\LocalProduct;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;
use App\Models\Admin\Kardex;
use App\Models\Admin\SuperAdmin\Company;

use App\Traits\Receipts\BillTrait;
use DB;
use Session;

class ShopController extends Controller
{
    use BillTrait;
    
    public function index(){
        $code = date('YmdHis');
        $products = Product::select(DB::raw("CONCAT(products.name,' ',products.description, ' ',products.price) AS name"), 'products.id')
                    ->join('local_products as lp', 'products.id', '=', 'lp.product_id')
                    ->where('lp.local_id', Session::get('local_id'))
                    ->pluck('products.name', 'products.id');
        //  dd($products);
        $payment_methods = PaymentMethod::where('company_id', Session::get('company_id'))->get();
        return view('sectorr.shop.index', compact('payment_methods', 'products', 'code'));
        // dd("aqui tipo Tienda");
    }

    public function addOrder(Request $req){

        // $code = date('YmdHis');
        $check = TempSale::where('code', $req->order['code'])
        ->where('status', '<', 2)
        ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
        ->value('code');
        
        if($check){
            $id_order=TempSale::create(['local_id'=>Session::get('local_id'), 'user_id'=>Session::get('user_id'), 'customer_id'=>1, 'code'=>$req->order['code'], 'product_id'=>$req->order['id'], 'amount'=>$req->order['amount'], 'price'=> $req->order['price'], 'status' => 1]);
            $orders = Product::select("products.name", "products.price", "ts.id", "ts.status", "ts.amount")->join("temp_sales as ts", "ts.product_id", "=", "products.id")->where('ts.code', $check)->get();
            return response()->json(['ok' => 1, 'orders' => $orders]);
        }else{
            // dd($check, 'hace esto');
            $id_order=TempSale::create(['local_id'=>Session::get('local_id'), 'user_id'=>Session::get('user_id'), 'customer_id'=>1, 'code'=>$req->order['code'], 'product_id'=>$req->order['id'], 'amount'=>$req->order['amount'], 'price'=> $req->order['price'], 'status' => 1 ]);
            $orders = Product::select("products.name", "products.price", "ts.id", "ts.status", "ts.amount")->join("temp_sales as ts", "ts.product_id", "=", "products.id")->where('ts.code', $id_order->code)->get();
            return response()->json(['ok' => 1, 'orders' => $orders]);
        }

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

        $orders = Product::select("products.name", "products.price", "ts.id", "ts.status", "ts.amount")->join("temp_sales as ts", "ts.product_id", "=", "products.id")->where('ts.code', $check)->get();
        return response()->json(['ok' => 1, 'orders' => $orders, 'sign'=> $sign]);
    }

    public function store(Request $request){

        $local = Session::get('local_id');
        // dd($request);receipt
        $campos = [
            "receipt"=>"required",
            "customer_id"=>"required",
            "code"=>"required",
            "payMethod" => "required"
        ];
        // $mensajes =[
        // ]; 

        // $validator = Validator::make($request->all(), $campos);
        Validator::make($request->all(), $campos)->validate(); //DEVUELVE ERROR 403


        if (TempSale::where('code', $request->code)->exists()) {

            $filteredArray = array_filter($request->input('payMethodVal'), function($value) {
                return !is_null($value);
            });

            if(count($filteredArray) !== count($request->input('payMethod'))){
               /**SI ES DIFERENTE MP CON VALUE-MP */
               
                // dd($total, $total_provi, $filteredArray, $request->input('payMethod'), array_combine($request->input('payMethod'), $filteredArray), $request, count($filteredArray), count($request->input('payMethod')));
                // return redirect()->route('pay.show', ['order'=> $request->code])->with('danger', 'Elegio formas de pagos que no coninciden con monto total');
            dd("joder1");
            }
    
            $total_provi = (float)array_sum($filteredArray);
            $total = TempSale::select(DB::raw('SUM(price * amount) as total'))->where('code', $request->code)->value('total');
            
            if($total !== $total_provi){
                /**COMPARA VALORES LE TEMP_SALE CON LOS TRAIDO EL EL ARREGLO "$filteredArray" **/

                //dd($total, $total_provi, $filteredArray, $request->input('payMethod'), array_combine($request->input('payMethod'), $filteredArray), $request, count($filteredArray), count($request->input('payMethod')));
                dd("joder2");
                // return redirect()->route('pay.show', ['order'=> $request->code])->with('danger', 'Elegio formas de pagos que no coninciden con monto total');
            }

            // $attentions = Temp_Order::where('temp_orders.code', $request->code)->first();
            // $numeration = $this->setCorrelative('attentions', 'sunat_code', $request->receipt);
        // dd($total, $total_provi, $filteredArray, $request->input('payMethod'), array_combine($request->input('payMethod'), $filteredArray), $request, count($filteredArray), count($request->input('payMethod')));    
        // dd($request);
            $combinado = array_combine($request->input('payMethod'), $filteredArray);
            
            // dd($total, $total_provi, $filteredArray, $request->input('payMethod'), array_combine($request->input('payMethod'), $filteredArray), $request, count($filteredArray), count($request->input('payMethod')));
            $attention_id = Attention::create([
                'local_id'=> Session::get('local_id'),
                'customer_id'=>$request->customer_id,
                'sunat_code'=>$request->receipt,
                'document_code'=>$request->code,
                'reference _document'=>'',
                'currency'=>1,
                'total'=>$total,
                'seller'=>Session::get('user_id'),
                'serie'=>1,
                'numeration'=> 1,
            ]);
            
            if($attention_id->id){

                foreach($combinado as $key => $val){
                    // echo $key." - ".$val."</br>";
                    PaymentLog::create([
                        'local_id' => Session::get('local_id'),
                        'attention_id'=>$attention_id->id,
                        'method_id'=>$key,
                        'total'=>$val
                    ]);
                }
                // return redirect()->route('attention.index')->with($respo['alert'], $respo['message']); 
                $temps=TempSale::where('code', $request->code);
                $product_ids = $temps->get();
                foreach($product_ids as $pid){
        //RECORDAR QUE EL ID_PRODUXCT ES EL ID DEL LOCAL_PRODUCT
                    LocalProduct::where('local_id', $local)->where('product_id', $pid->product_id)->decrement('stock', $pid->amount);
                    Product::where('id', $pid->product_id)->decrement('stock', $pid->amount);

                    $check = Kardex::where('local_id', Session::get('local_id'))
                    ->where('product_id', $pid->product_id)
                    ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
                    ->value('id');

                    if($check){
                        Kardex::where('id', $check)->increment('output', $pid->amount);
                    }
                    else{
                        Kardex::create(['local_id'=>$local, 'product_id'=>$pid->product_id, 'entry'=>0, 'output'=>$pid->amount]);
                    } 
                }


                switch($request->receipt){
                    case '03' :
                            // $respo = $this->boleta($request->code);
                            $voucher = 'Boleta';
                            // if($respo['success'])
                            //     return redirect()->route('pay.generated', ['order'=>$respo['attentionId']])->with($respo['alert'], 'Boleta '.$respo['nameId'].' '.$respo['message']);  
                            // else
                            //     return redirect()->route('pay.index')->with($respo['alert'], $respo['message']); 
                            break;
                    case '01' :
                            $respo = $this->setBill($request->code); //TRAIT BILL
                            // dd($respo);
                            $voucher = 'Factura';
                            // if($respo['success'])
                            //     return redirect()->route('pay.generated', ['order'=>$respo['attentionId']])->with($respo['alert'], 'Factura '.$respo['nameId'].' '.$respo['message']); 
                            // else
                            //     return redirect()->route('pay.index')->with($respo['alert'], $respo['message']);   
                            break; 
                    default :
                            // $respo = $this->ticket($request->code);
                            $voucher = 'Ticket';
                            // return redirect()->route('pay.index')->with($respo['alert'], $respo['message']);        
                }

                $temps->update(['status'=> 2]);
                // dd($total, $attention_id->id);
                // return redirect()->route('attention.index')->with('success', 'su venta se realizo con exito...'); 
                return redirect()->route('shop.generated', ['order' => $request->code ]); 
                // dd($total, $attention_id->id);
                // $respo = null;
                // $voucher = '';
                // // dd($request->receipt);
                // switch($request->receipt){
                //     case '03' :
                //             $respo = $this->boleta($request->code);
                //             $voucher = 'Boleta';
                //             // if($respo['success'])
                //             //     return redirect()->route('pay.generated', ['order'=>$respo['attentionId']])->with($respo['alert'], 'Boleta '.$respo['nameId'].' '.$respo['message']);  
                //             // else
                //             //     return redirect()->route('pay.index')->with($respo['alert'], $respo['message']); 
                //             break;
                //     case '01' :
                //             $respo = $this->facturacion($request->code);
                //             // dd($respo);
                //             $voucher = 'Factura';
                //             // if($respo['success'])
                //             //     return redirect()->route('pay.generated', ['order'=>$respo['attentionId']])->with($respo['alert'], 'Factura '.$respo['nameId'].' '.$respo['message']); 
                //             // else
                //             //     return redirect()->route('pay.index')->with($respo['alert'], $respo['message']);   
                //             break; 
                //     default :
                //             $respo = $this->ticket($request->code);
                //             $voucher = 'Ticket';
                //             // return redirect()->route('pay.index')->with($respo['alert'], $respo['message']);        
                // }

                // if($respo['success'])
                //     return redirect()->route('pay.generated', ['order'=>$respo['attentionId']])->with($respo['alert'], $voucher.' '.$respo['nameId'].' '.$respo['message']);  
                // else
                //     return redirect()->route('pay.index')->with($respo['alert'], $respo['message']); 
            }

            // dd($request->all());
            // dd($this->config());
        }

        return redirect()->route('Tienda')->with('danger', 'No se pudo realizar la venta');
    }

    public function generatedReceipt(Request $request, $order){
        $notify = 0;
        // $uri = $request->path();
        // $previousURL= url()->previous();

        // if(strpos($previousURL, "admin/attentions/") !== false) {
        //     $notify = 1;
        // }
// dd($order);
        $attention = Attention::where('document_code', $order)->first();
        // dd($attention);
        $company = Company::find(1);
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

}