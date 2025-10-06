<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Admin\Product;
use App\Models\Local;
use App\Models\Admin\WarehouseProduct;
use App\Models\Admin\LocalProduct;
use App\Models\Admin\ProductEntry;
use App\Models\Admin\Kardex;
use App\Models\Admin\TransferProduct;
use App\Models\Admin\WarehouseLog;
use App\Models\Admin\WarehouseMovement;
use App\Models\Admin\Category;
use App\Models\Admin\Notification;
use App\Models\Admin\BuyProduct;
use App\Models\Biller\TempBuy;
use App\Models\Admin\Staff\Establishment;
use App\Models\Admin\SuperAdmin\Company;
use App\Helpers\CompanyHelper;
use DB;

class WarehouseProductController extends Controller
{
    public function whProducts(Request $request, $id){
        $noty = null;
        if(session()->has('notification')) {
            $noty_id=request()->session()->get('notification');
            $noty = Notification::find($noty_id);
        }
        /************************QUITAR CATEGORIAS Y LOCALES Y PRODUCTOS TAMBIEN CREO ********** */
        // $url = $request->path();
        // $request->session()->put('url_tool', $url);
        $wh_id = $id;
        $request->session()->flash('wh', $wh_id);
        $request->session()->keep(['wh']);
        
        $locals = Local::where('company_id', request()->session()->get('company_id'))->pluck('local_name', 'id');
        $categories = Category::pluck('category_name', 'id');
        $products = Product::select(DB::raw("CONCAT_WS(' ', name,' ',description, ' ',price) AS name"),'id')->pluck('name', 'id');
        
        $text = $request->search;

        $select = ['warehouse_products.product_id', 'p.name', 'p.description', 'p.price', 'p.category_id', 'warehouse_products.stock', 'c.category_name'];
        $where = ['warehouse_products.warehouse_id' => ['=', $wh_id] ];
        $orWhere = ['p.name'=>['like', '%'.$text.'%'], 'p.description' => ['like', '%'.$text.'%'], 'p.price' => ['like', '%'.$text.'%'], 'c.category_name' => ['like', '%'.$text.'%'], 'warehouse_products.stock' => ['like', '%'.$text.'%'] ];
        $join = ['products as p' => ['warehouse_products.product_id', '=', 'p.id'], 'categories as c' => ['p.category_id', '=', 'c.id'] ];

        $query  = WarehouseProduct::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $wh_products = $result->paginate();
        
        // $wh_products = WarehouseProduct::select('warehouse_products.warehouse_id', 'warehouse_products.product_id', 'p.name', 'p.description', 'p.price', 'p.category_id', 'warehouse_products.stock')
        //         ->join('products as p', 'warehouse_products.product_id', '=', 'p.id')->paginate();
        // dd($wh_products);
        return view('admin.wh_product.index', compact('products', 'categories', 'wh_products', 'wh_id', 'locals', 'text', 'noty'));
    }

    public function newEntries(Request $request){
        $request->session()->keep(['wh']);
        $wh_id = request()->session()->get('wh');
        // BuyProduct::
        $text = $request->search;
        $select = ['buy_products.id', 'buy_products.code', 'buy_products.total', 'buy_products.document', 'buy_products.created_at', 'buy_products.status', 'e.name as l_type', 'p.name'];
        $where = ['buy_products.company_id'=> ['=', $request->session()->get('company_id')], 'buy_products.location_type'=> ['=', 2], 'buy_products.location_id'=> ['=', $wh_id]];
        $orWhere = ['buy_products.total'=>['like', '%'.$text.'%'], 'buy_products.document' => ['like', '%'.$text.'%'], 'p.name' => ['like', '%'.$text.'%'], 'e.name' => ['like', '%'.$text.'%'], 'buy_products.created_at'=> ['like', '%'.$text.'%']];
        $join = ['providers as p' => ['buy_products.provider_id', '=', 'p.id'], 'establishments as e' => ['buy_products.location_type', '=', 'e.type']];

        $query  = BuyProduct::select($select);

        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $buyProducts = $result->orderBy('buy_products.id', 'desc')->paginate();

        $noty = false;
        
        return view('admin.wh_product.entry', compact('buyProducts', 'text', 'noty', 'wh_id'))
            ->with('i', ($request->input('page', 1) - 1) * $buyProducts->perPage());    
    }

