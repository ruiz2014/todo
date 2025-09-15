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
                        <div>
                            <button data-bs-toggle="modal" data-bs-target="#exampleModal3" class="btn btn-outline-success" >Importar Productos</button>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Products') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        <th >Name</th>
                                        <th >Description</th>
                                        <th >CompId</th>
                                        <th >Price</th>
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
                                            <td >{{ $product->company_id }}</td>
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
                    <h1 class="modal-title fs-5" id="exampleModal4Label">Verificacion de Existencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Quiso decir : </p>
                    <p>se encontraron productos con similar nombre ... si el producto ya en esta lista no lo cree y precione el boton VOLVER, caso contrario siga con la creacion del Producto </p>
                    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
        
                        <input type="file" name="file" class="form-control">
                        <br>
                        <button type="submit" class="btn btn-success"><i class="fa fa-file"></i> Import User Data</button>
                    </form>
            
                </div>
                <div class="modal-footer">
                    <a href="" type="button" class="btn btn-secondary">Volver</a>
                    <button type="button" id=""  class="btn btn-success">Importar Excel</button>
                </div>
                </div>
            </div>
    </div>
    </div>
@endsection
