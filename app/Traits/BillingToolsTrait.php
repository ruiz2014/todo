<?php 
namespace App\Traits;

use App\Models\User;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;

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