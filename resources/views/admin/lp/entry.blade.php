@extends('layouts.app')

@section('template_title')
    Buy Products
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Buy Products') }}
                            </span>

                             <div class="float-right">
                                <a href="" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <form class="d-flex">
                                <div class="input-group">
                                    <input name="search" class="form-control form-control-sm" value="{{ $text }}" type="search" placeholder="Buscar" aria-label="Search">
                                    <button class="btn btn-primary px-4" type="submit">
                                        <ion-icon name="search"></ion-icon>
                                    </button>
                                </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th >Proveedor</th>
                                        <th >Document</th>
                                        <th >Total</th>
                                        <th >Fecha</th>
                                        <th >Entrega</th>
                                        <th >Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($buyProducts as $buyProduct)
                                        <tr>
                                            <td >{{ $buyProduct->name }}</td>
                                            <td >{{ $buyProduct->document }}</td>
                                            <td >{{ $buyProduct->total }}</td>
                                            <td >{{ $buyProduct->created_at }}</td>
                                            <td >{{ $buyProduct->l_type }}</td>
                                            <td >{{ $buyProduct->status == 0 ? "Sin Registrar" : "Registrado" }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary " href="{{ route('lp.entryAction', $buyProduct->code) }}"><ion-icon name="eye"></ion-icon></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              {!! $buyProducts->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
@section('notification') 

    <script>
        const socket = io('http://localhost:3000',
        {
            path: "/socket.io",
            transports: ["websocket"],
        });
    
        // alert("esto es una gran mierda sabes eso")
        socket.emit('chat', "Se creo una compra");
    </script>
@endsection
