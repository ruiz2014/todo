    <table>
        <thead>
            <tr>
                <td align="center"><b>Idem</b></td>
                <td align="center"><b>Producto</b></td>
                <td align="center"><b>Descripcion</b></td>
                <td align="center"><b>Stock</b></td>
            </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td style="">{{ $product->id }}</td>
                <td style="">{{ $product->name }}</td>
                <td style="">{{ $product->description }}</td>
                <td class="">0.0</td>
            </tr> 
        @endforeach            
        </tbody>
    </table>