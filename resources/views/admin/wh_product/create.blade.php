@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Product
@endsection

@section('content')
<!-- ESTA PARTE NO VALE LA PENA QUE VOLVAMOS A INTENTAR ES MEJOR QUE TERMINEMOS -->
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Product</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('whp.tempAction') }}"  id="form-product" role="form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="url" value="{{ $url }}">
                            <div class="row padding-1 p-1">
                                <div class="col-md-6">
                                    <div class="form-group mb-2 mb20">
                                        <label for="name" class="form-label-2">{{ __('Name') }}</label>
                                        <input type="text" name="name" class="form-control-2 @error('name') is-invalid @enderror" value="{{ old('name', $product?->name) }}" id="name" placeholder="Name">
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
                                            <option value="{{ $key }}" {{$product?->category_id == $key ? 'selected' : ''}}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('category_id', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}  
                                    </div>
                                </div>
                                <input type="hidden" name="warehouse_id" value="{{ request()->session()->get('aver') }}">

                                <div class="col-md-12 mt20 mt-2">
                                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                    <a href="{{ route('whp.tempAction') }}">a ver</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Launch demo modal
        </button> -->

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Verificacion de Existencia</h1>
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
                    <a href="{{ url($url) }}" type="button" class="btn btn-secondary">Volver</a>
                    <button type="button" id="checkPrdt"  class="btn btn-success">Crear Producto</button>
                </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
<script>
    // window.onload = function() {
    //     $('#exampleModal').modal('show');
    // }
    $(".btn-primary").on("click", function(e) {
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
                    $('#exampleModal').modal('show');
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
</script>
@endsection