    public function entryAction(Request $request, $code){
        $request->session()->keep(['wh']);
        // $company = Company::find($request->session()->get('company_id'));
        $buyProduct = BuyProduct::where('company_id', $request->session()->get('company_id'))->where('code', $code)->first();
        $total = TempBuy::where('company_id', $request->session()->get('company_id'))->where('code', $code)->sum(DB::raw('stock * cost'));
        $temps = TempBuy::select('p.name', 'temp_buys.cost', 'temp_buys.stock')->join('products as p', 'temp_buys.product_id', '=', 'p.id')->where('code', $code)->get();
        $methods = [];
        // dd($temps, $total);
        return view('admin.wh_product.register', compact('code', 'buyProduct', 'total', 'temps', 'methods'));
    }

    public function register(Request $request, $code){
        
        $request->session()->keep(['wh']);
        $wh_id = request()->session()->get('wh');
        $buys = TempBuy::where('company_id', $request->session()->get('company_id'))->where('code', $code)->get();
        
        foreach($buys as $buy){
            $checkProd = WarehouseProduct::where('company_id', $request->session()->get('company_id'))->where('product_id', $buy->product_id)->where('warehouse_id', $wh_id)->exists();
            
            if($checkProd){
                WarehouseProduct::where('warehouse_id', $wh_id)->where('product_id', $buy->product_id)->increment('stock', $buy->stock);  
            }else{
                WarehouseProduct::create(['user_id'=>$request->session()->get('user_id'), 'company_id' => $request->session()->get('company_id'), 'warehouse_id'=>$wh_id, 'product_id'=>$buy->product_id, 'stock'=>$buy->stock]);
            }

            $whlog=WarehouseLog::create(['company_id' => $request->session()->get('company_id'), 'warehouse_id'=>$wh_id, 'product_id'=>$buy->product_id, 'batch'=>null,'entry'=>$buy->stock, 'output'=>0]); 
            WarehouseMovement::create(['user_id'=>$request->session()->get('user_id'), 'whlog_id'=>$whlog->id, 'movement'=>1, 'amount'=>$buy->stock]); 
            
        }
        BuyProduct::where('company_id', $request->session()->get('company_id'))->where('code', $code)->update(['status'=> 1]);
       return Redirect::route('whp.show', ['id'=>$wh_id])->with('success', 'Se registro correctamente los articulos de la compra ....');
    }

    public function store(Request $request){ //EL PROD.EXISTE Y DEBE INCREMENTAR SU STOCK
        // dd(request()->session()->get('wh'));
        $user_id = $request->session()->get('user_id');
        $wh_id = request()->session()->get('wh');

        $validatedData = $request->validate([
            'product_id' => 'required|numeric',
            'stock' => 'required|numeric|gt:0',
        ]);

        $checkProd = WarehouseProduct::where('product_id', $request->product_id)->where('warehouse_id', $wh_id)->exists();
        if($checkProd){
            WarehouseProduct::where('warehouse_id', $wh_id)->where('product_id', $request->product_id)->increment('stock', $request->stock);
            $whlog=WarehouseLog::create(['warehouse_id'=>$wh_id, 'product_id'=>$request->product_id, 'batch'=>$request->batch,'entry'=>$request->stock, 'output'=>0]); 
            WarehouseMovement::create(['user_id'=>$request->session()->get('user_id'), 'whlog_id'=>$whlog->id, 'movement'=>1, 'amount'=>$request->stock]); 

            return Redirect::route('whp.show', ['id'=>$wh_id])->with('success', 'Product created successfully.');
        }

         WarehouseProduct::create($validatedData + ['user_id' => $user_id, 'warehouse_id'=>$wh_id]);
         $whlog=WarehouseLog::create(['warehouse_id'=>$wh_id, 'product_id'=>$request->product_id, 'batch'=>$request->batch,'entry'=>$request->stock, 'output'=>0]);
         WarehouseMovement::create(['user_id'=>$request->session()->get('user_id'), 'whlog_id'=>$whlog->id, 'movement'=>1, 'amount'=>$request->stock]); 
         return Redirect::route('whp.show', ['id'=>$wh_id])->with('success', 'Product created successfully.');
        // dd($validatedData);
    }

