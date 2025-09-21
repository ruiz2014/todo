<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Admin\Local;
use App\Models\Admin\Role;
use App\Models\Admin\SuperAdmin\Company;
use App\Models\Admin\Staff\Subscription;
use App\Models\Biller\PaymentMethod;
use App\Models\Biller\PaymentLog;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;
use DB;
use Illuminate\Support\Facades\Auth;
use Session;

class HomeController extends Controller
{
    public function index(Request $request){

        
        $companys = [];
        if(Auth::user()->rol== 1){
            $companys = Company::pluck('name', 'id');
        }
        
        $local = Local::select('local_name')->where('id', $request->session()->get('local_id'))->first();
        $locals = Local::where('company_id', $request->session()->get('company_id'))->pluck('local_name', 'id');
        $rol = Role::select('name')->where('id', $request->session()->get('role'))->first();
        $date = date('Y-m-d');
        $currentMonth = date('n');
        $currentWeek = date("W");
        $currentDay = date("d");

        $receipts = $this->receipts($currentMonth)->pluck('cod');
        $pays = $this->paymentMethods($currentMonth);
        $months = $this->monthlyCare()->pluck('months');
        $monthlyCare = $this->monthlyCare()->pluck('total');

        $attentionDay = Attention::where(DB::raw('CAST(created_at as DATE)'), $date)->count();    //$this->currentDay($currentMonth, $currentDay);
        $totalDay = $this->currentDay($currentMonth, $currentDay);
        $attentionWeek = $this->currentWeek($currentWeek);
        $bestSeller = $this->selling($currentMonth)->pluck('name');
        $bestSellerQty = $this->selling($currentMonth)->pluck('dish');
        $creditoDay = Attention::where('type_payment', 2)->where(DB::raw('CAST(created_at as DATE)'), $date)->sum('total');

        return view('admin.home.index', compact('companys', 'locals', 'local', 'rol', 'receipts', 'pays', 'attentionDay', 'totalDay', 'creditoDay', 'monthlyCare', 'months', 'bestSeller', 'bestSellerQty', 'attentionWeek'));
        // return view('admin.home.index');
    }

    public function paymentMethods($month){

        $methods = PaymentMethod::select(DB::raw('SUM(pl.total) as total'), 'payment_methods.name', 'payment_methods.image')
                    ->leftJoin('payment_logs as pl', 'payment_methods.id', '=', 'pl.method_id')
                    ->join('attentions as at', 'pl.attention_id', '=', 'at.id')
                    ->where(DB::raw('MONTH(at.created_at)'), $month)
                    ->groupBy('payment_methods.name')
                    ->get();
        return $methods;         
    }

    public function monthlyCare(){
        $attentions = Attention::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(id) as attentions'), DB::raw('SUM(total) as total'), DB::raw('ELT(MONTH(created_at), "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre") as months'), 'created_at')
                    ->orderBy('month')            
                    ->groupBy('months')
                    ->get();
        // dd($attentions);            
        return $attentions;                
    }

    public function currentDay($month, $day){
        $attentions = Attention::select(DB::raw('COUNT(id) as attentions'), DB::raw('SUM(total) as total'), DB::raw('DAY(created_at) as day'))
                    ->where('type_payment', 1)            
                    ->where(DB::raw('MONTH(created_at)'), $month)
                    ->where(DB::raw('DAY(created_at)'), $day)
                    ->first();
        return $attentions;            
    }

    public function currentWeek($week){
        $week = Attention::select(DB::raw('SUM(total) as total'), DB::raw('COUNT(id) as attentions'))
                    ->where(DB::raw('WEEK(created_at, 1)'), $week)
                    ->first();
        // dd($week);
        return $week;            
    }

    public function receipts($month){
        $receipts = Attention::select(DB::raw('COUNT(sunat_code) as cod'), 'sunat_code')
                    ->where(DB::raw('MONTH(created_at)'), $month)
                    ->orderBy('sunat_code')
                    ->groupBy('sunat_code')
                    ->get();
                    // dd($receipts);
        return  $receipts;          
    }

    public function selling($month){
        $dishes = TempSale::select(DB::raw('COUNT(temp_sales.product_id) as dish'), 'name')
                ->join('products as pd', 'temp_sales.product_id', '=', 'pd.id')
                // ->where('pd.category_id', 2)
                ->where(DB::raw('MONTH(temp_sales.created_at)'), $month)
                // ->orderBy('dish', 'desc')
                ->orderBy('dish', 'desc')
                ->groupBy('product_id')
                ->get();
                // ->where('')
            // dd($dishes);        
        return $dishes;        
    }

    public function mierda(){
        header('Content-Type: application/json'); // Para responder con JSON

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos_json = file_get_contents('php://input');
            $suscripcion = json_decode($datos_json, true);
            
            if (isset($suscripcion['endpoint'])) {
                $endpoint = $suscripcion['endpoint'];
                $auth = $suscripcion['keys']['auth'] ?? null; // Extrae la clave de autenticación
                $p256dh = $suscripcion['keys']['p256dh'] ?? null; // Extrae la clave P-256DH

                Subscription::updateOrCreate(
                    ['user_id' => request()->session()->get('user_id')], // Atributos para buscar el registro
                    ['company_id' => request()->session()->get('company_id'), 'local_id'=>request()->session()->get('local_id'), 'endpoint'=>$endpoint, 'auth'=>$auth, 'p256dh'=>$p256dh] // Atributos para actualizar/crear
                );
                // Subscription::create(['company_id' => request()->session()->get('company_id'), 'local_id'=>request()->session()->get('local_id'), 'user_id'=>request()->session()->get('user_id'), 'endpoint'=>$endpoint, 'auth'=>$auth, 'p256dh'=>$p256dh]);
                return response()->json(['ok' => 1, 'resp' => $auth]);
            } else {
                // Manejar error si no hay datos de suscripción
                return response()->json(['ok' => 0, 'resp' => 'negativo']);
                exit();
            }

        }
    }
}
