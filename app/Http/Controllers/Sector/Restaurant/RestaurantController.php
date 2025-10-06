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
use App\Models\Restaurant\Room;
use App\Models\Restaurant\Table;

// use App\Traits\Receipts\BillTrait;
// use App\Traits\Receipts\TicketTrait;
use DB;
use Session;

class RestaurantController extends Controller
{
    public function index(){

        // $group = 1;
        // $dishes = $this->getProducts(2, $group); //Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where('product_type', 1)->pluck('name', 'id');
        // $drinks = $this->getProducts(3, $group); //Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where('product_type', 2)->pluck('name', 'id');
        // $fittings = $this->getProducts(4, $group); //Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where('product_type', 3)->pluck('name', 'id');
        // $others = $this->getProducts(5, $group); //Product::select(DB::raw("CONCAT(name,' ',price) AS name"),'id')->where('product_type', 4)->pluck('name', 'id');
        // $rooms = Room::select('id', 'name')->get();
        // $tables = Table::select('id', 'identifier', 'room_id')->get();
        
        // return view('hall', compact('dishes', 'drinks', 'fittings', 'others', 'tables', 'rooms'));

        $rooms = Room::select('id', 'name')->get();
        $tables = Table::select('id', 'identifier', 'room_id')->get();
        
        return view('sectorr.restaurant.index', compact('rooms', 'tables'));
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
}