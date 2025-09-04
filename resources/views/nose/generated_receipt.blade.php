@extends('layouts.app')

@section('template_title')
    Caja
@endsection

@section('content')

<div class="container-fluid">

    <div class="row">
         <div class="col-sm-12">
            <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center;">

                <span id="card_title" class="text-uppercase">
                    Comprobante
                </span>

                <div class="float-right">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm float-right"  data-placement="left">
                        <ion-icon name="refresh"></ion-icon>
                    </a>
                </div>
            </div>
            @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                <p>{{ $message }}</p>
            </div>
            @endif
            @if ($message = Session::get('danger'))
                <div class="alert alert-danger" role="alert">
                    <p>{{ $message }}</p>
                </div>
            @endif
        </div>
    </div>
    <div class="row">
    
        <div class="col-md-3">
            <div class="panel invoice-list">
                <div class="list-group animate__animated animate__fadeInLeft">
                <a href="#" class="list-group-item list-group-item-action active">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $attention->voucher->name}} </h5>
                    <!-- <small>3 days ago</small> -->
                    </div>
                    <p class="amount mb-0">{{ $attention->identifier }}</p>
                    <!-- <div>Concepto de la factura.</div> -->
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ $attention->customer->name }}</h5>
                    <!-- <small class="text-muted">3 days ago</small> -->
                    </div>
                    <p class="amount mb-1">Forma de Pago</p>
                    <div class="text-muted">{{ $attention->type_payment == 1 ? 'Contado' : 'Credito' }}</div>

                    <p class="amount mb-1 mt-2">Medio de Pago</p>

                    @foreach($methods as $method)
                    <div class="d-flex w-100 justify-content-between">
                        <div class="text-muted">{{ $method->name }}</div>
                        <div class="d-flex w-25 justify-content-between">
                            <small class="text-muted">S/.</small> <small>{{ $method->total }}</small>
                        </div>
                    </div>
                    @endforeach
                    <div class="d-flex w-100 mt-2 justify-content-between" style="border-top: 2px solid #b9b6b6;">
                        <h5 class="mb-1 mt-1">Total</h5>
                        <div class="d-flex w-25 justify-content-between mt-1">
                        <span>S/.</span><span>{{ $total }}</span>
                        </div>
                    </div>
                    <p class="amount mb-1 mt-2">Observaciones</p>
                    <!-- <div class="text-muted">Donec id elit non mi porta.</div> -->
                </a>
                <!-- <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Nombre de cliente</h5>
                    <small class="text-muted">3 days ago</small>
                    </div>
                    <p class="amount mb-1">1200€</p>
                    <div class="text-muted">Donec id elit non mi porta.</div>
                </a> -->
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card animate__animated animate__fadeIn">
                <div class="card-header">
                    Fecha :
                    <strong>{{ date('d-m-Y', strtotime($attention->created_at))  }}</strong>
                    <span class="float-end"> <strong>Estado:</strong> {{ $attention->status }}</span>

                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-6 col-md-6">
                            <h6 class="mb-2">From:</h6>
                            <div>
                                <strong>{{ $company->company_name }}</strong>
                            </div>
                            <div>{{ $company->address }}</div>
                            <div>Email: {{ $company->id}}</div>
                            <div>Phone: {{ $company->id }}</div>
                        </div>

                        <div class="col-6 col-md-6">
                            <h6 class="mb-2">Cliente:</h6>
                            <div>
                                <strong>{{ $attention->customer->name }}</strong>
                            </div>
                            <div>Direccion: {{ $attention->customer->address }}</div>
                            <div>Email: {{ $attention->customer->email }}</div>
                            <div>Phone: {{ $attention->customer->phone }}</div>
                        </div>

                    </div>

                    <div class="table-responsive-sm">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" width="2%" class="center">#</th>
                                    <th scope="col" width="20%">Producto/Servicio</th>
                                    <th scope="col" class="d-none d-sm-table-cell" width="50%">Descripción</th>
                                    <th scope="col" width="10%" class="text-right">P. Uni.</th>
                                    <th scope="col" width="8%" class="text-right">Cant.</th>
                                    <th scope="col" width="10%" class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($temps as $temp)    
                                <tr>
                                    <td class="text-left">1</td>
                                    <td class="item_name">{{ $temp->product->name }}</td>
                                    <td class="item_desc d-none d-sm-table-cell">{{ $temp->product->description }}</td>

                                    <td class="text-right">{{ $temp->price }}</td>
                                    <td class="text-right">{{ $temp->amount }}</td>
                                    <td class="text-right">{{ $temp->price * $temp->amount }}</td>
                                </tr>
                            @endforeach    
                                <!-- <tr>
                                    <td class="center">2</td>
                                    <td class="item_name">Custom Services</td>
                                    <td class="item_desc d-none d-sm-table-cell">Instalation and Customization (cost per hour)</td>

                                    <td class="text-right">150,00€</td>
                                    <td class="text-right">20</td>
                                    <td class="text-right">3.000,00€</td>
                                </tr> -->
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-sm-5">
                        </div>

                        <div class="col-lg-4 col-sm-5 ml-auto">
                            <table class="table table-sm table-clear">
                                <tbody>
                                    <tr>
                                        <td class="left">
                                            <strong>Subtotal</strong>
                                        </td>
                                        <td class="d-flex justify-content-between bg-light"><span>S/.</span><span>{{ number_format($total/1.18, 2) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong>IGV (18%)</strong>
                                        </td>
                                        <td class="d-flex justify-content-between bg-light"><span>S/.</span><span>{{ number_format($total - ($total/1.18), 2) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="left">
                                            <strong>Total</strong>
                                        </td>
                                        <td class="d-flex justify-content-between bg-light">
                                            <span>S/.</span><strong>{{ number_format($attention->total, 2) }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="footer container-fluid mt-3 bg-light">
        <div class="row">
            <div class="col footer-app">&copy; Todos los derechos reservados · <span class="brand-name"></span></div>
        </div>
    </div>
</div>    
<style>
    /* .panel {
        width:320px;
    }
    .invoice-list {
        position:absolute;
        top:68px;
        left:5px;
    } */
    /* .mm {
  max-width:1200px;
    min-width:420px;
} */
.footer .footer-app {
    text-align:center;
    font-size:.78rem;
    padding:10px;
}
</style>    
@endsection
@push('scripts')
    <!-- @if($notify == 0)
        <script type="module"> 

            import { io } from "https://cdn.socket.io/4.7.5/socket.io.esm.min.js";
            // const socket = io('https://chapi.nafer.com.pe');
            const socket = io('http://localhost:3000');

            let table = table }};
            socket.emit('cashier', table);
            setTimeout(function(){
                window.location.href = "oute('pay.index') }}";
            }, 10000);
            // setInterval(function(){ location.reload(); }, 4000);

            // socket.on('cashier', (msg)=>{
            //     // alert("A caja")
            //     notify("Llego una nueva orden para generar su voucher...");
            //     // alert("Llego una nueva orden para generar su voucher...")
            //     setInterval(function(){ location.reload(); }, 4000);
            //     // location.reload();
            // })
        </script>   
    @endif  -->
@endpush  