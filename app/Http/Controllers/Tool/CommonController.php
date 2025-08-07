<?php

namespace App\Http\Controllers\Tool;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\URL;

// use App\Http\Requests\CustomerRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Admin\Staff\Customer;
use App\Models\Admin\Product;
use App\Models\Admin\Category;
use App\Models\Admin\Warehouse;
use App\Models\Admin\Local;
use App\Models\Admin\Role;
use DB;
use Session;

class CommonController extends Controller
{
    public function searchCustomer(Request $req){
        $local_id = Session::get('local_id');
        $text = $req->customer;
        
        $result = Customer::where('local_id', $local_id)
                        ->where("name", "like", "%$text%")
                        ->select("id", "name", "document")
                        ->take(20)
                        ->get();

        return response()->json($result);
    }

    // public function registerCustomer(CustomerRequest $req){
    //     // dd("hola");
    //     // We are collecting all data submitting via Ajax
    //     $input = $req->all();
    //     // dd($input, $req->name);
    //     // Sending json response to client
    //     return response()->json([
    //         "status" => true,
    //         "data" => $input
    //     ]);
    // }

    public function createProduct(Request $req)
    {
        
        // $url = Route::getRoutes()->match(request()->create(url()->previousPath()))->getName();
        // $url= $req->session()->pull('url_tool', 'default');
        // dd($req->session()->all());

        // if(!$req->session()->has('aver')){

        // }

        $url= $req->session()->get('url_tool');       // $url= $req->session()->pull('url_tool', 'default');


        // dd($url, $req->session()->all());
        $product = new Product();
        $categories = Category::pluck('category_name', 'id');

        return view('tool.product.create', compact('product', 'categories', 'url'));
    }

    public function storeProduct(ProductRequest $request)
    {
        // dd("Este");
        // Session::get('local_id')
        $local = 3;
        Product::create($request->validated() + ['product_type'=>2, 'group' => $local, 'stock'=>0, 'minimo'=>0]);
        
        // return redirect()->route($request->url)->with('success', 'Product created successfully.');
        return redirect($request->url)->with('success', 'Product created successfully.');
        // return redirect()->action([PostController::class, 'index']);
    }

    public function checkProduct(Request $request){
        if($request->session()->has('wh')){
            $wh_id = request()->session()->get('wh');
            $request->session()->flash('wh', $wh_id);
            $request->session()->keep(['wh']);
            // return response()->json(['ok' => 5, 'checks' => $wh_id]);
        }

        $check = DB::select("SELECT * FROM `products` WHERE similar_word('".$request->search."', description) = 1 or similar_word('".$request->search."', name) = 1");
        // dd($check);
        // return response()->json([
        //     "status" => true,
        //     "data" => $input
        // ]);
        return response()->json(['ok' => 1, 'checks' => $check]);
    }

    public function getRole(Request $request){
        $id = $request->id;
        // return response()->json($id);
        $type = DB::table('establishments')->where('id', $id)->value('type');
        $roles = Role::select('id', 'name as name')->where('establishment_id', $id)->get();
      
        return response()->json(['roles'=> $roles, 'type'=> $type]);
    }

    public function getEstablishment(Request $request){
        $id = $request->id;

        $establishment = '';
        switch($id){
            case 2:
                $establishment = Warehouse::select('id', 'warehouse_name as name', )->where('company_id', request()->session()->get('company_id'))->get();
                break;
            default :
                $establishment = Local::select('id', 'local_name as name', )->where('company_id', request()->session()->get('company_id'))->get();
        }

        return response()->json($establishment);
    }
}
