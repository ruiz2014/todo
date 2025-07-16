<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\LocalProduct;
// use App\Models\Admin\Kardex;
// use App\Models\Admin\ProductEntry;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;

class localProductController extends Controller
{
    public function index(Request $req){
        $products = Product::select(DB::raw("CONCAT(name,' ',description, ' ',price) AS name"),'id')->pluck('name', 'id');
        $local_products = LocalProduct::select('local_products.product_id', 'p.name', 'p.description', 'p.price', 'p.category_id', 'local_products.stock')
                ->join('products as p', 'local_products.product_id', '=', 'p.id')->paginate();
        return view('admin.lp.index', compact('products', 'local_products'));
    }
}