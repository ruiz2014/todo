<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use PDF;

use App\Models\Biller\PaymentMethod;
use App\Models\Admin\Product;
use App\Models\Biller\Attention;
use App\Models\Biller\TempSale;
use App\Models\Biller\Quote;
use App\Models\Biller\TempQuote;

use App\Models\Admin\SuperAdmin\Company;
use DB;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Http\Controllers\NumeroALetras;


class PdfController extends Controller
{
    protected $temps =[];
    protected $methods =[];
    protected $total = 0;
    protected $main_data =[];
    protected $name_document =null;

    public function generatePDF(Request $request, $id, $type=null)
    {
        $url = explode('/', $request->path());
        $format = $url[0];
        $word = 'ticket';

        if (Str::contains($format, $word)) {
            // $dompdf->setPaper('b7', 'portrait');
            $height = 250;
            $width = 78;
            $paper_format = array(0, 0, ($width/25.4) * 72, ($height/25.4) * 72);
            $view = 'pdf.ticketPDF';
            
        } else {
            $paper_format = 'A4';
            $view = 'pdf.myPDF';
            // $type = "landscape";
        }

        switch($type){
            case 'cotizacion' :
                $sunat = 2; 
                $this->quoted($id);
                break;
            default : 
                $sunat = 1; 
                $this->paid($id);       
        }

        $type = "portrait";

        $company = Company::find(request()->session()->get('company_id'));
        $main_data = $this->main_data;
        $temps = $this->temps;
        $methods = $this->methods;
        $payment_methods = PaymentMethod::all();
        $total = $this->total;
        $name_document = $this->name_document;
        $numberToLetters = $this->NumberToLetters();

        $pdf = PDF::loadView($view, compact('sunat', 'company', 'main_data', 'total', 'payment_methods', 'temps', 'methods', 'name_document', 'numberToLetters'))
                    ->setPaper($paper_format, $type);
       
        return $pdf->download($this->main_data->identifier.'.pdf');
    }

    public function paid($id): void{
        $this->main_data = Attention::where('id', $id)->first();
        $this->name_document = $this->main_data->voucher->name;
        $this->temps = TempSale::where('code', $this->main_data->document_code)->get();
        $this->methods = PaymentMethod::join('payment_logs as pl', 'payment_methods.id', '=', 'pl.method_id')
                                ->join('attentions as at', 'pl.attention_id', '=', 'at.id')
                                ->where('at.document_code', $this->main_data->document_code)
                                ->select('pl.total', 'payment_methods.name')
                                ->get();

        $this->total = TempSale::where('code', $this->main_data->document_code)->sum(DB::raw('amount * price'));
    }

    public function quoted($id): void{
        $this->main_data = Quote::where('company_id', request()->session()->get('company_id'))->where('id', $id)->first();
        $this->name_document = 'Cotizacion';
        $this->total =  $this->main_data->total;//TempQuote::where('company_id', $request->session()->get('company_id'))->where('document_code', $code)->sum(DB::raw('price * amount'));
        $this->temps = TempQuote::where('temp_quotes.code', $this->main_data->document_code)->get();
        $this->methods = [];
    }

    public function NumberToLetters(){
        $convertNumberToLetters = new NumeroALetras();
        $numberToLetters = $convertNumberToLetters->convertir($this->total, 'soles');

        return $numberToLetters;
    }
}

