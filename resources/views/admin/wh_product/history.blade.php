@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('template_title')
    Productos Almacen
@endsection

@section('content')
    <div class="container-fluid mb-3">

    @if ($message = Session::get('success'))
        <div class="alert alert-success m-4" role="alert">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('danger'))
        <div class="alert alert-danger m-4" role="alert">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Products') }}
                            </span>

                            <!-- <div class="float-right">
                                <a href=" route('whp.temp') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                                <a href="" id=""  class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal3"><ion-icon name="cart-outline"></ion-icon>Trans</a>
                            </div> -->
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th >Name</th>
                                        <th >Description</th>
                                        <th >Lote</th>
                                        <th >Fecha de Ingreso</th>
                                        <th >Stock</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wh_products as $product)
                                        <tr>
                                            <td></td>
                                            <td >{{ $product->name }}</td>
                                            <td >{{ $product->description }}</td>
                                            <td >{{ $product->batch }}</td>
                                            <td >{{ $product->date }}</td>
                                            <td >{{ $product->entry }}</td>
                                            <td>
                                                <!-- <a id="pr-{product->product_id }}" class="btn btn-sm btn-primary prod-id" href="" data-bs-toggle="modal" data-bs-target="#exampleModal"><ion-icon name="eye"></ion-icon>Agregar</a> -->
                                                <a href="" data-id="{{ $product->id }}"  class="btn btn-sm btn-warning opt-prod" data-bs-toggle="modal" data-bs-target="#exampleModal2"><ion-icon name="cart-outline"></ion-icon>Transferir Producto</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $wh_products->withQueryString()->links() !!}
            </div>
        </div>
    </div>
        <!-- Modal-2 -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal2Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModal2Label">Ingresar Stock</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('whp.transfer') }}" method="POST">  
                @csrf 
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <h3 id="prod_form" class="form-label-2"></h3>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="" style="width:100%;">Local</label>
                                <select id="local_id" name="local_id" class="form-select" aria-label="Default select example" style="width:100%;">
                                    <option value="">Seleccione Local</option>     
                                    @foreach($locals as $lid => $local)
                                    <option value="{{$lid}}">{{$local}}</option>
                                    @endforeach
                                </select>
                                
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="amount_form" class="form-label-2">{{ __('Amount') }}</label>
                                    <input type="text" name="amount" class="form-control-2 @error('customer_doc') is-invalid @enderror" value="{{ old('amount') }}" id="amount_form" placeholder="3.00">
                                    {!! $errors->first('amount', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>
                            <input type="hidden" name="row" id="idem">
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>     
            </div>
        </div>
    </div>  
@endsection 
@push('scripts')
    <script></script>
@endpush
@section('script')  
<!-- <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>    -->
 

    <script>
        $('.opt-prod').click(function(){
            const dataId = $(this).data('id');
            $("#idem").val(dataId);
        })
    </script>

@endsection

