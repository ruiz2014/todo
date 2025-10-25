<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Admin\SuperAdmin\Sector;
use App\Models\Admin\SuperAdmin\SetUpCompany;
use Session;
use DB;

// use Illuminate\Support\Collection; 

class CompanyHelper
{
   
//  protected static $_status = [
//         1=> [
//             'value' => 1,
//             'displayName' => 'Active',
//         ],
//         2 => [
//             'value' => 2,
//             'displayName' => 'Inactive',
//         ],
//         3 => [
//             'value' => 3,
//             'displayName' => 'Delete',
//         ],

//     ];

//     public static function getStatusesList()
//     {
//         $status = (new Collection(self::$_status))->pluck('displayName', 'value')->toArray();


//         return $status;
//     }

    public static function getBelong(){
        return DB::table('companies')->where("id", Session::get('company_id'))->value("sector_id");
    }

    public static function getSector(){
        // $company = Session::get('company_id');
        $url = 'panel';
        $name = 'panel';

        $sector = Sector::select('sectors.name as name', 'sectors.id')->join('companies as co', 'sectors.id', '=', 'co.sector_id')->where('co.id', Session::get('company_id'))->first();
    // dd($sector);
        if($sector){
            $name = $sector->name;
            switch($sector->id){
                case 2 :
                    $url = 'shop';
                    break;
                case 3 : 
                    $url = 'restaurant';
                    break;
                case 4 :
                    $url = 'hotel';
                    break;  
                default :
                    $url =  'panel' ;      
            }
        }
        
        return $collection = collect([ 'name'=>$name, 'url'=>$url ]);
    }

    public static function searchAll($query, $text, $join, $where, $orWhere){
        foreach($join as $table => $row){
                $query->leftJoin($table, $row[0], $row[1], $row[2]);
        }

        foreach($where as $field => $condition){ 
            $query->where($field, $condition[0], $condition[1]);
        }

        $query->where(function($query) use ($orWhere, $text){
                foreach($orWhere as $column => $value){
                    $query->orWhere($column, $value[0], $value[1]);
                }
        });

        return $query;
    }

    public static function downloadXml($id, $type){
        $band = false;
        $nameReceipt = self::selectIdXml($type, $id);

        if(isset($nameReceipt->identifier)){
            $path = self::getStringPath($nameReceipt, $type, 1);
// dd($nameReceipt, $path);
            if(!file_exists(public_path()."/sunat_documents/$path")){
                return $band; 
            }
        
            return $path;
        }else{
            return $band;
        }
        return $band;
    }

    public static function downloadCdr($id, $type){
        $band = false;
        $nameReceipt = self::selectIdXml($type, $id);
        if(isset($nameReceipt->identifier)){
            switch($type){
                case 1:
                    break;
                case 2 :
                    break;
            }

            $path = self::getStringPath($nameReceipt, $type, 2); // 2 PARA CDR
            if(!file_exists(public_path()."/sunat_documents/$path")){
                return $band; 
            }
            return $path;

        }else{
            return $band;
        }
    }

    public static function selectIdXml($type, $id){
        $voucher = false;
        switch($type){
            case 'boleta':
            case 'factura':
                $voucher = self::getNameVoucher('attentions', 'document_code', $id); 
                break;
            case 2:
                $voucher = self::getNameVoucher('attentions', 'document_code', $id); 
                break; 
            case 'resumen':
                $voucher = self::getNameVoucher('sumaries', 'id', $id); 
                break;
            case 100:
                $voucher = self::getNameVoucher('voided', 'id', $id);  
                break;             
        }

        return $voucher;
    }

    public static function getNameVoucher($table, $column, $id){
        return $identificador = DB::table($table)
                                ->where($column, $id)
                                ->select('identifier', $column)
                                ->first();
    }
    
    public static function getStringPath($name, $type, $opt){
        
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
            case 'boleta' :  
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

    public static function a_casa($route, $code = null, $alert = null, $message = null){
        if(SetUpCompany::where('company_id', Session::get('company_id'))->value('redirect_after')){
            $cadena = back()->with('success', 'operacion exitosa .....');
        }else{
            $cadena = redirect()->route($route, ['order' => $code ])->with($alert, $message);
        }
        
        return $cadena;
    }
}