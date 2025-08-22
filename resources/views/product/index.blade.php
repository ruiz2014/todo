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
    </div>
@endsection
