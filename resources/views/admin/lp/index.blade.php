@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush

@section('template_title')
    Venta de Articulos
@endsection

@section('content')
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


                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

    <div class="container-fluid mb-3">
         <a href="{{ route('lp.entry') }}" class="btn btn-outline-success xs-margin" >Nuevas Entradas de Mercancia</a>
        <!-- <form id="prue" action="oute('lp.store') }}" method="POST">
                @csrf    
            <div class="row padding-1 p-1 mb-3">
           
                <div class="col-md-6">
                    <label for="" style="width:100%;">Productos</label>
                    <select id="product_id" name="product_id" class="form-select" aria-label="Default select example" style="width:100%;">
                        <option value="">Seleccione productos</option>     
                        foreach(products as id => prod)
                        <option value="id}}">{prod}}</option>
                        endforeach
                    </select>
                </div>
            </div> 
            <button id="btn-add" class="btn btn-outline-success mb-3" type="submit">Agregar</button>  
        </form>      -->
        <button data-bs-toggle="modal" data-bs-target="#exampleModal3" class="btn btn-outline-warning" >importar productos</button>
    </div>  

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card mb-2">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Products') }}
                            </span>

                             <!-- <div class="float-right">
                                <a href="oute('tool.createProduct') }}" id="check" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div> -->
                        </div>
                    </div>


                    <div class="card-body bg-white">
                        <form class="d-flex">
                                <div class="input-group">
                                    <input name="search" class="form-control form-control-sm" value="{{ $text }}" type="search" placeholder="Buscar" aria-label="Search">
                                    <button class="btn btn-success px-4" type="submit">
                                        <ion-icon name="search"></ion-icon>
                                    </button>
                                </div>
                        </form>
                        <div class="table-responsive mt-3">
                            
                            <table class="table table-striped table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th >Nombre</th>
                                        <th >Descripcion</th>
                                        <th >Precio</th>
                                        <th >Categoria</th>
                                        <th >Stock</th>
                                        
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
    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModal4Label" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModal4Label">Importar Productos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Pasos para importar : </p>
                    <p>Primero descargue este formato de excel <a href="{{ route('lp.format') }}">descargar formato</a>, esto le descargar un formato en excel que debera ser llenado 
                        con la lista de productos y demas datos que en ella se le pida .....No modifigue la estructura de este archivo
                        luego subalo al servidor. <br>
                        Esta opcion solo sera posible ejecutarlo una vez ... ! OJO ¡</p>
                    <form action="{{ route('lp.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control">
                        <br>
                        <button type="submit" class="btn btn-success"><i class="fa fa-file"></i> Import User Data</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="" type="button" class="btn btn-secondary">Volver</a>
                </div>
                </div>
            </div>
    </div>
        <!-- Modal -->
    <!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    </div>  -->
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