    public function viewHistory(Request $request, $id){
        // dd(request()->session()->get('wh'));
        $request->session()->keep(['wh']);

        if(!session()->has('wh')) {
            return back()->with('danger', 'Hubo algun error vuelva a intentarlo por favor... .');
        }

        $wh_id = request()->session()->get('wh');
        $locals = Local::pluck('local_name', 'id');
       
        $wh_products = WarehouseLog::select('warehouse_logs.id', 'warehouse_logs.product_id', 'p.name', 'p.description', 'p.price', DB::raw("DATE_FORMAT(warehouse_logs.created_at, '%d-%m-%Y') as date"), 'warehouse_logs.batch', 'warehouse_logs.entry')
                ->join('products as p', 'warehouse_logs.product_id', '=', 'p.id')
                ->where('warehouse_logs.product_id', $id)
                ->where('warehouse_id', $wh_id) 
                ->paginate();
        
        return view('admin.wh_product.history', compact('wh_products', 'locals'));        
        // dd($wh_products);        
    }

    public function tempAction(Request $request){

        if(!session()->has('wh')) {
            return back()->with('danger', 'No se encontro el Almacen... vueva a intentarlo.');
        }
        // $request->merge(['user_id'=>$request->session()->get('user_id')]);
        $wh_id = request()->session()->get('wh');
        
        $validatedData = $request->validate([
            // 'user_id'=> 'required',
            'name' => 'required|string',
            'category_id'=> 'required|numeric',
            'stock' => 'required|numeric|gt:0',
        ]);
        // dd($validatedData);
        // dd($request->session()->all(), $wh_id);
        
        $prod = Product::create($validatedData + ['user_id'=>$request->session()->get('user_id'), 'price'=>0, 'approved'=>2, 'product_type'=>1, 'stock'=>0, 'minimo'=>0]);
        WarehouseProduct::create(['user_id'=>$request->session()->get('user_id'), 'warehouse_id'=>$wh_id, 'product_id'=>$prod->id, 'stock'=>$request->stock]);
        WarehouseLog::create(['warehouse_id'=>$wh_id, 'product_id'=>$prod->id, 'batch'=>$request->batch, 'entry'=>$request->stock, 'output'=>0]);            

        $role = request()->session()->get('role');
        $title = 'Se creo un Producto desde almacen';
        $notes = 'Se Creo el producto '.$prod->name.' con el stock '.$request->stock.' parab su aprobacion ..';
        // dd($request->session()->get('company_id'), $request->session()->get('user_id'), $role, $title);
        $notify_id = Notification::create(['company_id'=>$request->session()->get('company_id'), 'user_id'=>$request->session()->get('user_id'), 'local_id'=>0, 'from_role_id'=>$role, 'to_role_id'=>1, 'title'=>$title, 'notes'=>$notes]);
        $request->session()->flash('notification', $notify_id->id);
        
        return Redirect::route('whp.show', ['id'=>$wh_id])
            ->with('success', 'Product created successfully.');
    }

