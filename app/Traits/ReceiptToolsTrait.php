<?php 
namespace App\Traits;

use App\Models\User;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;
use App\Models\Biller\ReceiptLog;

use DB;
use DOMDocument;

trait ReceiptToolsTrait {
    
    public function selectIdXml($type, $id){
        $voucher = false;
        switch($type){
            case 'factura':
                $voucher = $this->getNameVoucher('attentions', 'document_code', $id); 
                break;
            case 2:
                $voucher = $this->getNameVoucher('attentions', 'document_code', $id); 
                break; 
            case 'resumen':
                $voucher = $this->getNameVoucher('summaries', 'id', $id); 
                break;
            case 100:
                $voucher = $this->getNameVoucher('voided', 'id', $id);  
                break;             
        }

        return $voucher;
    }

    public function getNameVoucher($table, $column, $id){
        return $identificador = DB::table($table)
                                ->where($column, $id)
                                ->select('identifier', $column)
                                ->first();
    }
    
    public function getStringPath($name, $type, $opt){
        
        $path = $name->identifier.'.xml';
        $string = '20608894447';

        if($opt == 2){
            $path = $name->identifier.'.zip';
            $string = 'R-20608894447';
        }

        switch($type){
            case 'factura' :  
                return 'Bill/20608894447/'.$string.'-01-'.$path;
            break;
            case 2 :  
                return 'Ticket/20608894447/'.$string.'-03-'.$path;
            break;
            case 'resumen' :  
                return 'Summary/20608894447/'.$string.'-'.$path;
            break;
            case 100 :  
                return 'Voided/20608894447/'.$string.'-'.$path;
            break;
        }
    }
}