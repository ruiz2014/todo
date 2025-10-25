<?php

namespace App\Http\Controllers\Sector\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Biller\TempRestaurant;
// use App\Models\Admin\Company;
use App\Models\Biller\PaymentMethod;
use App\Models\Biller\PaymentLog;
use App\Models\Biller\Attention;
use App\Models\Admin\SuperAdmin\Company;
// use App\Models\Biller\Log_Receipt;
use DB;

use App\Traits\Receipts\BillTrait;
use App\Traits\Receipts\TicketTrait;

use App\Helpers\CompanyHelper;

// use Greenter\Model\Response\BillResult;
// use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
// use Greenter\Model\Sale\Invoice;
// use Greenter\Model\Sale\SaleDetail;
// use Greenter\Model\Sale\Legend;

// use Greenter\Model\Summary\Summary;
// use Greenter\Model\Summary\SummaryDetail;


// use Greenter\Ws\Services\SunatEndpoints;
// use App\Http\Controllers\NumeroALetras;

use DateTime;
use DOMDocument;
// use \PDF;
// use Exception;

class CheckOutController extends Controller
{
    use BillTrait, TicketTrait;

    public function index(Request $request){
        $attentions = TempRestaurant::where('status', 4)
                    ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
                    ->orderBy('id', 'desc')
                    ->groupBy('code')
                    ->get();
                    
        return view('sectorr.restaurant.cash_register', compact('attentions'));  
    }

    public function show(Request $request, $order){
        // dd($order);
/*+++++ERROR DE RUTAS SE PUEDE IR A TEMP ATTEMCION SE DEBE CORREGIR PARA NO MOSTRAR NADA ++++++*/
        $attentions = TempRestaurant::join('products as p', 'p.id', '=', 'temp_restaurants.product_id')
                    ->where('temp_restaurants.status', 4)
                    ->where('temp_restaurants.code', $order)
                    // ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
                    // ->orderBy('id', 'desc')
                    ->get();

        $payment_methods = PaymentMethod::where('company_id', request()->session()->get('company_id'))->get();

        $total = TempRestaurant::where('code', $order)->sum(DB::raw('amount * price'));

        return view('sectorr.restaurant.cash_bill', compact('attentions', 'total', 'order', 'payment_methods'));
    }

