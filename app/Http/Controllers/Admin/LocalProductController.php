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
        $products = [];
        $text = $req->search;
        $local_products = LocalProduct::select('local_products.product_id', 'p.name', 'p.description', 'p.price', 'p.category_id', 'c.category_name', 'local_products.stock')
                ->join('products as p', 'local_products.product_id', '=', 'p.id')
                ->join('categories as c', 'p.category_id', '=', 'c.id')
                ->where("p.name", "like", "%$text%")
                ->orWhere("p.description", "like", "%$text%")
                ->orWhere("p.price", "like", "%$text%")
                ->orWhere("c.category_name", "like", "%$text%")
                ->paginate(1);
        return view('admin.lp.index', compact('products', 'local_products', 'text'));
    }
}