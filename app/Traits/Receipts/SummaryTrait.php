<?php
namespace App\Traits\Receipts;

use App\Models\Usuario;
use App\Models\Biller\Attention;
use App\Models\Biller\Sumary as Resume;

// use DateTime;

// use Greenter\Model\Response\BillResult;
// use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
// use Greenter\Model\Sale\Invoice;
// use Greenter\Model\Sale\SaleDetail;
// use Greenter\Model\Sale\Legend;
// use Greenter\Ws\Services\SunatEndpoints;

use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;

use App\Http\Controllers\NumeroALetras;
use App\Traits\BillingConfigurationTrait;
use App\Traits\BillingToolsTrait;
use DateTime;
use Carbon\Carbon;

trait SummaryTrait {
    use BillingConfigurationTrait, BillingToolsTrait;

    private $document_id =[];

    public function setResume($documents, $date){
        // $cad = 'Boleta Aceptada enviada en Resumen RC-20230306-00001';
        // dd(substr($cad,-17));
        // $this->document_id = [12, 13, 14];
        // $ids = $documents->pluck('id')->toArray();
        // Attention::whereIn('id', $ids)->update(['completed'=>10]);
        // dd("salio", $this->document_id);
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

        $correlative = $this->getCorrelative();
        $summary = $this->setSummary($date, $correlative, $documents);
        // $summaryDetails = $this->setSummaryDetails($documents);

        $see = $this->config(); //AQUI EMPIEZA .................................

        // $xml_string = $see->getXmlSigned($sum);
        // $doc = new DOMDocument();
        // $doc->loadXML($xml_string);


        $xmlSigned = $this->xmlSigned($see->getXmlSigned($summary));//TRAIT BILLTOOL
        $hash=$this->getHashXml($xmlSigned); //TRAIT BILLTOOL
        $xml_id=$this->getIdXml($xmlSigned); //TRAIT BILLTOOL
        $rucCustomer = '20608894447';
        // dd($xmlSigned, $hash, $xml_id, $see->getXmlSigned($summary));

        $this->writeXml($summary, $see->getFactory()->getLastXml(), $rucCustomer, 101); 
        // file_put_contents(public_path().'/Sunat/Summary/'.$summary->getName().'.xml', $see->getFactory()->getLastXml());

        $result = $see->send($summary);

        $validated = $this->validateResult($result, $response);

        if($validated['error']){ //SI HUBO PROBLEMA EN EL ENVIO "HTTP" SE ANULA 
            return $validated;
        }
        
        $ticket = $result->getTicket();
        $statusResult = $see->getStatus($ticket);

        $validateStatus = $this->validateResult($statusResult, $response);
        // dd($validateStatus);
        if($validateStatus['error']){ //SI HUBO PROBLEMA EN EL ENVIO "HTTP" SE ANULA 
            // Attention::where('id', $sale_data->id)->update(['hash'=>$hash, 'identifier'=>$xml_id, 'resume' => $resumen, 'cdr'=>$validated['cdr'], 'message'=>$validated['message'], 'dispatched'=>1]);
            return $validateStatus;
        }

        $cdr = $statusResult->getCdrResponse();
        $code_cdr = (int)$cdr->getCode();

        $this->writeCdr($summary, $statusResult->getCdrZip(), $rucCustomer, 101); //TRAIT RECEIPTSTOOL

        $this->saveReceipt($documents, $ticket, 1); //TRAIT BILLINGTOOL

        list($message, $alert, $update_ts) = $this->validateCrd($code_cdr);

        $message .=''.$cdr->getDescription().PHP_EOL;
// dd('llego 3.0', $cdr, $message);
        if($update_ts){
            Attention::whereIn('id', $this->document_id)->update(['cdr'=>$code_cdr, 'success'=>1, 'message'=>'Boleta Aceptada enviada en Resumen '.$xml_id, 'dispatched'=>1, 'received'=>1, 'completed'=>1, 'status'=>1]);
        }

        Resume::create(['company_id'=>request()->session()->get('company_id'), 'local_id'=>request()->session()->get('local_id'), 'user_id'=>request()->session()->get('user_id'), 'identifier'=>$xml_id, 'ticket'=>$ticket, 'cdr'=>$code_cdr, 'status'=>$update_ts, 'hash'=>$hash, 'message'=>$message, 'date_created'=>new DateTime($date), 'date_send'=>new DateTime()]);

        $response = [
            'success' => true,
            'alert' => $alert,
            'message' => $message,
            'cdr' => $code_cdr,
            'nameId' => $xml_id,
            'attentionId' => null,
            'update' => $update_ts
        ];

        // dd($hash, $xml_id, $see, $see->getFactory(), $result, $validated, $response, $message, $alert, $response);
        return $response;

        dd($result, $hash, $summary, $xml_id, $validated, $ticket, $statusResult, $validateStatus);


        // $hash = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        // $xml = $doc->getElementsByTagName('ID')->item(0)->nodeValue;

        // file_put_contents(public_path().'/Sunat/Resumen/'.$sum->getName().'.xml', $see->getFactory()->getLastXml());
        
        // $config->writeXml($invoice, $see->getFactory()->getLastXml(), $empresa->Ruc, 1);
        // $result = $see->send($sum);

    }

