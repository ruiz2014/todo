@extends('layouts.app')

@section('template_title')
    Locals
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Locals') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('locals.create') }}" class="btn btn-outline-primary btn-sm float-right"  data-placement="left">
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
                                        <th >Telefono</th>
                                        <th >Direccion</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($locals as $local)
                                        <tr>
                                            <td >{{ $local->local_name }}</td>
                                            <td >{{ $local->phone }}</td>
                                            <td >{{ $local->address }}</td>
                                            <td>
                                                <form action="{{ route('locals.destroy', $local->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary xs-margin" href="{{ route('locals.show', $local->id) }}"><ion-icon name="eye"></ion-icon></a>
                                                    <a class="btn btn-sm btn-success xs-margin" href="{{ route('locals.edit', $local->id) }}"><ion-icon name="create"></ion-icon></a>
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
                {!! $locals->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