    // public function uploadProducts(Request $req){
    //     // $local = Session::get('local_id');
    //     // $user_id=Session::get('user_id');
    //     $local = 1;
    //     $user_id = 1;
    //     $validated = $req->validate([
    //         'idem' => 'required|numeric',
    //         'amount' => 'required|numeric',
    //         'cost' => 'required|numeric', //QUITAR COSTO
    //     ]);
    //     // dd($lp, $validated, $local, $req->amount, $req->idem);
    //     WarehouseProduct::where('warehouse_id', $local)->where('product_id', $req->idem)->increment('stock', $req->amount);
    //     // ProductEntry::create(['user_id'=>$user_id, 'product_id'=>$req->idem, 'amount'=>$req->amount, 'cost'=>$req->cost]);
    //     // Product::where('id', $req->idem)->increment('stock', $req->amount);
     
    //     // $check = Kardex::where('local', $local)
    //     //     ->where('product_id', $req->idem)
    //     //     ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
    //     //     ->value('id');

    //     // if($check){
    //     //     Kardex::where('id', $check)->increment('entry', $req->amount);
    //     // }
    //     // else{
    //     //     Kardex::create(['local'=>$local, 'product_id'=>$req->idem, 'entry'=>$req->amount, 'output'=>0]);
    //     // } 
        
    //     return redirect()->route('lp.index')->with('success', 'Se ingreso nuevo stock');
    // }

    // public function uploadStock(Request $request){
        
    //     // 'price' => 'required|numeric|gt:0', 
    //     //VERIFICAR SI EN EL MISMO LOCAL NO EXISTA EL PRODUCTO CREADO
    //     $validatedData = $request->validate([
    //         'product_id' => 'required|numeric',
    //         'warehouse_id' => 'required|numeric',
    //         'amount' => 'required|numeric|gt:0', 
    //     ]);
    //     // dd($request, $validatedData);

    //     WarehouseProduct::where('warehouse_id', $request->warehouse_id)
    //                     ->where('product_id', $request->product_id)
    //                     ->increment('stock', $request->amount);

    //     WarehouseLog::create(['warehouse_id'=>$request->warehouse_id, 'product_id'=>$request->product_id, 'entry'=>$request->amount, 'output'=>0]);              
    //     // $check = WarehouseLog::where('local_id', $request->local_id)
    //     // ->where('product_id', $request->product_id)
    //     // ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
    //     // ->value('id');
            
    //     // if($check){
    //     //     WarehouseLog::where('id', $check)->increment('entry', $request->amount);
    //     // }
    //     // else{
    //     //     WarehouseLog::create(['local_id'=>$request->local_id, 'product_id'=>$request->product_id, 'entry'=>$request->amount, 'output'=>0]);
    //     // } 


    //     return Redirect::route('whp.show', ['id'=>$request->warehouse_id])
    //         ->with('success', 'Product created successfully.');
    // }

