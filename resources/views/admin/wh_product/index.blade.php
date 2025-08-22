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
        <a href="{{ route('whp.entry') }}" class="btn btn-outline-success" >Nuevas Entradas de Mercansia</a> 
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
                                <!-- <a href="route('whp.temp') }}" class="btn btn-primary btn-sm float-right"  data-placement="left"> -->
                                   <!-- __('Create New') }} -->
                                </a>
                                <a href="" id=""  class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal3"><ion-icon name="cart-outline"></ion-icon>Crear Producto</a>
                              </div>
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
                                        <th >Price</th>
                                        <th >Category Id</th>
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
                                            <td >{{ $product->price }}</td>
                                            <td >{{ $product->category_id }}</td>
                                            <td >{{ $product->stock }}</td>
                                            <td>
                                            <a class="btn btn-outline-primary" href="{{ route('whp.view', $product->product_id) }}"><ion-icon name="eye"></ion-icon>Ver historial</a>
                                                <!-- <a id="pr-{product->product_id }}" class="btn btn-sm btn-primary prod-id" href="" data-bs-toggle="modal" data-bs-target="#exampleModal"><ion-icon name="eye"></ion-icon>Agregar</a>
                                                <a href="" id="product->product_id }}%{product->name}}"  class="btn btn-sm btn-warning opt-prod" data-bs-toggle="modal" data-bs-target="#exampleModal2"><ion-icon name="cart-outline"></ion-icon>No sirve</a> -->
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ingresor Stock</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('whp.upload') }}" method="POST">  
                @csrf 
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <h3 id="prod_form" class="form-label-2"></h3>
                                </div>
                            </div>

                            <!-- <div class="col-md-6 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="cost_form" class="form-label-2">{{ __('Cost') }}</label>
                                    <input type="text" name="cost" class="form-control-2 rror('customer_doc') is-invalid nderror" value="ld('cost') }}" id="cost_form" placeholder="5.00">
                                    errors->first('cost', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div> -->

                            <div class="col-md-6 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="amount_form" class="form-label-2">{{ __('Amount') }}</label>
                                    <input type="text" name="amount" class="form-control-2 @error('customer_doc') is-invalid @enderror" value="{{ old('amount') }}" id="amount_form" placeholder="3.00">
                                    {!! $errors->first('amount', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2 mb20">
                                    <label for="minimo" class="form-label-2">Lote</label>
                                    <input type="text" name="batch" class="mt-2 form-control-2 @error('batch') is-invalid @enderror" value="{{ old('batch') }}" id="batch" placeholder="Lote">
                                    {!! $errors->first('batch', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2 mb20">
                                    <label for="minimo" class="form-label-2">Stock</label>
                                    <input type="text" name="stock" class="mt-2 form-control-2 @error('batch') is-invalid @enderror" value="{{ old('stock') }}" id="Stock" placeholder="stock">
                                    {!! $errors->first('stock', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>
                            <input type="hidden" name="warehouse_id" value="{{ $wh_id }}">
                            <input type="hidden" id="wh_product_id" name="product_id" value="">
                        
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

                            <!-- <div class="col-md-6 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="cost_form" class="form-label-2">{{ __('Cost') }}</label>
                                    <input type="text" name="cost" class="form-control-2 rror('customer_doc') is-invalid nderror" value="{{ old('cost') }}" id="cost_form" placeholder="5.00">
                                    {!! $errors->first('cost', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div> -->

                            <div class="col-md-6 mt-3">
                                <div class="form-group mb-2 mb20">
                                    <label for="amount_form" class="form-label-2">{{ __('Amount') }}</label>
                                    <input type="text" name="amount" class="form-control-2 @error('customer_doc') is-invalid @enderror" value="{{ old('amount') }}" id="amount_form" placeholder="3.00">
                                    {!! $errors->first('amount', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                            </div>
                            <input type="hidden" name="product_id" id="idem">
                            <input type="hidden" name="warehouse_id" value="{{ $wh_id }}">
                        
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


    <!-- Modal-3 -->
    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModal3Label" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModal3Label">Ingresar Stock</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('whp.tempAction') }}" id="form-product" method="POST">  
                    @csrf 
                    <div class="modal-body">
                        <div class="col-md-12 mt20 mt-2">
                            <div class="row padding-1 p-1">
                                <div class="col-md-6">
                                    <div class="form-group mb-2 mb20">
                                        <label for="name" class="form-label-2">{{ __('Name') }}</label>
                                        <input type="text" name="name" class="form-control-2 @error('name') is-invalid @enderror" value="{{ old('name') }}" id="name" placeholder="Name">
                                        {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                </div>

                                <div class="col-md-6"> 
                                    <div class="form-group mb-2 mb20">
                                        <label for="minimo" class="form-label-2">Lote</label>
                                        <input type="text" name="batch" class="form-control-2 @error('batch') is-invalid @enderror" value="{{ old('batch') }}" id="batch" placeholder="Lote">
                                        {!! $errors->first('batch', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                </div>

                                <div class="col-md-6">    
                                    <div class="form-group mb-2 mb20">
                                        <label for="price" class="form-label-2">{{ __('Stock') }}</label>
                                        <input type="number" name="stock" class="form-control-2 @error('stock') is-invalid @enderror" value="{{ old('stock') }}" id="stock" placeholder="Stock">
                                        {!! $errors->first('stock', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                </div>
    
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label for="category_id" class="form-label-2">{{ __('Category Id') }}</label>
                                        <select name="category_id" id="" class="form-control-2 mt-1 line vld draw">
                                            @foreach($categories as $key => $value)
                                            <option value="{{ $key }}" >{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('category_id', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}  
                                    </div>
                                </div>
                                <input type="hidden" name="warehouse_id" value="{{ request()->session()->get('aver') }}">

                                <div class="col-md-12 mt20 mt-2">
                                    <button type="submit" id="mierda_b" class="btn btn-primary">{{ __('Submit') }}</button>
                                </div>
                            </div>
                        </div>

                    </div>
                 
                </form>
            </div> 
        </div>
    </div> 
    
    <!-- Modal -->
    <div class="modal fade" id="exampleModal4" tabindex="-1" aria-labelledby="exampleModal4Label" aria-hidden="false">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModal4Label">Verificacion de Existencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Quiso decir : </p>
                    <p>se encontraron productos con similar nombre ... si el producto ya en esta lista no lo cree y precione el boton VOLVER, caso contrario siga con la creacion del Producto </p>
                <table>
                    <thead>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Accion</th>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
            
                </div>
                <div class="modal-footer">
                    <a href="" type="button" class="btn btn-secondary">Volver</a>
                    <button type="button" id="checkPrdt"  class="btn btn-success">Crear Producto</button>
                </div>
                </div>
            </div>
        </div>

@endsection
@section('notification') 

    <!-- <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>    -->
    <script>
        const socket = io('http://localhost:3000',
        {
            path: "/socket.io",
            transports: ["websocket"],
        });
    
        // alert("esto es una gran mierda sabes eso")
        socket.emit('chat', "Se envio un producto");
    </script>

<!-- <script>
    $('.prod-id').click(function(){
        let text = $(this).attr('id');
        let string = text.split('-');
        let id = string[1];
        $('#wh_product_id').val(id);
    });

    $('.opt-prod').click(function(){
            let text = $(this).attr('id');
            let string = text.split('%');
            let id = string[0];
            let prod = string[1];
            $('#idem').val(id);
            $('#prod_form').html(prod);
    });

    $('#local_id').change(function(e){
        e.preventDefault();
        let local = $(this).val();
        let product_id = $('#idem').val();
        let data = {local : local, product_id : product_id};
        fetch(`/check_product`, {
            method: "POST",
            headers: { 
                'Content-Type': 'application/json',
                "X-CSRF-Token": document.querySelector('input[name=_token]').value
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(datos => {
            console.log(datos)
           
            if(!datos.ok){
                alert("Error ... Todavia no hay ese producto para ese local");
                $('#idem').val("");
                $("#local_id option").prop("selected", false); 
                $('#exampleModal2').modal('hide');
                // showResponse(datos['orders'], 'new');
                // $('#send-kitchen').prop('disabled', false);
            }else{
                // alert("joder tio");
                // console.log(datos)
            }
        });

        console.log(product_id)
        console.log(local)
    })
</script> -->
@endsection
@section('script')  
<script>
    // window.onload = function() {
    //     $('#exampleModal').modal('show');
    // }

    $("#mierda_b").on("click", function(e) {
        e.preventDefault();
        let search = $("#name").val();
        var data = { search: search };
        let body = '';
        $('#tbody').empty(); 
        fetch(`{{ url('tool/check') }}`, {
            method: "POST",
            headers: { 
                'Content-Type': 'application/json',
                "X-CSRF-Token": document.querySelector('input[name=_token]').value
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(datos => {
            // console.log(datos)
            if(datos.ok){ 
                if(datos.checks.length){
                    datos.checks.forEach(p => {
                    body += `<tr>
                                <td>${p.name}</td>
                                <td>${p.description}</td>
                                <td class="text-center">
                                    <button id="${p.id}" class="status btn btn-outline-danger"><ion-icon name="hourglass-outline"></ion-icon></button>
                                    <p class="crono" data-time="${p.created_at}"></p>
                                </td> 
                            </tr>`;
                    });
                    $('#tbody').prepend(body)
                    $('#exampleModal4').modal('show');
                    $('#exampleModal3').modal('hide');
                }else{
                    $("#form-product").submit();
                }
               
            }else{
                console.log(datos)
            }
        });
    });

    $('#checkPrdt').click(function(){
        $("#form-product").submit();
    });

    $("#exampleModal4").on("hidden.bs.modal", function () {
        $('#exampleModal3').modal('show');
    });

</script>

@endsection