    public function store(Request $request){
        // dd($request);
        $local = request()->session()->get('local_id');
        $company = request()->session()->get('company_id');

        $campos = [
            "receipt"=>"required",
            "customer_id"=>"required",
            "code"=>"required",
            "payMethod" => "required",
        ];

        Validator::make($request->all(), $campos)->validate(); //DEVUELVE ERROR 403

        try{
            // dd($request,$request->input('payMethod'));
            $payMethod = is_array($request->input('payMethod')) ? true : false;

            if (TempRestaurant::where('company_id', $company)->where('code', $request->code)->exists()) {
                $combinado = [];
                $total = TempRestaurant::select(DB::raw('SUM(price * amount) as total'))->where('code', $request->code)->value('total');

                $filteredArray = array_filter($request->input('payMethodVal'), function($value) {
                    return !is_null($value);
                });

                if($payMethod){
                    if(count($filteredArray) !== count($request->input('payMethod'))){
                        /**SI ES DIFERENTE MP CON VALUE-MP */  
                        return back()->with('danger', 'Los metodos de pago y las cantidades no concuerda....');                     
                            // dd($total, $total_provi, $filteredArray, $request->input('payMethod'), array_combine($request->input('payMethod'), $filteredArray), $request, count($filteredArray), count($request->input('payMethod')));
                    }
                }
                   
                $total_provi = (float)array_sum($filteredArray);
                     
                if($total !== $total_provi){
                        /**COMPARA VALORES LE TEMP_SALE CON LOS TRAIDO EL EL ARREGLO "$filteredArray" **/
                    return back()->with('danger', 'El total pago y las cantidades brindadas no concuerdan....'); 
                        // return redirect()->route('pay.show', ['order'=> $request->code])->with('danger', 'Elegio formas de pagos que no coninciden con monto total');
                }
                 
                $combinado = $payMethod == false ? 1 : array_combine($request->input('payMethod'), $filteredArray);

                $numeration = $this->setCorrelative($request->receipt); 
                $attention_id = Attention::create([
                    'local_id'=> $local,
                    'customer_id'=>$request->customer_id,
                    'sunat_code'=>$request->receipt,
                    'document_code'=>$request->code,
                    'reference _document'=>'',
                    'currency'=>1,
                    'type_payment'=>1, //contado
                    'total'=>$total,
                    'seller'=>request()->session()->get('user_id'),
                    'serie'=>1,
                    'numeration'=> $numeration,
                    'belong' => 3
                ]);
                /*AQUI DEBERIA VER UN ESTADO PARA VER LA CASH*/
                // TempRestaurant::where('code', $request->code)->update(['customer_id'=>$request->customer_id, 'type_payment'=>$request->type_payment]);
                TempRestaurant::where('code', $request->code)->update(['customer_id'=>$request->customer_id]);

                $algo = 1; //contado
                if($attention_id->id){
                    if($algo){ //$request->type_payment == 1
                        if($payMethod){
                            foreach($combinado as $key => $val){
                                PaymentLog::create([
                                    'company_id' => $company,
                                    'local_id' => $local,
                                    'attention_id'=>$attention_id->id,
                                    'method_id'=>$key,
                                    'total'=>$val
                                ]);
                            }
                        }else{
                            PaymentLog::create([
                                'company_id' => $company,
                                'local_id' => $local,
                                'attention_id'=>$attention_id->id,
                                'method_id'=>$request->payMethod,
                                'total'=>$total
                            ]);
                        }
                            
                    }else{
                            
                        CreditLog::create([
                            'local_id' => $local,
                            'company_id' =>$company,
                            'attention_id'=>$attention_id->id,
                            'customer_id'=>$request->customer_id,
                            'total'=>$total,
                        ]);
                    }

// dd($numeration, $combinado, $request->input('payMethod'), $filteredArray, $payMethod, $total_provi, $total, $request);
                    $this->model = TempRestaurant::class; //TRAIT BILL Y TICKET
                    $this->table = 'temp_restaurants'; //TRAIT BILL Y TICKET
                    $this->statusEnd = 5; //TRAIT BILL Y TICKET

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
                                TempRestaurant::where('code', $request->code)->update(['status'=> 5]);
                                Attention::where('id', $attention_id->id)->update(['identifier' => $identifier, 'message' => 'Ticket Generado', 'success'=> 1, 'completed' => 1, 'status'=>1]);
                                
                                return CompanyHelper::a_casa('shop.generated', $request->code, 'success', 'Ticket Generado .....Se realizo correctamente la venta');      
                    }

                     return CompanyHelper::a_casa('shop.generated', $request->code, $respo['alert'], $respo['message']);
                
                }
            
            }

            return back()->with('danger', 'No se pudo realizar la venta');

        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }    
    }

    public function generatedReceipt(Request $request, $order){
        $notify = 0;;
        $attention = Attention::where('document_code', $order)->first();

        $company = Company::find($request->session()->get('company_id'));
        $temps = TempRestaurant::where('code', $attention->document_code)->get();
        $methods = PaymentMethod::join('payment_logs as pl', 'payment_methods.id', '=', 'pl.method_id')
                                ->join('attentions as at', 'pl.attention_id', '=', 'at.id')
                                ->where('at.document_code', $attention->document_code)
                                ->select('pl.total', 'payment_methods.name')
                                ->get();

        $payment_methods = PaymentMethod::all(); 
        $total = $attention->total;
        // dd($payment_methods, $attention, $company, $temps, $methods);
        return view('nose.generated_receipt', compact('notify', 'company', 'attention', 'total', 'payment_methods', 'temps', 'methods'));
    }

    protected function setCorrelative($type){
        $correlative = Attention::where('sunat_code', $type)
                            ->where('local_id', request()->session()->get('local_id'))
                            ->orderBy('numeration', 'desc')
                            ->first();
        if($correlative){
            $number = $correlative->numeration;
            return $number + 1;
        } 
        
        return 1;
    }
}