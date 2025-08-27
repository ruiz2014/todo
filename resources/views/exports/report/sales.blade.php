<table>
    <thead>
        <tr>Tipo de Pago</tr>
        <tr>Total</tr>
    </thead>
    <tbody>
        <tr>
            <td>Efectivo</td>
            <td>{{ $contado }}</td>
        </tr>
        <tr>
            <td>Yape</td>
            <td>{{ $yape }}</td>
        </tr>
        <tr>
            <td>Crédito</td>
            <td>{{ $credito }}</td>
        </tr>
        <tr>
            <td><b>Total</b></td>
            <td><b>{{ $yape + $contado + $credito }}</b></td>
        </tr>
    </tbody>
</table>
<table>
        <thead>
            <tr>
                <td colspan="15" align="center" >RiverSport-{{ $voucher }} Fecha del {{ $start }} - {{ $end }}</td>
            </tr>
            <tr>
                <td align="center"><b>Producto</b></td>
                <td align="center"><b>Precio</b></td>
                <td align="center"><b>Cantidad</b></td>
                <td align="center"><b>Total</b></td>
                <td align="center"><b>Responsable</b></td>
                <td align="center"><b>Fecha</b></td>
            </tr>
        </thead>
        <tbody>
        @foreach($sales as $report)
            <tr>
                <td style="">{{ $report->product->name }}</td>
                <td class="">{{  number_format($report->price, 2) }}</td>
                <td class="">{{  $report->amount }}</td>
                <td class="">{{  number_format($report->price * $report->amount , 2)  }}</td>
                <td class="">{{  $report->user->name }}</td>
                <td class="">{{  $report->created_at}}</td> 
            </tr> 
        @endforeach            
        </tbody>
    </table>