    public function transferStock(Request $request){
        /*VALIDAR SI EXISTE EL PRODUCTO EN EL LOCAL A ENVIAR ..... SI NO ES ASI DEBE AVISAr que no existe el producto */
            $request->session()->keep(['wh']);
            if(!session()->has('wh')) {
                return back()->with('danger', 'Hubo algun error vuelva a intentarlo por favor... .');
            }

            $validated = $request->validate([
                'local_id' => 'required|numeric',
                'row' => 'required|numeric',
                // 'warehouse_id' => 'required|numeric',
                'amount' => 'required|numeric|gt:0', 
            ]);

            try{

                $product = WarehouseLog::find($request->row);
                $detail_prod = Product::where('id', $product->product_id)->first();

                if($request->amount > $product->entry){
                    return back()->with('danger', 'Producto no tiene stock suficiente');
                }
                
                WarehouseProduct::where('warehouse_id', request()->session()->get('wh'))
                            ->where('product_id', $product->product_id)
                            ->decrement('stock', $request->amount);

                $product->decrement('entry', $request->amount);
                $product->increment('output', $request->amount);

                WarehouseMovement::create(['user_id'=>$request->session()->get('user_id'), 'whlog_id'=>$request->row, 'movement'=>2, 'amount'=>$request->amount]);                 
                $detail_prod->increment('stock', $request->amount); //AUMENTA EL STOCK GENERAL DEL PRODUCTO
                
                //PONER STATUS APROVADO PARA AVISAR SI EL PRODUCTO SE PUEDE USAR
                $lp=LocalProduct::where('local_id', $request->local_id)->where('product_id', $product->product_id)->first();
                if($lp){
                    $lp->increment('stock', $request->amount);
                }
                else{
                    LocalProduct::create(['user_id'=>$request->session()->get('user_id'), 'local_id'=>$request->local_id, 'product_id'=>$product->product_id, 'stock'=>$request->amount, 'approved'=>0]);
                }
                
                ProductEntry::create(['company_id'=>$request->session()->get('company_id'), 'user_id'=>$request->session()->get('user_id'), 'local_id'=>$request->local_id, 'product_id'=>$product->product_id, 'amount'=>$request->amount, 'cost'=>0]);
                
                TransferProduct::create(['company_id'=>$request->session()->get('company_id'), 'local_id' => $request->local_id, 'warehouse_id' =>request()->session()->get('wh'), 'user_id'=>$request->session()->get('user_id'), 'product_id'=>$product->product_id, 'output'=>$request->amount]);
                
                $karde = Kardex::where('local_id', $request->local_id)
                    ->where('product_id', $product->product_id)
                    ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
                    ->first();
        
                if($karde){
                    $karde->increment('entry', $request->amount);
                }
                else{
                    Kardex::create(['company_id'=>$request->session()->get('company_id'), 'local_id'=>$request->local_id, 'product_id'=>$product->product_id, 'entry'=>$request->amount, 'output'=>0]);
                } 

                // dd($request, $request->session()->all(), request()->session()->get('role')); 
                $role = request()->session()->get('role');
                $title = 'Se envio un Producto desde almacen';
                $notes = 'Se envio el producto '.$detail_prod->name.' con el stock '.$request->amount.' para el local ...';
                $notify_id = Notification::create(['company_id'=>$request->session()->get('company_id'), 'user_id'=>$request->session()->get('user_id'), 'local_id'=>$request->local_id, 'from_role_id'=>$role, 'to_role_id'=>1, 'title'=>$title, 'notes'=>$notes]);
                $request->session()->flash('notification', $notify_id->id);
                return Redirect::route('whp.show', ['id'=>request()->session()->get('wh')])
                ->with('success', 'Product created successfully.');

            }catch (\Throwable $th) {
                // dd(get_class_methods($th));
                Log::info("Line No : ".__LINE__." : File Path : ".__FILE__." message ".$th->getMessage()." linea : ".$th->getLine()." codigo :".$th->getCode());
                Log::error('Velocity CartController: ' . $th->getMessage(), ["hola"=>"hola"]);
                // dd("error en base ". $th->getMessage());//throw $th;
                
                return back()->with('danger', 'Hubo error al generar este procedimiento');
            }     
    }

    // public function checkProduct(Request $request){
    //     $check = LocalProduct::where('local', $request->local)
    //                 ->where('product_id', $request->product_id)
    //                 ->exists();
        
    //     return response()->json(['ok' => $check]);            
    // }

    // public function distancia(Request $request){
    //     $cont1 = 0;
    //     $palabra = 'inka cola';
    //     $subs_p = explode(' ', $palabra);

    //     $cadena = 'el sabor inca kola nacional';
    //     $subst = explode(' ', $cadena);

    //     for($i=0; $i < count($subst); $i++){
    //         // if(isset($subs_p[$i])){
    //             for($j=0; $j< count($subs_p); $j++){
    //                 $guar = similar_text($subs_p[$j], $subst[$i]);
    //                 echo $subs_p[$j].'@'.$subst[$i].' - ';
    //                 if($guar > 2)
    //                     $cont1 = $cont1 + 1;
    //             }
    //         // } 
    //     }
    //     dd($cont1);
    // }
}