<?php

namespace App\Exports\Report;

// use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Admin\Product;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;
use DB;
// use App\Models\Biller\PaymentLog;
// use App\Models\Admin\LocalProduct;



use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SalesExport implements FromView, WithTitle, ShouldAutoSize
{

    protected $start, $end;
    public function __construct($start, $end){
        $this->start = $start;
        $this->end = $end;
    }

    public function view(): View
    {
        return view('exports.report.sales', [
            'voucher'=>'Ventas',
            'start'=> $this->start, 
            'end'=> $this->end,
            'sales' => TempSale::all()
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
