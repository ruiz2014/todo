<?php

namespace App\Exports\Report;

// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Admin\Product;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;
use App\Models\Biller\PaymentLog;
use DB;

// use App\Models\Admin\LocalProduct;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesExport implements FromView, WithTitle, ShouldAutoSize
{

    protected $start, $end;
    public function __construct($start, $end, $local_id, $company_id){
        $this->start = $start;
        $this->end = $end;
        $this->local_id = $local_id;
        $this->company_id = $company_id;
    }

    public function view(): View
    {
       // dd($this->company_id, PaymentLog::where('company_id', $this->company_id)->where('method_id', 2)->whereBetween(DB::raw('DATE(created_at)'), [$this->start, $this->end])->sum('total'));
        return view('exports.report.sales', [
            'voucher'=>'Ventas',
            'start'=> $this->start, 
            'end'=> $this->end,
            'sales' => TempSale::where('company_id', $this->company_id)->where('local_id', $this->local_id)->where('status', 2)->whereBetween(DB::raw('DATE(created_at)'), [$this->start, $this->end])->get(),
            'yape' => PaymentLog::where('company_id', $this->company_id)->where('local_id', $this->local_id)->where('method_id', 2)->whereBetween(DB::raw('DATE(created_at)'), [$this->start, $this->end])->sum('total'),
            'contado' => PaymentLog::where('company_id', $this->company_id)->where('local_id', $this->local_id)->where('method_id', 1)->whereBetween(DB::raw('DATE(created_at)'), [$this->start, $this->end])->sum('total'),
            'credito' => PaymentLog::where('company_id', $this->company_id)->where('local_id', $this->local_id)->where('method_id', 3)->whereBetween(DB::raw('DATE(created_at)'), [$this->start, $this->end])->sum('total'),
            // 'yape' =>PaymentLog::where('company_id', $this->company_id)->where('local_id', $this->local_id)->where('method_id', 2)->whereBetween(DB::raw('DATE(created_at)'), [$this->start, $this->end])->sum('total'),
            // 'contado' =>PaymentLog::where('company_id', $this->company_id)->where('local_id', $this->local_id)->where('method_id', 1)->whereBetween(DB::raw('DATE(created_at)'), [$this->start, $this->end])->sum('total'),
            // 'credito' => PaymentLog::where('company_id', $this->company_id)->where('local_id', $this->local_id)->where('method_id', 3)->whereBetween(DB::raw('DATE(created_at)'), [$this->start, $this->end])->sum('total'),
            
            // 'sales' => TempSale::select()
            //             ->join('products as p', 'temp_sales.product_id', '=', 'p.id')
            //             ->join('user as u', 'temp.user_id')
            // 'sales' => Attention::select('customer_id', 'total', 'seller', 'identifier', 'created_at') ->whereBetween(DB::raw('DATE(created_at)'), [$this->start, $this->end])->get()
        ]);
    }

    public function title(): string
    {
        return 'Ventas';
    }
}
