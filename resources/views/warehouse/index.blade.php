@extends('layouts.app')

@section('template_title')
    Warehouses
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Warehouses') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('warehouses.create') }}" class="btn btn-outline-success btn-sm float-right"  data-placement="left">
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
                                <button class="btn btn-outline-success px-4" type="submit">
                                    <ion-icon name="search"></ion-icon>
                                </button>
                            </div>
                        </form>
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover">
                                <thead class="table-success">
                                    <tr>
                                        <th >Nombre</th>
                                        <th >Telefono</th>
                                        <th >Direccion</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouses as $warehouse)
                                        <tr>
										<td >{{ $warehouse->warehouse_name }}</td>
										<td >{{ $warehouse->phone }}</td>
										<td >{{ $warehouse->address }}</td>

                                        <td>
                                                <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST">
                                                   
                                                    <a class="btn btn-sm btn-primary xs-margin" href="{{ route('whp.show', $warehouse->id) }}"><ion-icon name="eye"></ion-icon></a>
                                                    <a class="btn btn-sm btn-success xs-margin" href="{{ route('warehouses.edit', $warehouse->id) }}"><ion-icon name="create"></ion-icon></a>
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
                {!! $warehouses->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
