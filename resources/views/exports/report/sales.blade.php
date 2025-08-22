<table>
        <thead>
            <tr>
                <td colspan="15" align="center" >RiverSport-{{ $voucher }} Fecha del {{ $start }} - {{ $end }}</td>
            </tr>
            <tr>
                <td align="center">Producto</td>
                <td align="center">Precio</td>
                <td align="center">Cantidad</td>
                <td align="center">Responsable</td>
                <td align="center">Fecha</td>
            </tr>
        </thead>
        <tbody>
        @foreach($sales as $report)
            <tr>
                <td style="">{{ $report->product->name }}</td>
                <td class="">{{  number_format($report->price/1.18, 2) }}</td>
                <td class="">{{  $report->amount }}</td>
                <td class="">{{  $report->user->name }}</td>
                <td class="">{{  $report->created_at}}</td> 
            </tr> 
        @endforeach            
        </tbody>
    </table>