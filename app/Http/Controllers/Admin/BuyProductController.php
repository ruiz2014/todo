<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\BuyProduct;
use App\Models\Admin\Product;
use App\Models\Admin\PurchaseLog;
use App\Models\Admin\Staff\Provider;
use App\Models\Biller\TempBuy;
use App\Models\Admin\Staff\Establishment;
use App\Models\Admin\Notification;
use App\Models\Admin\SuperAdmin\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BuyProductRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Helpers\CompanyHelper;
use DB;

class BuyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $noty = null;
        if(session()->has('notification')) {
            $noty_id=request()->session()->get('notification');
            $noty = Notification::find($noty_id);
        }
        
        $text = $request->search;
        $select = ['buy_products.id', 'buy_products.code', 'buy_products.total', 'buy_products.document', 'buy_products.created_at', 'e.name as l_type', 'p.name'];
        $where = ['buy_products.company_id'=> ['=', $request->session()->get('company_id')], 'e.company_id'=>['=', $request->session()->get('company_id')]];
        $orWhere = ['buy_products.total'=>['like', '%'.$text.'%'], 'buy_products.document' => ['like', '%'.$text.'%'], 'p.name' => ['like', '%'.$text.'%'], 'e.name' => ['like', '%'.$text.'%'], 'buy_products.created_at'=> ['like', '%'.$text.'%']];
        $join = ['providers as p' => ['buy_products.provider_id', '=', 'p.id'], 'establishments as e' => ['buy_products.location_type', '=', 'e.type']];

        $query  = BuyProduct::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $buyProducts = $result->orderBy('buy_products.id', 'desc')->paginate();
        $algo = true;
        return view('buy-product.index', compact('buyProducts', 'text', 'noty', 'algo'))
            ->with('i', ($request->input('page', 1) - 1) * $buyProducts->perPage());    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $code = date('YmdHis');
        $payment_methods = []; //PaymentMethod::where('company_id', Session::get('company_id'))->get();
        $providers = Provider::where('company_id', request()->session()->get('company_id'))->pluck('name', 'id');
        $buyProduct = new BuyProduct();
        $products = Product::select(DB::raw("CONCAT_WS(' ', products.name,' ',products.description) AS name"), 'products.id')->where('company_id', request()->session()->get('company_id'))->pluck('name', 'id');
        $establishments  = Establishment::where('company_id', request()->session()->get('company_id'))->get(); //pluck('name', 'id');
        
        return view('buy-product.create', compact('code', 'products', 'providers', 'buyProduct', 'establishments', 'payment_methods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
    // public function store(Request $request): RedirectResponse
    public function store(BuyProductRequest $request): RedirectResponse
    {
        try{
            $alert = 'danger';
            $message = 'Hubo un problema en el registro de la compra';
            
            $total = TempBuy::select(DB::raw('SUM(cost * stock) as total'))->where('company_id', $request->session()->get('company_id'))->where('code', $request->code)->value('total');

            if (TempBuy::where('code', $request->code)->exists()) {
                $id = BuyProduct::create($request->validated() + ['company_id'=>request()->session()->get('company_id'), 'user_id'=>request()->session()->get('company_id'), 'total' => $total]);
                // Purchaselog::create(['company_id'=>request()->session()->get('company_id'), 'user_id' => $request->session()->get('user_id'), 'buy_id'=>$id->id, 'product_id'=>$request->location_id, 'stock' => $request->stock, 'location_type'=>$request->location_type, 'location_id'=>$request->location_id]);

                $role = request()->session()->get('role');
                $title = 'Se Realizo una compra para el Almacen';
                $notes = 'Se Hizo una compra con Articulos que seran llevados al Almacen Almacen pri';
                $notify_id = Notification::create(['company_id'=>$request->session()->get('company_id'), 'user_id'=>$request->session()->get('user_id'), 'local_id'=>0, 'from_role_id'=>$role, 'to_role_id'=>1, 'title'=>$title, 'notes'=>$notes]);
                $request->session()->flash('notification', $notify_id->id);
                
                $alert = 'success';
                $message = 'Su compra se registro con exito';
            }

            return Redirect::route('buy-products.index')->with($alert, $message);
            
        }catch (\Throwable $th) {

            Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
            Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                
            return back()->with('danger', 'Hubo error al generar este procedimiento');
        }     
    }

    public function generatedReceipt(Request $request, $code){
        
        $company = Company::find($request->session()->get('company_id'));
        $buyProduct = BuyProduct::where('company_id', $request->session()->get('company_id'))->where('code', $code)->first();
        $total = TempBuy::where('company_id', $request->session()->get('company_id'))->where('code', $code)->sum(DB::raw('stock * cost'));
        $temps = TempBuy::select('p.name', 'temp_buys.cost', 'temp_buys.stock')->join('products as p', 'temp_buys.product_id', '=', 'p.id')->where('temp_buys.code', $code)->get();
        $methods = [];
        // dd($temps, $total);
        return view('buy-product.generated_receipt', compact('company', 'buyProduct', 'total', 'temps', 'methods'));


    }

    public function addOrder(Request $req){

        // $code = date('YmdHis');
        $check = TempBuy::where('company_id', $req->session()->get('company_id'))->where('code', $req->order['code'])
        ->where('status', '<', 2)
        ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
        ->value('code');
        
        if($check){
            $id_order=TempBuy::create(['company_id'=>request()->session()->get('company_id'), 'user_id'=>request()->session()->get('user_id'), 'code'=>$req->order['code'], 'product_id'=>$req->order['id'], 'stock'=>$req->order['amount'], 'cost'=> $req->order['price'], 'status' => 1]);
            $orders = Product::select("products.name", "ts.cost", "ts.id", "ts.status", "ts.stock")->join("temp_buys as ts", "ts.product_id", "=", "products.id")->where('ts.code', $check)->get();
            return response()->json(['ok' => 1, 'orders' => $orders]);
        }else{
            // dd($check, 'hace esto');
            $id_order=TempBuy::create(['company_id'=>request()->session()->get('company_id'), 'user_id'=>request()->session()->get('user_id'), 'code'=>$req->order['code'], 'product_id'=>$req->order['id'], 'stock'=>$req->order['amount'], 'cost'=> $req->order['price'], 'status' => 1 ]);
            $orders = Product::select("products.name", "ts.cost", "ts.id", "ts.status", "ts.stock")->join("temp_buys as ts", "ts.product_id", "=", "products.id")->where('ts.code', $id_order->code)->get();
            return response()->json(['ok' => 1, 'orders' => $orders]);
        }

    }

    public function modifyAmount(Request $req){
        if($req->amount < 1){
            return response()->json(['ok' => 0, 'orders' => []]);
        }
        $orders = TempBuy::where('company_id', $req->session()->get('company_id'))->where('id', $req->id)->update(['stock' => $req->amount]);
        return response()->json(['ok' => 1, 'orders' => $orders]);
    }

    public function deleteOrder(Request $req){
        $order = TempBuy::find($req->id);
        // dd($order);
        $check = $order->code;
        $order->delete();
// return response()->json(['ok' => 1, 'orders' => $order, 'sign'=> $check]);
        $numberOrders = TempBuy::where('company_id', $req->session()->get('company_id'))->where('code', $check)->count();
        $ordersSent = TempBuy::where('company_id', $req->session()->get('company_id'))->where('code', $check)->where('status', 3)->count();
        $sign = $numberOrders == $ordersSent ? 1 : 0;

        $orders = Product::select("products.name", "ts.cost", "ts.id", "ts.status", "ts.stock")->join("temp_buys as ts", "ts.product_id", "=", "products.id")->where('ts.code', $check)->get();
        return response()->json(['ok' => 1, 'orders' => $orders, 'sign'=> $sign]);
    }
}
