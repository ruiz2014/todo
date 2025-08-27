<?php

namespace App\Http\Controllers\Biller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Biller\TempSale;
use App\Models\Biller\Attention;
use App\Models\Admin\Product;
use App\Helpers\CompanyHelper;

use App\Exports\Report\SalesExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Traits\ReceiptToolsTrait;
use DB;
class AttentionController extends Controller
{
    use ReceiptToolsTrait;

    public function index(Request $request, $name){
        
        switch($name){
            case 'factura':
                    $code = '01';
                break;
            case 'boleta':
                    $code = '03';
                break;
            case 'ticket':
                    $code = '00';
                break;
            default :
                return  ;          
        }

        $text = $request->search;
        $select = ['attentions.id', 'attentions.document_code', 'attentions.identifier', 'c.name', DB::raw("format(attentions.total, 2) as total"), DB::raw("SUBSTRING(attentions.message, -17) as message"), 'attentions.created_at', 'attentions.cdr', 'attentions.status', 'attentions.completed', 'attentions.dispatched'];
        $where = ['attentions.local_id'=> ['=', request()->session()->get('local_id')], 'sunat_code'=>['=', $code] ];
        $orWhere = ['attentions.identifier'=>['like', '%'.$text.'%'], 'c.name'=>['like', '%'.$text.'%'], 'attentions.created_at'=>['like', '%'.$text.'%'], 'attentions.total'=>['like', '%'.$text.'%'], 'attentions.status'=>['like', '%'.$text.'%']];
        $join = ['customers as c' => ['attentions.customer_id', '=', 'c.id']];

        $query  = Attention::select($select);
        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $types = $result->orderBy('attentions.id', 'desc')->paginate();


        // $types = Attention::where('sunat_code', $type)->get();

        return view('biller.voucher.index', compact('types', 'text', 'name', 'code'));
    }

    public function reportDate(Request $request){
        return view('exports.report.joder');
    }

    public function reportSale(Request $request){
        $local_id = request()->session()->get('local_id');
        $company_id = request()->session()->get('company_id');
        $start_date = $request->get('start');
        $end_date = $request->get('end');
        // return (new SheetMulExport($this->start, $this->end))->download('reporte-general.xlsx');
        return Excel::download(new SalesExport($start_date, $end_date, $local_id, $company_id), 'sales.xlsx');
    }

    // public function downloadXml(Request $request, $id, $type){
    //     $nameReceipt = $this->selectIdXml($type, $id);
    //     if($nameReceipt->identifier){
    //         $path = $this->getStringPath($nameReceipt, $type, 1);

    //         if(!file_exists(public_path()."/sunat_documents/$path")){
    //             return back()->with('info', 'No se encontro el documento a descargar ....');
    //         }
        
    //         return response()->download(public_path()."/sunat_documents/$path");
    //     }else{
    //         return back()->with('info', 'No se encontro el documento a descargar ...');
    //     }

    // }

    // public function downloadCdr(Request $request, $id, $type){
    //     $nameReceipt = $this->selectIdXml($type, $id);
    //     if($nameReceipt->identifier){
    //         switch($type){
    //             case 1:
    //                 break;
    //             case 2 :
    //                 break;
    //         }
    //         // $path = $this->getStringPath($nameReceipt, $type, 1);
    //         // return response()->download(public_path()."/sunat_documents/$path");

    //         $path = $this->getStringPath($nameReceipt, $type, 2); // 2 PARA CDR
    //         if(!file_exists(public_path()."/sunat_documents/$path")){
    //             // return redirect()->route('attentions.index', ['type'=> $type])->with('success', 'No se encontro el documento a descargar');
    //             return back()->with('info', 'No se encontro el documento a descargar ...');
    //         }
    //         return response()->download(public_path()."/sunat_documents/$path");

    //     }else{
    //         return back()->with('info', 'No se encontro el documento a descargar ...');
    //     }
    // }
}

