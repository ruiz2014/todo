@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('template_title')
    Venta de Articulos
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

        <form id="prue" action="oute('lp.store') }}" method="POST">
                @csrf    
            <div class="row padding-1 p-1 mb-3">

                <div class="col-md-6">
                    <label for="" style="width:100%;">Productos</label>
                    <select id="product_id" name="product_id" class="form-select" aria-label="Default select example" style="width:100%;">
                        <option value="">Seleccione productos</option>     
                        @foreach($products as $id => $prod)
                        <option value="{{$id}}">{{$prod}}</option>
                        @endforeach
                    </select>
                </div>
            </div> 
            <button id="btn-add" class="btn btn-outline-success mb-3" type="submit">Agregar</button>  
        </form>     
    </div>  

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Products') }}
                            </span>

                             <div class="float-right">
                                <a href="oute('tool.createProduct') }}" id="check" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

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
                        <form class="d-flex">
                                <div class="input-group">
                                    <input name="search" class="form-control form-control-sm" value="{{ $text }}" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-primary px-4" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        <div class="table-responsive">
                            
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th >Nombre</th>
                                        <th >Descripcion</th>
                                        <th >Precio</th>
                                        <th >Categoria</th>
                                        <th >Stock</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($local_products as $product)
                                        <tr>
                                            <td >{{ $product->name }}</td>
                                            <td >{{ $product->description }}</td>
                                            <td >{{ $product->price }}</td>
                                            <td >{{ $product->category_name}}</td>
                                            <td >{{ $product->stock }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary xs-margin" href=""><ion-icon name="eye"></ion-icon></a>
                                                <a href="" id="{{ $product->product_id }}%{{$product->name}}"  class="xs-margin btn btn-sm btn-warning opt-prod" data-bs-toggle="modal" data-bs-target="#exampleModal"><ion-icon name="cart-outline"></ion-icon></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $local_products->withQueryString()->links() !!}
            </div>
        </div>
    </div>
        <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ingresor Stock</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="oute('lp.upload') }}" method="POST">  
                @csrf 
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <h3 id="prod_form" class="form-label-2"></h3>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="cost_form" class="form-label-2">{{ __('Cost') }}</label>
                                    <input type="text" name="cost" class="form-control-2 @error('customer_doc') is-invalid @enderror" value="{{ old('cost') }}" id="cost_form" placeholder="5.00">
                                    {!! $errors->first('cost', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="amount_form" class="form-label-2">{{ __('Amount') }}</label>
                                    <input type="text" name="amount" class="form-control-2 @error('customer_doc') is-invalid @enderror" value="{{ old('amount') }}" id="amount_form" placeholder="3.00">
                                    {!! $errors->first('amount', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>
                        <input type="hidden" name="idem" id="idem">
                        
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
@section('script')  
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
<script>
    $('.opt-prod').click(function(){
        let text = $(this).attr('id');
        let string = text.split('%');
        let id = string[0];
        let prod = string[1];
        $('#idem').val(id);
        $('#prod_form').html(prod);
    });
</script>
@endsection     