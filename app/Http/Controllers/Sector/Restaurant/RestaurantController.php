<?php

namespace App\Http\Controllers\Sector\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use App\Models\Biller\PaymentMethod;
use App\Models\Admin\Product;
use App\Models\Biller\PaymentLog;
use App\Models\Admin\LocalProduct;
use App\Models\Biller\Attention;
use App\Models\Biller\TempRestaurant;
use App\Models\Restaurant\Room;
use App\Models\Restaurant\Table;

// use App\Traits\Receipts\BillTrait;
// use App\Traits\Receipts\TicketTrait;
use DB;
use Session;

class RestaurantController extends Controller
{
    public function index(){

        $group = 3;
        $dishes = $this->getProducts(2, $group); //Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where('product_type', 1)->pluck('name', 'id');
        $drinks = $this->getProducts(3, $group); //Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where('product_type', 2)->pluck('name', 'id');
        $fittings = $this->getProducts(4, $group); //Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where('product_type', 3)->pluck('name', 'id');
        $others = $this->getProducts(5, $group); //Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where('product_type', 4)->pluck('name', 'id');
        // $rooms = Room::select('id', 'name')->get();
        // $tables = Table::select('id', 'identifier', 'room_id')->get();
        
        // return view('hall', compact('dishes', 'drinks', 'fittings', 'others', 'tables', 'rooms'));

        $rooms = Room::select('id', 'name')->get();
        $tables = Table::select('id', 'identifier', 'room_id')->get();
        
        // dd($dishes, $drinks, $fittings, $others, $tables, $rooms);
        return view('sectorr.restaurant.index', compact('dishes', 'drinks', 'fittings', 'others', 'rooms', 'tables'));
    }

    public function check(Request $req){
        // select(DB::raw("cast(created_at AS DATE)"))
        
        $check = TempRestaurant::where('table_id', $req->table)
                    ->where('status', '<=', 3)
                    // ->where('business_id', 1)
                    ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
                    ->value('code');
        if($check){
            $orders = Product::select("products.name", "products.price", "tr.id", "tr.status", "tr.amount")->join("temp_restaurants as tr", "tr.product_id", "=", "products.id")->where('tr.code', $check)->get();
            // $orders = Temp_Order::where('')
            // dd($orders);
            $numberOrders = TempRestaurant::where('code', $check)->count();
            $ordersSent = TempRestaurant::where('code', $check)->where('status', 3)->count();
            $sign = $numberOrders == $ordersSent ? 1 : 0;

            return response()->json(['ok' => 1, 'code'=>$check, 'orders' => $orders, 'sign'=> $sign]);
        }
        // dd($check);
        return response()->json(['ok' => 0, 'error' => "No se Encontro el cliente ...."]);
    }

    public function addOrder(Request $req){

        $code = date('YmdHis');
        $check = TempRestaurant::where('table_id', $req->order['table'])
        ->where('status', '<=', 3)
        // ->where('business_id', 1)
        ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
        ->value('code');
        
        if($check){
            $id_order = TempRestaurant::create(['company_id'=>Session::get('company_id'), 'local_id'=>Session::get('company_id'), 'user_id'=>Session::get('company_id'), 'code'=>$check, 'table_id'=>$req->order['table'], 'product_id'=>$req->order['id'], 'amount'=>$req->order['cantidad'], 'price'=> $req->order['price'], 'status' => 1 ]);
            $orders = Product::select("products.name", "products.price", "tr.id", "tr.status", "tr.amount")->join("temp_restaurants as tr", "tr.product_id", "=", "products.id")->where('tr.code', $check)->get();
            return response()->json(['ok' => 1, 'orders' => $orders]);
        }else{

            $id_order=TempRestaurant::create(['company_id'=>Session::get('company_id'), 'local_id'=>Session::get('company_id'), 'user_id'=>Session::get('company_id'),  'code'=>$code, 'table_id'=>$req->order['table'], 'product_id'=>$req->order['id'], 'amount'=>$req->order['cantidad'], 'price'=> $req->order['price'], 'status' => 1 ]);
            $orders = Product::select("products.name", "products.price", "tr.id", "tr.status", "tr.amount")->join("temp_restaurants as tr", "tr.product_id", "=", "products.id")->where('tr.code', $id_order->code)->get();
            return response()->json(['ok' => 1, 'orders' => $orders]);
        }
    }

