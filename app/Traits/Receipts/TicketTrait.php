<?php
namespace App\Traits\Receipts;

use App\Models\Usuario;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale; 

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

trait TicketTrait {

    use BillingConfigurationTrait, BillingToolsTrait;

    public function setTicket($code){

        $response = [
            'success' => false,
            'alert' => 'danger',
            'message' => 'No se encontro ninguna orden ',
            'nameId' => '',
            'attentionId' => null, 
        ];

        if (!Attention::where('document_code', $code)->exists()) {
             dd('if');
            return  $response;
        }
            
        try {
                
            $items = [];

            $sale_data = $this->getSale($code);  //TRAIT BillingToolsTrait
            $sale_items = $this->getDetails('temp_sales', $code);

            if(!$sale_items->isEmpty()){
                $serie = $this->formatSerie($sale_data->serie, $sale_data->sunat_code);
                $number = str_pad($sale_data->numeration, 8, "0", STR_PAD_LEFT);
            
                $invoice = $this->setInvoiceTicket($sale_data, $serie, $number);
                $items = $this->setItemsTicket($sale_items);

                $convertNumberToLetters = new NumeroALetras();
                $numberToLetters = $convertNumberToLetters->convertir($sale_data->total, 'soles');
                
                $legend = (new Legend())
                    ->setCode('1000')
                    ->setValue($numberToLetters);
                
                $invoice->setDetails($items)
                        ->setLegends([$legend]);
                    
                $see = $this->config();
                // ****** file_put_contents(public_path().'/Sunat/Boletas/'.$invoice->getName().'.xml', $see->getFactory()->getLastXml());
                
                $xmlSigned = $this->xmlSigned($see->getXmlSigned($invoice));//TRAIT BILLTOOL
                $hash=$this->getHashXml($xmlSigned); //TRAIT BILLTOOL
                $xml_id=$this->getIdXml($xmlSigned); //TRAIT BILLTOOL
                $date = new DateTime();
                $_fecha = $date->format('Y-m-d');
                $resumen = '20000000001|03|'.$serie.'|'.$number.'|'.round(45, 2).'|'.round(45, 2).'|'.$_fecha.'|06|48712312';
                $message = 'Guardado para envio en resumen';

                // dd($hash, $xml_id, $xmlSigned);
            
                TempSale::where('code', $code)->update(['status'=> 2]);
                
                Attention::where('id', $sale_data->id)->update(['hash'=>$hash, 'identifier'=>$xml_id, 'resume' => $resumen, 'message'=>$message]);

                $response = [
                    'success' => true,
                    'alert' => 'success',
                    'message' => $message,
                    'nameId' => $xml_id,
                    'attentionId' => $sale_data->id,
                ];
                    
                return $response; 

            }
            else{
                    dd('no hay');
            }
        } catch (\Throwable $th) {

                // dd(get_class_methods($th));
                Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
                Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                // dd("error en base ". $th->getMessage());//throw $th;
                
                $response['message'] = 'Hubo error al generar la boleta : '.$th->getMessage();
                
                return $response;
        }
        
    }

    public function setInvoiceTicket($sale, $serie, $number){

        $type = $sale->sunat_code;
        $total = $sale->total;
        $subTotal = $total/1.18;
        $igv = $total - $subTotal;
            
        $invoice = new Invoice();
        $invoice
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101')
            ->setTipoDoc($type)
            ->setSerie($serie)
            ->setCorrelativo($number)
            ->setFechaEmision(new DateTime())
            ->setTipoMoneda('PEN')
            ->setCompany($this->companyData())
            ->setClient($this->customerData())
            ->setMtoOperGravadas(number_format($subTotal,2,'.', ''))
            ->setMtoIGV(number_format($igv,2,'.', ''))
            ->setTotalImpuestos(number_format($igv,2,'.', ''))
            ->setValorVenta(number_format($subTotal,2,'.', ''))
            ->setSubTotal(number_format($total,2,'.', ''))
            ->setMtoImpVenta(number_format($total,2,'.', ''));

        return $invoice;
    }

    public function setItemsTicket($data){

        $items = [];
        foreach($data as $item){
                    // $trueQua = $item->amount * 100;
                    // $unitario = $item->price / $trueQua;

                            // $igv_base = $item->Costo / 1.18; //igv del valor unitario
                            // $montoBase = $igv_base * $item->Cantidad; //multiplicamos el valor unitario por la cantidad
                            // $igv_item = $item->Costo - $igv_base; //sacamos igv de producto unitario
                            // $igv_set = $igv_item * $item->Cantidad; // igv unitario total
                
            $igv_base = $item->price / 1.18; //igv del valor unitario
            $montoBase = $igv_base * $item->amount; //multiplicamos el valor unitario por la cantidad
            $igv_item = $item->price - $igv_base; //sacamos igv de producto unitario
            $igv_set = $igv_item * $item->amount; // igv unitario total

            $item = (new SaleDetail())
            ->setCodProducto($item->product_id)
            ->setUnidad('NIU') // Unidad - Catalog. 03
            ->setDescripcion($item->name)
            ->setCantidad(intval($item->amount))
            ->setMtoValorUnitario($igv_base)
            ->setMtoValorVenta(number_format($montoBase,2,'.', ''))
            ->setMtoBaseIgv(number_format($montoBase,2,'.', ''))
            ->setPorcentajeIgv(18.00) // 18%
            ->setIgv($igv_set)
            ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
            ->setTotalImpuestos($igv_set) // Suma de impuestos en el detalle 
            ->setMtoPrecioUnitario(number_format($item->price, 2,'.', ''));
                
            array_push($items, $item);
            $igv_base= 0;
            $montoBase = 0;
            $igv_item = 0;
            $igv_set = 0; 
        }

        return $items;
    }

}