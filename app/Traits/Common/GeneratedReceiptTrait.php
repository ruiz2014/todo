<?php
namespace App\Traits\Common;

use App\Models\Usuario;
use App\Models\Admin\Product;
use App\Models\Biller\Attention;
use App\Models\Biller\PaymentMethod;
use App\Models\Admin\SuperAdmin\Sector;
use App\Models\Admin\SuperAdmin\SetUpCompany;
use App\Models\Admin\SuperAdmin\Company;
// use App\Models\Biller\TempSale;
// use App\Traits\Sunat\SunatTrait;
// use App\Traits\BillingConfigurationTrait;
// use App\Traits\BillingToolsTrait;
use DateTime;
use DB;

// use Greenter\Model\Response\BillResult;
// use Greenter\Model\Sale\Charge;
// use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
// use Greenter\Model\Sale\Invoice;
// use Greenter\Model\Sale\SaleDetail;
// use Greenter\Model\Sale\Legend;

// use Greenter\Ws\Services\SunatEndpoints;
use App\Http\Controllers\NumeroALetras;

use Illuminate\Support\Facades\Log;

trait GeneratedReceiptTrait{

    protected $temps =[];
    protected $methods =[];
    protected $total = 0;
    protected $main_data =[];
    protected $payment_methods = [];
    protected $name_document =null;
    protected $company =null;

    public function generatedReceiptT($code): void{
        $business_type = Company::where('id', request()->session()->get('company_id'))->value('sector_id');

        switch($business_type){
            case 3 :
                $table = 'temp_restaurants';
                break;
            default :
                $table = 'temp_sales';
        }
        $this->has_qr = 
        $this->main_data = Attention::where('document_code', $code)->first();
        $this->company = Company::find(request()->session()->get('company_id'));
        $this->name_document = $this->main_data->voucher->name;
        $this->temps = DB::table($table)
                ->join('products as pr', $table.'.product_id', '=', 'pr.id')
                ->where('code', $this->main_data->document_code)->get();
        $this->methods = PaymentMethod::join('payment_logs as pl', 'payment_methods.id', '=', 'pl.method_id')
                                ->join('attentions as at', 'pl.attention_id', '=', 'at.id')
                                ->where('at.document_code', $this->main_data->document_code)
                                ->select('pl.total', 'payment_methods.name')
                                ->get();

        $this->payment_methods = PaymentMethod::all(); 
        $this->total = $this->main_data->total;
        // dd($payment_methods, $attention, $company, $temps, $methods);      
    }

}