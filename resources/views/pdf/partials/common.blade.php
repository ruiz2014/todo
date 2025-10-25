<table class="border" WIDTH="100%" cellspacing="0">
			<thead  style="border-bottom:1px solid #aaa3a6">
				<tr>
					<th style="padding:4px 1px;color:#000;font-size:11px;width:20%;">NOMBRE</th>
					<th style="padding:4px 1px;color:#000;font-size:11px;width:40%;">DESCRIPCION</th>
					<th style="padding:4px 1px;color:#000;font-size:10px;width:10%;">CANT</th>
					<th style="padding:4px 1px;color:#000;font-size:10px;width:15%;">P. UNIT.</th>
					<th style="padding:4px 1px;color:#000;font-size:10px;width:15%;">IMPORTE</th>
				</tr>
			</thead>
			<tbody class="details">
	
		@foreach($temps as $item)
				<tr>
					<td style="text-align:center;padding:5px 0px;font-size:9px;">{{ $item->name }}</td>
					<td style="text-align:center;padding:5px 0px;font-size:9px;">{{ $item->description }}</td>
					<td style="text-align:center;font-size:9px;">{{ $item->amount }}</td>
					<td style="text-align:center;font-size:9px;">{{ $item->price }}</td>
					<td style="text-align:center;font-size:9px;">{{ $item->amount * $item->price  }}</td>
				</tr>
		@endforeach

		<?php
		$vendedor = 'Jose Bolas';
		$monedaImp='PEN';
        ?>	

    <?php $subTotal=$total/1.18;
		$igv=$total-$subTotal;
	?>

				<!-- <tr> <td colspan=4 style="border:none;padding:10px;"></td><tr> -->
				<tr>
					<td colspan=3 style="border:none;padding:3px 1px;"></td>
					<td style="font-size:10px;text-align:right;"></td>
					<td style="font-size:12px;text-align:center;"></td>
				</tr>
				<tr style="background:#FFF;">
					<td colspan=3 style="border:none;"></td>
					<td colspan=1 style="font-size:10px;text-align:right;padding:4px 1px;">SUB-TOTAL&nbsp;S/.</td>
					<td style="font-size:10px;text-align:center;"><span ><?=number_format((float)$subTotal,2,'.', '') ?></span></td>
				</tr>
				<tr style="background:#FFF;padding:3px 1px;">
					<td colspan=3 style="border:none;"></td>
					<td colspan=1 style="font-size:10px;text-align:right;padding:4px 1px;">IGV 18%&nbsp;S/.</td>
					<td style="font-size:10px;text-align:center;"><span ><?=number_format((float)$igv,2,'.', '') ?></span></td>
				</tr>
				<tr style="background:#FFF;padding:3px 1px;">
					<td colspan=3 style="border:none;"></td>
					<td colspan=1 style="font-size:10px;text-align:right;padding:4px 1px;"><b>TOTAL&nbsp;S/.</b></td>
					<td style="font-size:10px;text-align:center;"><span ><?=number_format((float)$total,2,'.', '') ?></span></td>
				</tr>
			</tbody>
		</table>