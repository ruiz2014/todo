<?php

namespace App\Exports\Product;

use App\Models\Admin\Product;
// use App\Models\Biller\Attention;
// use App\Models\Biller\TempSale;
// use App\Models\Biller\PaymentLog;
use DB;


use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductsExport implements FromView, WithTitle, ShouldAutoSize
{

    protected $company_id;
    public function __construct($company_id){
        $this->company_id = $company_id;
    }
    
    public function view(): View
    {
        $products = Product::select('id', 'name', 'description')->where('company_id', $this->company_id)->get();
        return view('exports.product.product', [
            'products' => $products
        ]);
        //
    }

    public function title(): string
    {
        return 'Productos';
    }
}
