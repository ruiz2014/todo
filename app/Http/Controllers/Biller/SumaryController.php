<?php 

namespace App\Http\Controllers\Biller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Biller\Attention;
use App\Models\Biller\Sumary as Resume;
use App\Models\Biller\ReceiptLog;

use App\Traits\Receipts\SummaryTrait;

// use Greenter\Model\Client\Client;
// use Greenter\Model\Company\Company;
// use Greenter\Model\Company\Address;

// use Greenter\Model\Response\SummaryResult;
// use Greenter\Model\Sale\Document;
// use Greenter\Model\Summary\Summary;
// use Greenter\Model\Summary\SummaryDetail;
// use Greenter\Model\Summary\SummaryPerception;
// use Greenter\Ws\Services\SunatEndpoints;

// use App\Http\Controllers\Servicio\config;
use Illuminate\Support\Facades\DB;
use App\Helpers\CompanyHelper;
use Carbon\Carbon;
use DateTime;
use DOMDocument;

class SumaryController extends Controller
{
    use SummaryTrait;
    public function index(Request $request){

        $text = $request->search;
        $select = ['id', 'identifier', 'ticket', 'date_created', 'date_send', 'cdr', 'status'];
        $where = ['company_id'=> ['=', request()->session()->get('company_id')]];
        $orWhere = ['identifier'=>['like', '%'.$text.'%'], 'ticket'=>['like', '%'.$text.'%'], 'cdr'=>['like', '%'.$text.'%'], 'date_created'=>['like', '%'.$text.'%'], 'date_send'=>['like', '%'.$text.'%'], 'status'=>['like', '%'.$text.'%']];
        $join = [];

        $query = Resume::select($select);
        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $summaries = $result->orderBy('id', 'desc')->paginate();

        return view('biller.summary.index', compact('summaries', 'text'))
            ->with('i', ($request->input('page', 1) - 1) * $summaries->perPage());


        return view('biller.summary.index', compact('documentos', 'text')); 
    }

    public function create($fecha=null){
        $documentos=[];

        if($fecha!=null){
            $documentos = Attention::where('sunat_code','03')->whereNull('status')->where(DB::raw("CAST(created_at as date)"), $fecha)->get();
            // dd('paso');
        }
        return view('biller.summary.create', compact('documentos', 'fecha')); 
    }

    public function search(Request $request){

        $validated = $request->validate([
            "birthday" => 'required|date',
           
        ]);

        $fecha = $request->input('birthday');
        $date = Carbon::parse($fecha)->format('Y-m-d');
       
        // $fecha = $request->input('birthday');
        $documentos = Attention::where('sunat_code','03')->whereNull('status')->where(DB::raw("CAST(created_at as date)"), $date)->get();
        // dd($documentos);
        if($documentos->first()){
            return redirect()->route('summary.create', ['fecha' => $date]);
        }

        return redirect()->route('summary.create');

    }

    public function summary(Request $request){
        
        $validated = $request->validate([
            "fecha" => 'required|date',  
        ]);

        $fecha = $request->input('fecha');
        $items = [];

        $codigo ='';
        $mensaje = '';
        $date = $request->input('fecha');

        $documents = Attention::where('sunat_code','03')->whereNull('status')->where(DB::raw("CAST(created_at as date)"), $fecha)->get();
        
        $respo = $this->setResume($documents, $date);
        
        return redirect()->route('summary.index')->with($respo['alert'], $respo['message']);

        // dd($result);
        // // dd($see, $sum, $correlativo, $result, $res, $cdr);
        // return redirect()->route('resumen.index')->with($alert, $cdr->getDescription());
    }

    public function show(Request $request, $ticket){
        
        $codes = Receiptlog::where('ticket', $ticket)->pluck('receipt_code')->toArray();
        // $receipts =  Attention::whereIn('document_code', $codes)->get();
        
        $text = $request->search;
        $select = ['attentions.id', 'attentions.document_code', 'attentions.identifier', 'c.name', 'attentions.total', 'attentions.created_at', 'attentions.cdr', 'attentions.status', 'attentions.completed', 'attentions.dispatched'];
        $where = ['attentions.local_id'=> ['=', request()->session()->get('local_id')], 'sunat_code'=>['=', '03'] ];
        $orWhere = ['attentions.identifier'=>['like', '%'.$text.'%'], 'c.name'=>['like', '%'.$text.'%'], 'attentions.created_at'=>['like', '%'.$text.'%'], 'attentions.total'=>['like', '%'.$text.'%'], 'attentions.status'=>['like', '%'.$text.'%']];
        $join = ['customers as c' => ['attentions.customer_id', '=', 'c.id']];

        $query  = Attention::select($select)->whereIn('document_code', $codes);
        $result = CompanyHelper::searchAll($query, $text, $join, $where, $orWhere);
        $types = $result->paginate(2);


        // $types = Attention::where('sunat_code', $type)->get();

        return view('biller.summary.show', compact('types', 'text'));
        
    
        dd($receipts);
        // $ids = $documents->pluck('id')->toArray();
        // Attention::whereIn('id', $ids)->update(['completed'=>10]);
        
    }

    public function datosCliente(){
        // Cliente
        $client = (new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000001')
        ->setRznSocial('EMPRESA X');

        return $client;
    }

    public function datosCompania(){
        // Emisor
        $address = (new Address())
        ->setUbigueo('150101')
        ->setDepartamento('LIMA')
        ->setProvincia('LIMA')
        ->setDistrito('LIMA')
        ->setUrbanizacion('-')
        ->setDireccion('Av. Villa Nueva 221')
        ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $company = (new Company())
        ->setRuc('20123456789')
        ->setRazonSocial('GREEN SAC')
        ->setNombreComercial('GREEN')
        ->setAddress($address);

        return $company;
    }

    // protected function getCorrelative(){
    //     $number = Resume::orderBy('id', 'Desc')->first();

    //     if($number)
    //     {
    //     	$correlative=(int)$number->id;
    //     	$correlative=str_pad($correlative+1 , 5, "0", STR_PAD_LEFT);

    //         return $correlative;
    //     }

    //     return $correlative = '00001';
    // }


}