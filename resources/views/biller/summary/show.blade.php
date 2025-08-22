@extends('layouts.app')

@section('template_title')
    Voucher
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title" class="text-uppercase">
                                Boletas
                            </span>

                            <div class="float-right">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm float-right"  data-placement="left">
                                    <ion-icon name="refresh"></ion-icon>
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
                                        <td >{{ $type->success }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary xs-margin" href="{{ route('shop.generated', $type->document_code) }}"><ion-icon name="eye"></ion-icon></a>
                                        
                                            @if($type->dispatched == 1)  
                                            <a class="btn btn-sm btn-success xs-margin" href="{{ route('downloadXml', ['id'=>$type->document_code, 'type'=> 'boleta']) }}"><ion-icon name="code-download"></ion-icon></a>
                                            @endif
                                            @if($type->completed == 1)
                                            <a class="btn btn-sm btn-info xs-margin" href="{{ route('downloadCdr', ['id'=>$type->document_code, 'type'=> 'boleta']) }}"><ion-icon name="ribbon"></ion-icon></a>
                                            <a href="" class="btn btn-sm btn-warning xs-margin" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><ion-icon name="trash"></ion-icon></a>
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