<?php 
namespace App\Traits;

use App\Models\User;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;
use App\Models\Biller\ReceiptLog;

// use Greenter\Data\DocumentGeneratorInterface;
// use Greenter\Model\DocumentInterface;

// use Greenter\Ws\Services\SunatEndpoints;
// use Greenter\See;

// use Greenter\Model\Client\Client;
// use Greenter\Model\Company\Company;
// use Greenter\Model\Company\Address;

// use DOMDocument;
use DB;
use DOMDocument;

trait BillingToolsTrait {

    public function getSale($code){
        $attentionData = Attention::where('document_code', $code)->orderBy('id', 'desc')->first();

        return $attentionData;
    }

    public function getDetails($table, $code){
        $attentionItems = DB::table($table)->join('products as pr', $table.'.product_id', '=', 'pr.id')
                            ->where($table.'.code', $code)->get();
        return  $attentionItems;                   
    }

    public function xmlSigned($xml_signed){
        // $xml_signed = $see->getXmlSigned($document);
        $doc = new DOMDocument();
        $doc->loadXML($xml_signed);
        return $doc;
    }

    protected function getHashXml($xml){
        return  $xml->getElementsByTagName('DigestValue')->item(0)->nodeValue;
    }

    protected function getIdXml($xml){
        return  $xml->getElementsByTagName('ID')->item(0)->nodeValue;
    }

    public function validateResult($result, $response){
        
        if(!$result->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
                    
            $response['message'] = $result->getError()->getMessage();
            $response['cdr'] = $result->getError()->getCode();
            $response['error'] = true;

            return $response;
        }

        return $response;
    }

    public function validateCrd($code){
        if($code === 0) {

            $message = 'ACEPTADA ';
            $alert='success';
            $update = true;

                    // echo 'ESTADO: ACEPTADA'.PHP_EOL;
                    // if (count($cdr->getNotes()) > 0) {
                    //     echo 'OBSERVACIONES:'.PHP_EOL;
                    //     // Corregir estas observaciones en siguientes emisiones.
                    //     var_dump($cdr->getNotes());
                    // }  

        } else if ($code >= 2000 && $code <= 3999) {
            $message = 'RECHAZADA ';
            $alert='danger';
            $update = false;
                    
        }else if($code >= 4000){
            $message = 'Observacion ';
            $alert='info';
            $update = true;
        } 
        else {
            /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
            /*code: 0100 a 1999 */
            // echo 'Excepción';
            $message = 'Excepción ';
            $alert='warning ';  
            $update = false;                  
        }

        return array($message, $alert, $update);
    }
    
    public function setCorrelative($table, $where, $type){
        $correlative = DB::table($table)
                            ->where($where, $type)
                            ->orderBy('numeration', 'desc')
                            ->first();
		if($correlative)
		{
			$number = $correlative->numeration;
            return $number + 1;
		}

        return 1;
    }

    public function saveReceipt($documents, $ticket, $documents_type){
        
        foreach($documents as $document)
        {
            ReceiptLog::create([
                'company_id'=> request()->session()->get('company_id'),
                'local_id'=> request()->session()->get('local_id'),
                'user_id'=> request()->session()->get('user_id'), //Auth-asuser
                'receipt_code'=>$document->document_code,
                'identifier'=>$document->identifier,
                'ticket'=>$ticket,
                'receipt_type'=>$documents_type, // 1 PARA RESUMEN, 2 PARA BAJA FACTURA
            ]);
        }
    }

    public function formatSerie($serie, $type){ //SI VALE ESTA FUNCION
        switch($type){
            case '01': //PARA FACTURA
                $serie = 'F'.str_pad($serie, 3, "0", STR_PAD_LEFT);
                break;
            case '03': //PARA BOLETA
                $serie = 'B'.str_pad($serie, 3, "0", STR_PAD_LEFT);
                break;
            case 7:
                $serie = 'FC'.str_pad($serie, 2, "0", STR_PAD_LEFT);
                break;
            case 8:
                $serie = 'FD'.str_pad($serie, 2, "0", STR_PAD_LEFT);
                break;
            case 9:
                $serie = 'T'.str_pad($serie, 3, "0", STR_PAD_LEFT);
                break;    
            default:
                $serie ='001';
        }
        return $serie;
    }
}