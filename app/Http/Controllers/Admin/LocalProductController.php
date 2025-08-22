<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\LocalProduct;
use App\Models\Admin\BuyProduct;
use App\Models\Biller\TempBuy;
use App\Models\Admin\Kardex;
use App\Models\Admin\ProductEntry;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\CompanyHelper;
use DB;
use Session;

class localProductController extends Controller
{
    public function index(Request $request){
        // DB::enableQueryLog();
        $products = Product::select(DB::raw("CONCAT_WS(' ', name,' ',description, ' ',price) AS name"),'id')->where('company_id', $request->session()->get('company_id'))->pluck('name', 'id');
        $text = $request->search;
        // dd();
        $select = ['local_products.product_id', 'p.name', 'p.description', 'p.price', 'p.category_id', 'c.category_name', 'local_products.stock'];
        $where = ['local_products.local_id'=> ['=', $request->session()->get('local_id')]];
        $orWhere = ['p.name'=>['like', '%'.$text.'%'], 'p.description' => ['like', '%'.$text.'%'], 'p.price' => ['like', '%'.$text.'%'], 'c.category_name' => ['like', '%'.$text.'%']];
        $join = ['products as p' => ['local_products.product_id', '=', 'p.id'], 'categories as c' => ['p.category_id', '=', 'c.id'] ];


        $query  = LocalProduct::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $local_products = $result->paginate(2);

        return view('admin.lp.index', compact('products', 'local_products', 'text'));
    }

    public function newEntries(Request $request){

        $local_id = $request->session()->get('local_id'); 
        $text = $request->search;
        // dd($local_id, $text);
        $select = ['buy_products.id', 'buy_products.code', 'buy_products.total', 'buy_products.document', 'buy_products.created_at', 'buy_products.status', 'e.name as l_type', 'p.name'];
        $where = ['buy_products.company_id'=> ['=', $request->session()->get('company_id')], 'buy_products.location_type'=> ['=', 1], 'buy_products.location_id'=> ['=', $local_id]];
        $orWhere = ['buy_products.total'=>['like', '%'.$text.'%'], 'buy_products.document' => ['like', '%'.$text.'%'], 'p.name' => ['like', '%'.$text.'%'], 'e.name' => ['like', '%'.$text.'%'], 'buy_products.created_at'=> ['like', '%'.$text.'%']];
        $join = ['providers as p' => ['buy_products.provider_id', '=', 'p.id'], 'establishments as e' => ['buy_products.location_type', '=', 'e.type']];

        $query  = BuyProduct::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $buyProducts = $result->orderBy('buy_products.id', 'desc')->paginate();

        $noty = false;
        
        return view('admin.lp.entry', compact('buyProducts', 'text', 'noty'))
            ->with('i', ($request->input('page', 1) - 1) * $buyProducts->perPage());    
    }

    public function entryAction(Request $request, $code){

        $buyProduct = BuyProduct::where('company_id', $request->session()->get('company_id'))->where('code', $code)->first();
        // dd($buyProduct);
        $total = TempBuy::where('company_id', $request->session()->get('company_id'))->where('code', $code)->sum(DB::raw('stock * cost'));
        $temps = TempBuy::select('p.name', 'temp_buys.cost', 'temp_buys.stock')->join('products as p', 'temp_buys.product_id', '=', 'p.id')->where('code', $code)->get();
        $methods = [];
 
        return view('admin.lp.register', compact('code', 'buyProduct', 'total', 'temps', 'methods'));
    }

    public function register(Request $request, $code){
        
        $buys = TempBuy::where('company_id', $request->session()->get('company_id'))->where('code', $code)->get();
        // dd($buys);
        foreach($buys as $buy){

            $detail_prod = Product::where('id', $buy->product_id)->first();
            $detail_prod->increment('stock', $buy->stock); 

            $lp=LocalProduct::where('local_id', $request->session()->get('local_id'))->where('product_id', $buy->product_id)->first();
            if($lp){
                $lp->increment('stock', $buy->stock);
            }
            else{
                LocalProduct::create(['user_id'=>$request->session()->get('user_id'), 'local_id'=>$request->session()->get('local_id'), 'product_id'=>$buy->product_id, 'stock'=>$buy->stock, 'approved'=>1]);
            }
                
            ProductEntry::create(['company_id'=>$request->session()->get('company_id'), 'user_id'=>$request->session()->get('user_id'), 'local_id'=>$request->session()->get('local_id'), 'product_id'=>$buy->product_id, 'amount'=>$buy->stock, 'cost'=>1]);
                
                
            $karde = Kardex::where('local_id', $request->session()->get('local_id'))
                    ->where('product_id', $buy->product_id)
                    ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
                    ->first();
        
            if($karde){
                $karde->increment('entry', $buy->stock);
            }
            else{
                Kardex::create(['company_id'=>$request->session()->get('company_id'), 'local_id'=>$request->session()->get('local_id'), 'product_id'=>$buy->product_id, 'entry'=>$buy->stock, 'output'=>0]);
            } 
        }
        BuyProduct::where('company_id', $request->session()->get('company_id'))->where('code', $code)->update(['status'=> 1]);
       return Redirect::route('lp.index')->with('success', 'Se registro correctamente los articulos de la compra ....');
    }
}