<?php 
namespace App\Traits;

use App\Models\User;

use Greenter\Data\DocumentGeneratorInterface;
use Greenter\Model\DocumentInterface;

use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;

use DOMDocument;

trait BillingConfigurationTrait {

    public function config(){
        $see = new See();

        $see->setCertificate(file_get_contents(app_path().'/Certificates/certificate.pem'));
        // $see->setCertificate(file_get_contents(app_path().'\Certificates\certificate.pem'));
        // $see->setService(SunatEndpoints::FE_BETA);
        // $see->setClaveSOL('20000000001', 'MODDATOS', 'moddatos');
        $see->setClaveSOL('20608894447', '76063725', 'Claudia99');
        
        $user = 1; //User::find(auth()->user()->id); ESTO VALE LA PENA .......

        $see->setService(SunatEndpoints::FE_BETA);
        // dd($see);
        return $see;
    }

    public function customerData(){
        // Cliente
        $client = (new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000001')
        ->setRznSocial('EMPRESA X');

        return $client;
    }

    public function companyData(){
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
        ->setRuc('20608894447')
        ->setRazonSocial('GREEN SAC')
        ->setNombreComercial('GREEN')
        ->setAddress($address);

        return $company;
    }
}