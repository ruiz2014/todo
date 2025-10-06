@extends('layouts.app')

@section('template_title')
    Products
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ $buyProduct->status == 1 ? 'Entrada de Articulos : Registrado' : 'Entrada de Articulos'}}
                            </span>

                            <div class="float-right d-flex xs-margin">
                            @if($buyProduct->status == 0)    
                                <form id="form_register" action="{{ route('whp.register', $code) }}" method="POST">
                                    @csrf    
                                    <button type="submit" class="btn btn-outline-success btn-sm float-right" data-placement="left" onclick="event.preventDefault(); confirm('Seguro que desea hacer este registro ?') ? this.closest('form').submit() : false;"><ion-icon name="create"></ion-icon> Aprobar y Registrar Entrega</button>
                                </form>>    
                            @endif  
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm ms-3"  data-placement="left">
                                    <ion-icon name="refresh"></ion-icon>
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
                        <div class="row mb-4">

                            <div class="col-6 col-md-6">
                                <h6 class="mb-2">Proveedor:</h6>
                                <div>
                                    <strong>{{ $buyProduct->provider->name }}</strong>
                                </div>
                                <div>Direccion: {{ $buyProduct->provider->address }}</div>
                                <div>Email: {{ $buyProduct->provider->email }}</div>
                                <div>Phone: {{ $buyProduct->provider->phone }}</div>
                            </div>
                        </div>
                    
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th>Articulos</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($temps as $temp)
                                        <tr>
                                            <td class="item_name">{{ $temp->name }}</td>
                                            <td class="text-right">{{ $temp->stock }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Notacion</label>
                                    <textarea class="form-control" id="" name="notation" rows="3" form='form_register'></textarea>
                                </div>
                            </div>
                        </div>
                    </div>    
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
