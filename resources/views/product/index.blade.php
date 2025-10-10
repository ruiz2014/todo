@extends('layouts.app')

@section('template_title')
    Products
@endsection

@section('content')
    <div class="container-fluid mb-3">
        <div>
            <button data-bs-toggle="modal" data-bs-target="#exampleModal3" class="btn btn-outline-success" >Importar Productos</button>
        </div>
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
                                <a href="{{ route('products.create') }}" class="btn btn-outline-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>                       
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif
                    @if ($message = Session::get('danger'))
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <form class="d-flex">
                            <div class="input-group">
                                <input name="search" class="form-control form-control-sm" value="{{ $text }}" type="search" placeholder="Buscar" aria-label="Search">
                                <button class="btn btn-outline-primary px-4" type="submit">
                                    <ion-icon name="search"></ion-icon>
                                </button>
                            </div>
                        </form>
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover">
                                <thead class="table-info">
                                    <tr>
                                        <th >Nombre</th>
                                        <th >Descripcion</th>
                                        <th >Precio</th>
                                        <th >Categoria</th>
                                        <th >Stock</th>
                                        <th >Minimo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td >{{ $product->name }}</td>
                                            <td >{{ $product->description }}</td>
                                            <td >{{ $product->price }}</td>
                                            <td >{{ $product->category_name}}</td>
                                            <td >{{ $product->stock }}</td>
                                            <td >{{ $product->minimo }}</td>
                                            <td>
                                                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary xs-margin" href="{{ route('products.show', $product->id) }}"><ion-icon name="eye"></ion-icon></a>
                                                    <a class="btn btn-sm btn-success xs-margin" href="{{ route('products.edit', $product->id) }}"><ion-icon name="create"></ion-icon></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm xs-margin" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><ion-icon name="trash"></ion-icon></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $products->withQueryString()->links() !!}
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
                    <p>Primero descargue este formato de excel <a href="{{ asset('lista Productos.xlsx') }}">descargar formato</a>, esto le descargar un formato en excel que debera ser llenado 
                        con el nombre del producto, descripcion y precio....No modifigue la estructura de este archivo, las cabeceras de las columnas ni agregue algun dato mas.
                        luego subalo al servidor. <br>
                        Esta opcion solo sera posible ejecutarlo una vez ... ! OJO ¡</p>
                    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control">
                        <br>
                        <button type="submit" class="btn btn-outline-success"><i class="fa fa-file"></i> Import User Data</button>
                    </form>
            
                </div>
                <div class="modal-footer">
                    <a href="" type="button" class="btn btn-secondary">Volver</a>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection



