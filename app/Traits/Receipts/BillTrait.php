<?php
namespace App\Traits\Receipts;

use App\Models\Usuario;
use App\Models\Admin\Product;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;
// use App\Traits\Sunat\SunatTrait;
use App\Traits\BillingConfigurationTrait;
use App\Traits\BillingToolsTrait;
use DateTime;

use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Charge;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

use Greenter\Ws\Services\SunatEndpoints;
use App\Http\Controllers\NumeroALetras;

use Illuminate\Support\Facades\Log;

trait BillTrait {
    use BillingConfigurationTrait, BillingToolsTrait;
    // use SunatTrait; 
    // public $see = null;
    // public $cdr = '';
    // public $code = '';
    // public $message = '';

    // protected $serie = '';
    // protected $number = '';
    // protected $type = '';
    // protected $total = '';
    // protected $subTotal = '';
    // protected $igv = '';
    protected $model = null;
    protected $table = null;
    protected $statusEnd = null;

    public function setBill($code){

        $response = [
            'success' => false,
            'alert' => 'danger',
            'message' => 'No se encontro ninguna orden ',
            'cdr' => null,
            'nameId' => '',
            'attentionId' => null,
            'update' => false,
            'error' => false
        ];

        if (!Attention::where('document_code', $code)->exists()) {
            dd('if');
            return  $response;
        }
                
        try {  
            
            $items = [];
            
            $sale_data = $this->getSale($code);  //TRAIT BillingToolsTrait
            $sale_items = $this->getDetails($this->table, $code);

            if(!$sale_items->isEmpty()){

                $serie = $this->formatSerie($sale_data->serie, $sale_data->sunat_code);
                $number = str_pad($sale_data->numeration, 8, "0", STR_PAD_LEFT);

                $invoice = $this->setInvoice($sale_data, $serie, $number);
                $items = $this->setItems($sale_items);

                $convertNumberToLetters = new NumeroALetras();
                $numberToLetters = $convertNumberToLetters->convertir($sale_data->total, 'soles');
                $invoice->setDetails($items)
                ->setLegends([
                    (new Legend())
                        ->setCode('1000')
                        ->setValue($numberToLetters)
                ]);     

                $see = $this->config(); //AQUI EMPIEZA .................................

                $xmlSigned = $this->xmlSigned($see->getXmlSigned($invoice));//TRAIT BILLTOOL
                $hash=$this->getHashXml($xmlSigned); //TRAIT BILLTOOL
                $xml_id=$this->getIdXml($xmlSigned); //TRAIT BILLTOOL
                $date = new DateTime();
                $_fecha = $date->format('Y-m-d');
                $resumen = '20000000001|01|'.$serie.'|'.$number.'|'.round(45, 2).'|'.round(45, 2).'|'.$_fecha.'| 01 | 29781231232';
                $rucCustomer = '20608894447';

                $this->writeXml($invoice, $see->getFactory()->getLastXml(), $rucCustomer, 1); 
                
                Attention::where('id', $sale_data->id)->update(['hash'=>$hash, 'identifier'=>$xml_id, 'resume' => $resumen, 'dispatched'=>1]);
                
                $result = $see->send($invoice);

                $validated = $this->validateResult($result, $response);

                if($validated['error']){ //SI HUBO PROBLEMA EN EL ENVIO "HTTP" SE ANULA 

                    Attention::where('id', $sale_data->id)->update(['hash'=>$hash, 'identifier'=>$xml_id, 'resume' => $resumen, 'cdr'=>$validated['cdr'], 'message'=>$validated['message'], 'dispatched'=>1]);
                    return $validated;
                }
                
                $cdr = $result->getCdrResponse();
                $code_cdr = (int)$cdr->getCode();

                list($message, $alert, $update_ts) = $this->validateCrd($code_cdr);
// dd($cdr, $result, $validated);
                $this->writeCdr($invoice, $result->getCdrZip(), $rucCustomer, 1);
                Attention::where('id', $sale_data->id)->update(['cdr'=>$code_cdr, 'received'=>1]);
                // Log_Receipt::create([ 'user_id'=>1, 'customer_id'=>$attentionData->customer_id, 'document_code'=>$order, 'identifier'=>$xml, 'total'=>$total, 'hash'=>$hash, 'resume'=>$resumen, 'cdr'=>$code]);
                $message .=''.$cdr->getDescription().PHP_EOL;

                if($update_ts){ $this->model::where('code', $code)->update(['status'=> $this->statusEnd ]); }
                
                Attention::where('id', $sale_data->id)->update(['message'=>$message, 'completed'=>1, 'status' => 1]);

                $response = [
                    'success' => true,
                    'alert' => $alert,
                    'message' => $message,
                    'cdr' => $code_cdr,
                    'nameId' => $xml_id,
                    'attentionId' => $sale_data->id,
                    'update' => $update_ts
                ];
                // dd($hash, $xml_id, $see, $see->getFactory(), $result, $validated, $response, $message, $alert, $response);
                return $response;
            }
            else{
                dd('no hay');
            }

        }catch(\Throwable $th){
             // dd(get_class_methods($th));
             Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
             Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
             // dd("error en base ". $th->getMessage());//throw $th;
             
             $response['message'] = 'Hubo error al generar la factura : '.$th->getMessage();
             
             return $response;
             // exit();
        }
    }

    public function setInvoice($sale, $serie, $number){

        $type = $sale->sunat_code;
        $total = $sale->total;
        $subTotal = $total/1.18;
        $igv = $total - $subTotal;

        $invoice = new Invoice();
        $invoice
            ->setUblVersion('2.1')
            ->setFecVencimiento(new DateTime())
            ->setTipoOperacion('0101')
            ->setTipoDoc($type)
            ->setSerie($serie)
            ->setCorrelativo($number)
            ->setFechaEmision(new DateTime())
            ->setFormaPago(new FormaPagoContado())
            ->setTipoMoneda('PEN')
            ->setCompany($this->companyData())
            ->setClient($this->customerData())
            ->setMtoOperGravadas(number_format($subTotal,2,'.', ''))
            // ->setMtoOperExoneradas(100)
            ->setMtoIGV(number_format($igv,2,'.', ''))
            ->setTotalImpuestos(number_format($igv,2,'.', ''))
            ->setValorVenta(number_format($subTotal,2,'.', ''))
            ->setSubTotal(number_format($total,2,'.', ''))
            ->setMtoImpVenta(number_format($total,2,'.', ''));

        return $invoice;
    }

    public function setItems($data){

        $items = [];
        
        foreach($data as $item_sale){

            $product_price = Product::where('id', $item_sale->product_id)->value('price');

            $igv_base = $item_sale->price / 1.18; //igv del valor unitario
            $montoBase = number_format($igv_base * $item_sale->amount, 2,'.', ''); //multiplicamos el valor unitario por la cantidad
            $igv_item = $item_sale->price - $igv_base; //sacamos igv de producto unitario
            $igv_set = $igv_item * $item_sale->amount; // igv unitario total
            
            $discount = 0;

                $item = (new SaleDetail())
                ->setCodProducto($item_sale->product_id)
                ->setUnidad('NIU')
                ->setDescripcion($item_sale->name)
                ->setCantidad(intval($item_sale->amount))
                ->setMtoValorUnitario($igv_base);

            if($product_price > $item_sale->price)  
            {
                $factor = ($product_price - $item_sale->price) / $product_price;
                $discount = number_format($montoBase * $factor, 2,'.', '');
 
                $item->setDescuentos([
                    (new Charge())
                        ->setCodTipo('00') // Catalog. 53
                        ->setMontoBase($montoBase)
                        ->setFactor($factor)
                        ->setMonto($discount)
                ]);
            }  
            // dd($factor, $discount, $montoBase, ($montoBase - $discount), (($montoBase - $discount) * 0.18), (($montoBase - $discount) + ($montoBase - $discount) * 0.18) / $item_sale->amount, ($product_price - ($item_sale->price - 0.2)));
                
            $baseDiscount = number_format($montoBase - $discount, 2,'.', '');
            $setIgv = number_format($baseDiscount * 0.18, 2,'.', '');  

                $item->setMtoValorVenta($baseDiscount)
                ->setMtoBaseIgv($baseDiscount)
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv($setIgv)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos($setIgv)
                ->setMtoPrecioUnitario(number_format(($baseDiscount + $setIgv) / $item_sale->amount, 2,'.', ''));
                array_push($items, $item);
                $igv_base= 0;
                $montoBase = 0;
                $igv_item = 0;
                $igv_set = 0;  
                $discount = 0;
                $baseDiscount = 0; 
                $setIgv = 0;
        }
        
        return $items;    
    }

    // public function setItems($data){

    //     $items = [];
        
    //     foreach($data as $item){
    //         $igv_base = $item->price / 1.18; //igv del valor unitario
    //         $montoBase = $igv_base * $item->amount; //multiplicamos el valor unitario por la cantidad
    //         $igv_item = $item->price - $igv_base; //sacamos igv de producto unitario
    //         $igv_set = $igv_item * $item->amount; // igv unitario total
    //         // $totalANumero = $totalANumero + ($item->price * $item->amount);
    //         // dd($igv_item);
    //             $item = (new SaleDetail())
    //             ->setCodProducto($item->product_id)
    //             ->setUnidad('NIU')
    //             ->setDescripcion($item->name)
    //             ->setCantidad(intval($item->amount))
    //             ->setMtoValorUnitario($igv_base)

                





    //             ->setMtoValorVenta(number_format($montoBase,2,'.', ''))
    //             ->setMtoBaseIgv(number_format($montoBase,2,'.', ''))
    //             ->setPorcentajeIgv(18.00) // 18%
    //             ->setIgv($igv_set)
    //             ->setTipAfeIgv('10')
    //             ->setTotalImpuestos($igv_set)
    //             ->setMtoPrecioUnitario(number_format($item->price, 2,'.', ''));
    //             // dd($igv_base.'-'.$montoBase.'-'.$igv_item.'-'.$igv_set);
    //             array_push($items, $item);
    //             $igv_base= 0;
    //             $montoBase = 0;
    //             $igv_item = 0;
    //             $igv_set = 0;   
    //     }
        
    //     return $items;    
    // }
}