    public function setSummary($date, $correlative, $documents){
        $sum = new Summary();

        $sum->setFecGeneracion(new DateTime($date))
            ->setFecResumen(new DateTime())//'-1days' ENTENDER ESTA PARTE DE LAS FECHAS ... OJOOOOOO
            ->setCorrelativo($correlative)
            ->setCompany($this->companyData())
            ->setDetails($this->setSummaryDetails($documents));
        return $sum;

        // $sum = new Summary();
        // $sum->setFecGeneracion(new DateTime($fecha))
        //     ->setFecResumen(new DateTime())
        //     ->setCorrelativo($correlative)
        //     ->setCompany($this->datosCompania())
        //     ->setDetails($items);

    }

    public function setSummaryDetails($documents){

        $array = [];

        foreach($documents as $ticket)
        {
            $ids = $ticket->id;
            $status= $ticket->low == 0 ? '1' : '3';

            $detail = new SummaryDetail();
                $detail->setTipoDoc('03')
                ->setSerieNro($ticket->identifier)
                ->setEstado($status)
                ->setClienteTipo($ticket->customer->tipo_doc) // $ticket->document_type aqui se debe arreglar a tipo de documento del cliente
                ->setClienteNro($ticket->customer->document)
                ->setTotal(number_format($ticket->total, 2,'.', ''))
                ->setMtoOperGravadas(number_format($ticket->total/1.18, 2,'.', ''))
                ->setMtoOperInafectas(0.00)
                ->setMtoOperExoneradas(0.00)
                ->setMtoOtrosCargos(0.00)
                ->setMtoIGV(number_format($ticket->total - ($ticket->total/1.18), 2,'.', ''));
                
                array_push($array, $detail);
                array_push($this->document_id, $ids);
            //array_push($denegado, $boleta->id_boleta); //para actualizar las boletas a estado "Resumen aceptado"
            // $denegado[$boleta->id_boleta] = $estado;
            //array_push($baja, $estado); //para actualizar las boletas a estado "Resumen aceptado"
        }

        return $array;

        // foreach($documents as $doc){
            
        //     $total = Attention::where('id', $doc->id)->value('total');
        //     $subTotal = $total/1.18;
        //     $igv = $total - $subTotal;

        //     $detail = new SummaryDetail();
        //     $detail->setTipoDoc('03')
        //         ->setSerieNro($doc->identifier)
        //         ->setEstado('1')
        //         ->setClienteTipo('1')
        //         ->setClienteNro('00000000')
        //         ->setTotal(number_format($total, 2,'.', ''))
        //         ->setMtoOperGravadas(number_format($subTotal, 2,'.', ''))
        //         ->setMtoOperInafectas(0.00)
        //         ->setMtoOperExoneradas(0.00)
        //         // ->setMtoOperExportacion(10.555)
        //         ->setMtoOtrosCargos(0.00)
        //         ->setMtoIGV(number_format($igv, 2,'.', ''));

        //     array_push($items, $detail);    
        // }
    }

     protected function getCorrelative(){
        $number = Resume::orderBy('id', 'Desc')->first();

        if($number)
        {
        	$correlative=(int)$number->id;
        	$correlative=str_pad($correlative+1 , 5, "0", STR_PAD_LEFT);

            return $correlative;
        }

        return $correlative = '00001';
    }

    




        

}