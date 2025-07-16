<?php
namespace App\Traits\Receipts;

use App\Models\Usuario;
// use App\Models\Sale\Sale;
use App\Models\Biller\Attention;
// use App\Traits\Sunat\SunatTrait;
use App\Traits\BillingConfigurationTrait;
use App\Traits\BillingToolsTrait;
use DateTime;

use Greenter\Model\Response\BillResult;
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

    public function setBill($code){



        $response = [
            'success' => false,
            'alert' => 'danger',
            'message' => 'No se encontro ninguna orden ',
            'cdr' => null,
            'nameId' => '',
            'attentionId' => null
        ];

        if (!Attention::where('document_code', $code)->exists()) {
            dd('if');
            return  $response;
        }
                
        try {  
            
            $items = [];
 
            $codigo ='';
            $mensaje = '';
            
            $sale_data = $this->getSale($code);  //TRAIT BillingToolsTrait
            $sale_items = $this->getDetails('temp_sales', $code);
            // $band = 0;
            // $message = '';

            // dd($sale_data, $sale_items);

            if(!$sale_items->isEmpty()){
                // dd($sale_data);
                $serie = $this->formatSerie($sale_data->serie, $sale_data->sunat_code);
                $number = str_pad($sale_data->numeration, 8, "0", STR_PAD_LEFT);

                $invoice = $this->setInvoice($sale_data, $serie, $number);
                $items = $this->setItems($sale_items);
                // dd($invoice, $items);
                $convertNumberToLetters = new NumeroALetras();
                $numberToLetters = $convertNumberToLetters->convertir($sale_data->total, 'soles');
                $invoice->setDetails($items)
                ->setLegends([
                    (new Legend())
                        ->setCode('1000')
                        ->setValue($numberToLetters)
                ]);     

                $see = $this->config(); //AQUI EMPIEZA .................................

//  dd($invoice, $items, $see, $see->getFactory());

                $xmlSigned = $this->xmlSigned($see->getXmlSigned($invoice));//TRAIT BILLTOOL
                $hash=$this->getHashXml($xmlSigned); //TRAIT BILLTOOL
                $xml_id=$this->getIdXml($xmlSigned); //TRAIT BILLTOOL
                $date = new DateTime();
                $_fecha = $date->format('Y-m-d');
                $resumen = '20000000001|01|'.$serie.'|'.$number.'|'.round(45, 2).'|'.round(45, 2).'|'.$_fecha.'| 01 | 29781231232';

                $result = $see->send($invoice);

                // dd($invoice, $items, $see, $see->getFactory(), $xmlSigned, $see->getFactory()->getLastXml());
                dd($hash, $xml_id, $see, $see->getFactory(), $result);



                $result = $this->sendSunat($invoice, 1);
                // ---$xmlId = $this->getIdXml($this->see->getFactory()->getLastXml());//$this->getIdDocXml($invoice, $this->see); 
                // $hash = $this->getHashSign($invoice, $this->see); //TRAIT SalesToolsTrait
                //---$hash = $this->getHashXml($this->see->getFactory()->getLastXml());  //TRAIT SalesToolsTrait
                // dd($hash.' '.$xmlId);

                $resume = '01|'.$this->serie.'|'.$this->number.'|'.round($this->igv, 2).'|'.round($this->total, 2).'|'.$sale_data->date_f.'|'.$sale_data->code_sunat.'|'.$sale_data->document; //$this->getResume($code);
                $check = $this->checkStatusSuccess($result, $invoice);
                // dd($check);

                if($check){
                    $this->cdr = $this->getCdr($result, $invoice); 
                    $this->code = (int)$this->cdr->getCode();
                }
                
                $resp = array('band'=> $check, 'identifier'=>$xmlId, 'hash'=> $hash, 'resume'=> $resume, 'cdr'=>$this->cdr,'code'=> $this->code, 'message'=> $this->message);
                // dd($resp);
                return $resp;
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
        
        foreach($data as $item){
            $igv_base = $item->price / 1.18; //igv del valor unitario
            $montoBase = $igv_base * $item->amount; //multiplicamos el valor unitario por la cantidad
            $igv_item = $item->price - $igv_base; //sacamos igv de producto unitario
            $igv_set = $igv_item * $item->amount; // igv unitario total
            // $totalANumero = $totalANumero + ($item->price * $item->amount);
            // dd($igv_item);
                $item = (new SaleDetail())
                ->setCodProducto($item->code)
                ->setUnidad('NIU')
                ->setDescripcion($item->description)
                ->setCantidad(intval($item->amount))
                ->setMtoValorUnitario($igv_base)
                ->setMtoValorVenta(number_format($montoBase,2,'.', ''))
                ->setMtoBaseIgv(number_format($montoBase,2,'.', ''))
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv($igv_set)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos($igv_set)
                ->setMtoPrecioUnitario($item->price);
                // dd($igv_base.'-'.$montoBase.'-'.$igv_item.'-'.$igv_set);
                array_push($items, $item);
                $igv_base= 0;
                $montoBase = 0;
                $igv_item = 0;
                $igv_set = 0;   
        }
        
        return $items;    
    }
}