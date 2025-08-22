@extends('layouts.app')

@section('template_title')
    Resumen
@endsection

@section('content')
    <div class="container-fluid">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if (session('danger'))
        <div class="alert alert-danger">
            {{ session('danger') }}
        </div>
        @endif

        @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Resumenes') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('summary.create') }}" class="btn btn-outline-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        <form class="d-flex">
                            <div class="input-group">
                                <input name="search" class="form-control form-control-sm" value="{{ $text }}" type="search" placeholder="Buscar" aria-label="Search">
                                <button class="btn btn-outline-success px-3" type="submit">
                                    <ion-icon name="search"></ion-icon>
                                </button>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th >Identificador</th>
                                        <th >Ticket</th>
                                        <th >Creacion</th>
                                        <th >Envio</th>
                                        <th >Cdr</th>
                                        <th >Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($summaries as $type)
                                    <tr>   
                                        <td >{{ $type->identifier }}</td>
                                        <td >{{ $type->ticket }}</td>
                                        <td >{{ $type->date_created }}</td>
                                        <td >{{ $type->date_send}}</td>
                                        <td >{{ $type->cdr }}</td>
                                        <td >{{ $type->status == 1 ? 'Enviado' : 'Pendiente' }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary xs-margin" href="{{ route('summary.show', $type->ticket) }}"><ion-icon name="eye"></ion-icon></a>
                                            <a class="btn btn-sm btn-success xs-margin" href="{{ route('downloadXml', ['id'=>$type->id, 'type'=> 'resumen']) }}"><ion-icon name="code-download"></ion-icon></a>
                                            <a class="btn btn-sm btn-info xs-margin" href="{{ route('downloadCdr', ['id'=>$type->id, 'type'=> 'resumen']) }}"><ion-icon name="ribbon"></ion-icon></a>
                                            <!-- <form action=" route('products.destroy', $product->id) }}" method="POST">
                                               
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm xs-margin" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><ion-icon name="trash"></ion-icon></button>
                                            </form> -->
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $summaries->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection