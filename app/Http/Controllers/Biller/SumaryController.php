<?php 

namespace App\Http\Controllers\Biller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Biller\Attention;
use App\Models\Biller\Sumary as Resume;

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
use Carbon\Carbon;
use DateTime;
use DOMDocument;

class SumaryController extends Controller
{
    use SummaryTrait;
    public function index($fecha=null){
        //
        // $request->session()->forget('empleado');
        $documentos=[];

        if($fecha!=null){
            $documentos = Attention::where('sunat_code','03')->where(DB::raw("CAST(created_at as date)"), $fecha)->get();
            // dd('paso');
        }

        // $datos = DB::table('Venta')
        //     ->orderBy('IdVent', 'desc')
        //     ->get();
        // Log::info($datos);

        return view('biller.sumary.index', compact('documentos', 'fecha')); 
    }

    public function search(Request $request){

        $validated = $request->validate([
            "birthday" => 'required|date',
           
        ]);

        $fecha = $request->input('birthday');
        $date = Carbon::parse($fecha)->format('Y-m-d');
       
        // $fecha = $request->input('birthday');
        $documentos = Attention::where('sunat_code','03')->where(DB::raw("CAST(created_at as date)"), $date)->get();
        // dd($documentos);
        if($documentos->first()){
            return redirect()->route('sumary.index', ['fecha' => $date]);
        }

        return redirect()->route('sumary.index');

    }

    public function sumary(Request $request){
        
        $validated = $request->validate([
            "fecha" => 'required|date',  
        ]);

        $fecha = $request->input('fecha');
        $items = [];

        $codigo ='';
        $mensaje = '';
        $date = $request->input('fecha');

        $documents = Attention::where('sunat_code','03')->where(DB::raw("CAST(created_at as date)"), $fecha)->get();
        
        $respo = $this->setResume($documents, $date);
        
        return redirect()->route('sumary.index')->with($respo['alert'], $respo['message']);

        // dd($result);
        // // dd($see, $sum, $correlativo, $result, $res, $cdr);
        // return redirect()->route('resumen.index')->with($alert, $cdr->getDescription());
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