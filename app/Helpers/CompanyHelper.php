<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Admin\SuperAdmin\Sector;
use Session;
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
}