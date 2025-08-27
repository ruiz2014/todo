<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Admin\SuperAdmin\Sector;

use App\Models\Biller\TempSale;
use App\Models\Biller\TempQuote;
use App\Models\Admin\Product;

use Session;
use DB;

// use Illuminate\Support\Collection; 

class OperationHelper
{
    static $model;
    
    public function __construct($opt){
        
        switch($opt){
            case 1 :
                self::$model = TempSale::class;
                break;
            case 2 :
                self::$model = TempQuote::class; 
                break;  
        }
    }

    public static function add($req){

        $local = Session::get('local_id');
        $company = Session::get('company_id');
        $modelo = self::$model;
        
        $check = $modelo::where('company_id', $company)->where('local_id', $local)->where('code', '202508231324435')
        // ->where('status', '<', 2)
        // ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
        ->value('code');
dd($check);
        $check22 = empty($check) ? $req->order['code'] : $check ;

        $id_order=self::$model::create(['company_id' => $company, 'local_id'=>$local, 'user_id'=>Session::get('user_id'), 'customer_id'=>1, 'code'=>$check22, 'product_id'=>$req->order['id'], 'amount'=>$req->order['amount'], 'price'=> $req->order['price'], 'status' => 1 ]);
        $orders = Product::select("products.name", "products.price", "ts.id", "ts.status", "ts.amount")->join("temp_sales as ts", "ts.product_id", "=", "products.id")->where('ts.code', $check22)->get();
        
        return ['ok' => 1, 'orders' => $orders];
        // return self::$model;
        
        // return response()->json(['ok' => 1, 'orders' => $orders]);

        die();
        // dd($modelo);
        // $code = date('YmdHis');
          // dd(self::$model::find(2));
        // return response()->json(['ok' => 1, 'orders' => $req->order['code']]);
    }
}