    public function modifyAmount(Request $req){
        $orders = TempRestaurant::where('id', $req->id)->update(['amount' => $req->amount]);
        return response()->json(['ok' => 1, 'orders' => $orders]);
    }

    public function deleteOrder(Request $req){
        $order = TempRestaurant::find($req->id);
        $check = $order->code;
        $order->delete();

        $numberOrders = TempRestaurant::where('code', $check)->count();
        $ordersSent = TempRestaurant::where('code', $check)->where('status', 3)->count();
        $sign = $numberOrders == $ordersSent ? 1 : 0;

        $orders = Product::select("products.name", "products.price", "tr.id", "tr.status", "tr.amount")->join("temp_restaurants as tr", "tr.product_id", "=", "products.id")->where('tr.code', $check)->get();
        return response()->json(['ok' => 1, 'orders' => $orders, 'sign'=> $sign]);
    }

    public function sendToKitchen(Request $req){
        $check = TempRestaurant::where('table_id', $req->table)
            ->where('status', 1)
            // ->where('business_id', 1)
            ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
            ->value('code');

        if($check){
            $sendOrders = Product::select("products.name", "products.price", "tr.table_id", "tr.id", "tr.status", "tr.amount", "tr.note", "tr.created_at", "ta.identifier", "ro.name as room")
                                ->join("temp_restaurants as tr", "tr.product_id", "=", "products.id")
                                ->join("tables as ta", "ta.id", "=", "tr.table_id")
                                ->join("rooms as ro", "ro.id", "=", "ta.room_id")
                                ->where('tr.code', $check)
                                ->where('tr.status', 1)
                                ->get();
                                
            TempRestaurant::where('code', $check)->where('status', 1)->update(['status'=> 2]);
            $orders = Product::select("products.name", "products.price", "tr.table_id", "tr.id", "tr.status", "tr.amount", "tr.note")->join("temp_restaurants as tr", "tr.product_id", "=", "products.id")->where('tr.code', $check)->get();

            return response()->json(['ok' => 1, 'orders' => $orders, 'sendOrders'=> $sendOrders]);
        }       

        return response()->json(['ok' => 0, 'orders' => []]);
    }

    public function finalizeOrder(Request $req){
        $check = TempRestaurant::where('table_id', $req->order_table)
            ->where('status', 3)
            // ->where('business_id', 1)
            ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
            ->value('code');
// dd($check);
        if($check){ 
            TempRestaurant::where('code', $check)->update(['status' => 4]);
            return redirect()->route('j2')->with('success', 'La orden fue enviada a caja');
        }  
        
        return redirect()->route('j2')->with('danger', 'No se encontro la orden de la mesa');

    }

    // public function store(RoomRequest $request): RedirectResponse
    // {
    //     // dd($request);
    //     $room = Room::create($request->validated() + ['user_id' => 1]); //auth()->id()

    //     for($i = 0; $i < $request->number_tables; $i++){
    //         Table::create(['identifier' =>$i+1, 'room_id'=>$room->id, 'place_id'=>1]);
    //     }

    //     return Redirect::route('room.index')
    //         ->with('success', 'Category created successfully.');
    // }

    public function checkOccupied(){
        $tables = TempRestaurant::select('table_id', 'code')->where('status', '<=', 3)
                    ->where(DB::raw("CAST(created_at AS DATE)"), '=', DB::raw("DATE(now())"))
                    ->get();

        return response()->json(['ok' => 1, 'tables'=>$tables ]);            
    }

    protected function getProducts($type, $group){
        // $result = Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where('category_id', $type)->where('group', $group)->where('status', 1)->pluck('name', 'id');
        // $result = Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->withTrashed()->where('category_id', $type)->where('group', $group)->pluck('name', 'id');
        $result = Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where("company_id", Session::get('company_id'))->where('category_id', $type)->where('belong', $group)->pluck('name', 'id');
        return $result;
    }
}