<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
       body{
		   /* font-family:'sans-serif'; */
		   /* font-family:monospace; */
		   font-family: "Segoe IU", sans-serif;
		   
	   }
	   .joder{
		   margin-bottom:5px;
	   }
	   .fila2, .fila3{
			font-size:11px;
		   margin-bottom:4px;
		   color:#474a51;
	   }
	   /* .fila3{
			width:100px;
			border:1px solid red;
	   } */
	   .border{
		   border-radius:5px;
		   /* border: 1px solid #040e04; */
		   border: 1px solid #aaa3a6;
		}

		.details tr:nth-child(even) {
			color: #000;
			background-color: #dedede;
			/* border-bottom: 1px solid #F00; */
		}
		.no_borde{
			background-color: #FFF;
		}

	   /* p {
         color: red;
       } */
    </style>
</head>
<body>
	<header>
		<table style="width:100%;margin-bottom:6px;">
			<tr style="text-align:center;">
				<td style="width:50%;">
					<img style="height:90px;" src="https://d500.epimg.net/cincodias/imagenes/2015/05/08/pyme/1431098283_691735_1431098420_noticia_normal.jpg" alt="">
				</td>
				<td class="border" style="width:50%;background:#dedede;">
					<div class="joder"><b>R.U.C : {{ $company->document }}</b></div>
					<div class="joder"><b>{{ $name_document }}</b></div>
					<div class="joder"><b>{{ $main_data->identifier }}</b></div>
				</td>
			</tr>
		</table>
		
		<table style="width:100%;margin-bottom:15px;">
			<tr style="">
				<td style="width:50%;">
					<div style="font-size:13px;margin-bottom:5px;"><b>{{ $company->company_name }}</b></div>
					<div class="fila2"><b>{{ $company->address }}</b></div>
					<div class="fila2"><b>Telf: 044-724028 Cel: 970076709</b></div>
					<div class="fila2"><b>www.naferlite.com.pe</b></div>
					<div class="fila2"><b>ventas1@naferlite.com.pe</b></div>
				</td>
				<td class="border" style="padding:10px;">
					<div class="fila2"><b>Fecha Emision : &nbsp; &nbsp; &nbsp;{{ date('d-m-Y', strtotime($main_data->created_at))  }}</b></div>
					<div class="fila2"><b>Hora Emision &nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ date('g:i:a', strtotime($main_data->created_at))  }}</b></div>
					<div class="fila2"><b>CLIENTE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp; &nbsp; &nbsp;{{ $main_data->customer->name}}</b></div>
					<div class="fila2"><b>R.U.C &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; &nbsp; &nbsp; &nbsp;{{ $main_data->customer->document }}</b></div>
					<div class="fila2"><b>DIRECCION&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; &nbsp; &nbsp; {{ $main_data->customer->address }}</b></div>
				</td>
			</tr>
		</table>
	</header>

	<section>

    @switch($sunat)
        @case(2)
            @include('pdf.partials.quotation')
            @break

        @default
            @include('pdf.partials.common')
    @endswitch


			  <div style="width:100%;text-align:center;padding:5px;font-size:10px;border-top:1px solid #939a9e;border-bottom:1px solid #939a9e;margin-top:10px;">{{ $numberToLetters }}</div>
			@if($sunat == 1)	
			  <table width="100%" style="margin-top:10px;">
				<tr>
					<td>
						<p style="font-size:10px;">Hash : {{ $main_data->hash }} </p>
					</td>
					<td align="center">
						<img width="80" src="" >
					</td>
				
					<td align="right">
				@if(!$main_data->sunat_code == '00')		
						<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(120)->generate($main_data->resume)) !!}" alt="">
				@endif	
					</td>
				</tr>
			  </table>
			@endif
	</section>
	<footer>
		<!--<div class="visible-print text-center">
			<img src="data:image/png;base64,  base64_encode(QrCode::format('png')->size(120)->generate($main_data->resume)) !!}" alt="">
		</div> -->
		<br>
	   <div style="font-size:10px;text-align:center">Representacion impresa de <b>{{ $name_document }}</b></div> 
	</footer>
	
</body>
</html>