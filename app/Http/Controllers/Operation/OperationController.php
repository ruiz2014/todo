<?php

namespace App\Http\Controllers\Operation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Models\Admin\Product;
use App\Models\Biller\Quote;
use App\Models\Biller\PaymentMethod;
use App\Models\Biller\PaymentLog;
use App\Models\Biller\EditQuote;
use App\Models\Admin\LocalProduct;


use App\Models\Biller\TempSale;
use App\Models\Biller\TempQuote;

use Session;
use DB;

class OperationController extends Controller
{
    public function add(Request $req){

        $local = Session::get('local_id');
        $company = Session::get('company_id');

        $model = $this->getopt($req->format); //PARA VER QUE TIPO DE MODELO USAR
        $table = new $model()->getTable();
        
        $check = $model::where('company_id', $company)->where('local_id', $local)->where('code', $req->order['code'])
        ->where('status', '<', 2)
        ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
        ->value('code');

        $check22 = empty($check) ? $req->order['code'] : $check ;

        $id_order = $model::create(['company_id' => $company, 'local_id'=>$local, 'user_id'=>Session::get('user_id'), 'customer_id'=>1, 'code'=>$check22, 'product_id'=>$req->order['id'], 'amount'=>$req->order['amount'], 'price'=> $req->order['price'], 'status' => 1 ]);
        $orders = Product::select("products.name", "products.price", "ts.id", 'ts.id as temp_id', "ts.status", "ts.amount")->join($table." as ts", "ts.product_id", "=", "products.id")->where('ts.code', $check22)->get();
        
        return ['ok' => 1, 'orders' => $orders, 'tabla' => $table];
        die();
    }

    public function modifyAmount(Request $req){
        $model = $this->getopt($req->format);
        if($req->amount < 1){
            return response()->json(['ok' => 0, 'orders' => []]);
        }
        $orders = $model::where('id', $req->id)->update(['amount' => $req->amount]);
        return response()->json(['ok' => 1, 'orders' => $orders]);
    }

    public function delete(Request $req){
        $model = $this->getopt($req->format);
        $table = new $model()->getTable();

        $order = $model::find($req->id);
        $check = $order->code;
        $order->delete();

        $numberOrders = $model::where('code', $check)->count();
        $ordersSent = $model::where('code', $check)->where('status', 3)->count();
        $sign = $numberOrders == $ordersSent ? 1 : 0;

        $orders = Product::select("products.name", "products.price", "ts.id", 'ts.id as temp_id', "ts.status", "ts.amount")->join($table." as ts", "ts.product_id", "=", "products.id")->where('ts.company_id', Session::get('company_id'))->where('ts.local_id', Session::get('local_id'))->where('ts.code', $check)->get();
        return response()->json(['ok' => 1, 'orders' => $orders, 'sign'=> $sign]);
    }

    public function getOpt($opt){
        $model = false;

        switch($opt){
            case 1 :
                $model = TempSale::class;
                break;
            case 2 :
                $model = TempQuote::class; 
                break; 
            case 3 :
                $model = EditQuote::class;
                break;     
        }

        return $model;
    }

    public function add_edit(Request $req){
        $local = Session::get('local_id');
        $company = Session::get('company_id');

        $model = $this->getopt($req->format);
        $table = new $model()->getTable();
        
        $check = $model::where('edit_code', $req->order['code'])->value('edit_code');

        $check22 = empty($check) ? $req->order['code'] : $check ;

        $id_order = $model::create(['edit_code'=>$check22, 'temp_id'=>null, 'edit_prod_id'=>$req->order['id'], 'edit_amount'=>$req->order['amount'], 'edit_price'=> $req->order['price']]);
        $orders = Product::select("products.name", "products.price", "ts.id", "temp_id", "ts.edit_amount as amount")->join($table." as ts", "ts.edit_prod_id", "=", "products.id")->where('ts.edit_code', $check22)->get();
        
        return ['ok' => 1, 'orders' => $orders, 'tabla' => $table];
        die();
    }

    public function modifyAmount_edit(Request $req){
        $model = $this->getopt($req->format);
        if($req->amount < 1){
            return response()->json(['ok' => 0, 'orders' => []]);
        }
        $orders = $model::where('id', $req->id)->update(['edit_amount' => $req->amount]);
        return response()->json(['ok' => 1, 'orders' => $orders]);
    }

    public function delete_edit(Request $req){
        $model = $this->getopt($req->format);
        $table = new $model()->getTable();

        $order = $model::find($req->id);
        $check = $order->edit_code;
        $order->delete();
        // EditQuote::where('id', $req->id)->delete();

        $numberOrders = $model::where('edit_code', $check)->count();
        $ordersSent = $model::where('edit_code', $check)->count();
        $sign = $numberOrders == $ordersSent ? 1 : 0;

        $orders = Product::select("products.name", "products.price", "ts.id", "temp_id", "ts.edit_amount as amount")->join("edit_quotes as ts", "ts.edit_prod_id", "=", "products.id")->where('ts.edit_code', $check)->get();
        return response()->json(['ok' => 1, 'orders' => $orders, 'sign'=> $sign]);
    }
}