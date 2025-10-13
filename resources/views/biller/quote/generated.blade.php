@extends('layouts.app')

@section('template_title')
    Caja
@endsection

@section('content')

<div class="container-fluid">
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
    <div class="row">
         <div class="col-sm-12">
            <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center;">

                <span id="card_title" class="text-uppercase">
                    Cotizacion
                </span>

                <div class="float-right">

                    <a href="{{ url('generate-pdf/'.$quote->id.'/cotizacion') }}" class="btn btn-outline-primary float-right"  data-placement="left">
                        <ion-icon name="save"></ion-icon> A4
                    </a>
                    <a href="{{ url('generate-pdf-ticket/'.$quote->id.'/cotizacion') }}" class="btn btn-outline-success float-right"  data-placement="left">
                        <ion-icon name="clipboard"></ion-icon> Ticket
                    </a>

                    <a href="{{ route('quotes.convert', $quote->document_code ) }}" class="btn btn-outline-success" >convertir a venta</a>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm float-right"  data-placement="left">
                        <ion-icon name="refresh"></ion-icon>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    
        <div class="col-md-3">
            <div class="panel invoice-list">
                <div class="list-group animate__animated animate__fadeInLeft">
                <a href="#" class="list-group-item list-group-item-action active">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">Cotizacion</h5>
                    <!-- <small>3 days ago</small> -->
                    </div>
                    <p class="amount mb-0">{{ $quote->identifier }}</p>
                    <!-- <div>Concepto de la factura.</div> -->
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"></h5>
                    <!-- <small class="text-muted">3 days ago</small> -->
                    </div>
                    <p class="amount mb-1">Forma de Pago</p>
                    <div class="text-muted">Contado.</div>

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
                    <strong>{{ date('d-m-Y', strtotime($quote->created_at))  }}</strong>
                    <span class="float-end"> <strong>Estado:</strong> {{ $quote->status }}</span>

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
                                <strong>{{ $quote->customer->name }}</strong>
                            </div>
                            <div>Direccion: {{ $quote->customer->address }}</div>
                            <div>Email: {{ $quote->customer->email }}</div>
                            <div>Phone: {{ $quote->customer->phone }}</div>
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
                                    <td class="item_name">{{ $temp->name }}</td>
                                    <td class="item_desc d-none d-sm-table-cell">{{ $temp->name }}</td>

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
                                            <span>S/.</span><strong>{{ number_format($total, 2) }}</strong>
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

.footer .footer-app {
    text-align:center;
    font-size:.78rem;
    padding:10px;
}
</style>    
@endsection


