@extends('layouts.app')

@section('template_title')
    Voucher
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

                            <span id="card_title" class="text-uppercase">
                                {{ $name }}
                            </span>

                            <div class="float-right">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm float-right"  data-placement="left">
                                    <ion-icon name="refresh"></ion-icon>
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
                                        <th >Cliente</th>
                                        <th >Total</th>
                                        <th >Fecha</th>
                                        <th >Cdr</th>
                                        <th >Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($types as $type)
                                    <tr>   
                                        <td >{{ $type->identifier }}</td>
                                        <td >{{ $type->name }}</td>
                                        <td >{{ $type->total }}</td>
                                        <td >{{ $type->created_at}}</td>
                                        <td >{{ $type->cdr }}</td>
                                        <td >{{ $type->status == 1 ? 'Enviado' : 'Pendiente' }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary xs-margin" href="{{ route('shop.generated', $type->document_code) }}"><ion-icon name="eye"></ion-icon></a>
                                        @if($code != '00') 
                                            @if($type->dispatched == 1)  
                                            <a class="btn btn-sm btn-success xs-margin" href="{{ route('downloadXml', ['id'=>$type->document_code, 'type'=> $name]) }}"><ion-icon name="code-download"></ion-icon></a>
                                            @endif
                                            @if($type->completed == 1)
                                                @if($code == '03')
                                                <span class="align-middle fs-6">{{ $type->message }}</span>
                                                @else
                                                <a class="btn btn-sm btn-info xs-margin" href="{{ route('downloadCdr', ['id'=>$type->document_code, 'type'=> $name]) }}"><ion-icon name="ribbon"></ion-icon></a>
                                                @endif
                                            <!-- <a href="" class="btn btn-sm btn-warning xs-margin" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><ion-icon name="trash"></ion-icon></a> -->
                                            @endif
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $types->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection