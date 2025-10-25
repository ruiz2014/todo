<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <title>Document</title>
</head>
<body>
    <div style="margin:0px; padding:10px 5px">
			<style>
				@page { sheet-size: 70mm 250mm; margin:0px; padding:0px}

				h1.bigsection {
					page-break-before: always;
					page: bigger;
				}

			</style>
				<table class="tabla1">
				<tr>
					<td align="center"><img style="height:70" src="https://d500.epimg.net/cincodias/imagenes/2015/05/08/pyme/1431098283_691735_1431098420_noticia_normal.jpg"></td>
				</tr>
				<tr>
					<td align="center" style="font-size:13px">{{ $company->company_name }}</td>
				</tr>
				<tr>
					<td align="center" style="font-size:12px">RUC: {{ $company->document }}</td>
				</tr>
				<tr>
					<td align="center" style="font-size:12px">{{ $company->address }}</td>
				</tr>
				<tr>
					<td align="center" style="font-size:12px">Teléfono: 044-724028 Cel: 970076709</td>
				</tr>
				<tr>
					<td align="center">==============================</td>
				</tr>

				<tr>
					<td class="negrita" align="center" style="font-size:13px">{{ $name_document }}</td>
				</tr>
				<tr>
					<td class="negrita" align="center" style="font-size:13px">{{ $main_data->identifier }}</td>
				</tr>
				<tr>
					<td align="center">==============================</td>
				</tr>
			</table>

			<table style="border 1px solid red;font-size:11px"  class="tabla1">
			<tr>
                <td style="font-size:11px" width="20%">Ruc/Dni:</td>
                <td style="font-size:11px" width="30%"> {{ $main_data->customer->document }}</td>
            </tr>
            <tr>
                <td style="font-size:11px" width="20%">Nombre/Razón:</td>
                <td style="font-size:10px" width="30%">{{ $main_data->customer->name}}</td>
			</tr>
			<tr>
                <td style="font-size:11px" width="20%">Dirección:</td>
                <td style="font-size:10px" width="30%">{{ $main_data->customer->address }}</td>
            </tr>
            <tr>
                <td style="font-size:11px" width="20%">Fecha / Hora:</td>
                <td style="font-size:11px" width="30%">{{ date('d-m-Y', strtotime($main_data->created_at))  }}</td>
            </tr>
		</table>
		<table  class="tabla1">
            <tr>
                <td align="center">==============================</td>
            </tr>
        </table>
        <table  width="100%" class="tabla1">
            <thead>
                <tr>
                    <th  style="font-size:11px" align="center">Descripción</th>
                    <th  style="font-size:11px" align="center">Cant</th>
                    <th  style="font-size:11px" align="center">Precio</th>
                    <th  style="font-size:11px" align="center">Importe</th>
                </tr>
			</thead>
			<tbody>
			@foreach($temps as $item)
				<tr>
						<td style="font-size:9px;text-align:center;padding:5px 0px">{{ $item->name }}</td>
						<td style="font-size:10px;text-align:center;">{{ $item->amount }}</td>
						<td style="font-size:10px;text-align:center;">{{ $item->price }}</td>
						<td style="font-size:10px;text-align:center;">{{ $item->price * $item->amount }}</td>
					</tr>
				
			@endforeach
            <?php
		$vendedor = 'Jose Bolas';
		$monedaImp='PEN'; ?>	

    <?php $subTotal=$total/1.18;
		$igv=$total-$subTotal;
	?>

			</tbody>
		</table>
		<table  class="tabla1">
            <tr>
                <td align="center">==============================</td>
            </tr>
        </table>
		<table width="100%" class="tabla1 borderTop borderBottom">
            <thead>
                <tr>
                    <td style="font-size:10px;text-align:center;" width="25%" align="center">IMP. BRUTO</td>
                    <td style="font-size:10px;text-align:center;" width="25%" align="center">IGV(18%)</td>
                    <td style="font-size:10px;text-align:center;" width="25%" align="center">TOTALES</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size:10px;text-align:center;" width="25%" align="center">{{ number_format((float)$subTotal,2,'.', ' ') }}</td>
                    <td style="font-size:10px;text-align:center;" width="25%" align="center">{{ number_format((float)$igv,2,'.', ' ') }}</td>
                    <td style="font-size:10px;text-align:center;" width="25%" align="center">{{ number_format((float)$total,2,'.', '') }}</td>
                </tr>
            </tbody>
		</table>
		<table  class="tabla1">
            <tr>
                <td align="center">==============================</td>
            </tr>
        </table>
		<table  class="tabla1">
            <tr>
                <td style="font-size:12px;" width="15%">Son:</td>
                <td style="font-size:10px;" width="45%">{{ $numberToLetters }} </td>
            </tr>
		</table>

        <table  class="tabla1">
            <tr>
                <td align="center" class="negrita">---------------------------------------------------</td>
            </tr>
        </table>
        <table  class="tabla1">
            <tr>
                <td style="font-size:11px;text-align:left;" width="15%">Vendedor:</td>
                <td style="font-size:11px;text-align:left;" width="25%">juan bolas</td>
            </tr>
            <tr>
                <td style="font-size:12px;text-align:left;" width="15%">Observaciones:</td>
                <td style="font-size:12px;text-align:center;" width="25%"></td>
            </tr>
        </table>
        <table  class="tabla1">
            <tr>
                <td align="center" class="negrita">---------------------------------------------------</td>
            </tr>
        </table>
        <table  class="tabla1">
            <tr>
                <td style="font-size:10px;text-align:center;" align="center"><span class="negrita">{{ $main_data->hash }}</span></td>
            </tr>
        </table>
        <table  class="tabla1">
            <tr>
                <td align="center" class="negrita">---------------------------------------------------</td>
            </tr>
		</table>

		<table  class="tabla3" style="margin:0 auto;">
        @if($sunat == 1)    
            <tr>
                <td align="center">
                @if(!$main_data->sunat_code == '00')	    
                    <!-- <img width="80" src="'. base_url().'img/qr/'.$factura->id_factura.'.png" > -->
                     <img width="80" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(120)->generate($main_data->resume)) !!}" alt="">
                @endif
                </td>
            </tr>
        @endif    
            <tr>
                <td align="center">

                    <div style="font-size:10px;text-align:center;">
                        Representación Impresa de <b>{{ $name_document }}</b>
                    </div>
                    <div style="font-size:10px;text-align:center;">
                        Consulte Documento en www.naferlite.com.pe
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>