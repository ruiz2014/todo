@extends('layouts.app')

@section('template_title')
    {{ $cash->name ?? __('Show') . " " . __('Cash') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cash</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cashes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                    @if($cash->status == 1)    
                        <form action="{{ route('cashes.end', $cash->id) }}" method="POST">
                            @csrf
                    @endif        
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha de la Apertura :</strong>
                                    {{ $cash->created_at }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong >Local:</strong>
                                    {{ $cash->local->local_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Usuario:</strong>
                                    {{ $cash->user->name }}
                                </div>
                                <hr>
                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Estado :</strong>
                                    Abierto
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Saldo Apertura:</strong>
                                    {{ $cash->amount }} 
                                </div>

                            @foreach($payAll as $pa)
                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Saldo {{ $pa->name }}:</strong>
                                    {{ $pa->total }}
                                </div>
                            @endforeach  
                            
                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">En Credito :</strong>
                                    {{ $payCredit->total }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Dinero Entrada :</strong>
                                    {{ $input }}
                            @if(!$indetails->isEmpty())  
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                            <button style="padding:10px 20px" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIn" aria-expanded="true" aria-controls="collapseOne">
                                                <span class="badge text-bg-success">Ver detalle de Entradas</span>
                                            </button>
                                            </h2>
                                            <div id="collapseIn" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <ol class="list-group list-group-numbered">
                                                    @foreach($indetails as $idet)        
                                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                            <div class="fw-bold">Monto :</div>
                                                            {{ $idet->observation }}
                                                            </div>
                                                            <span class="badge text-bg-primary rounded-pill">{{ $idet->io_amount }}</span>
                                                        </li>
                                                    @endforeach    
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endif
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Dinero Salida:</strong>
                                    {{ $output }}
                                @if(!$outdetails->isEmpty())    
                                     <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                            <button style="padding:10px 20px" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOut" aria-expanded="true" aria-controls="collapseOne">
                                                <span class="badge text-bg-secondary">Ver detalle de Salidas</span>
                                            </button>
                                            </h2>
                                            <div id="collapseOut" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <ol class="list-group list-group-numbered">
                                                    @foreach($outdetails as $outdet)          
                                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                            <div class="fw-bold">Monto :</div>
                                                            {{ $outdet->observation }}
                                                            </div>
                                                            <span class="badge text-bg-primary rounded-pill">{{ $outdet->io_amount }}</span>
                                                        </li>
                                                    @endforeach 
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif    
                                </div>
                                
                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">En Caja :</strong>
                                    {{ ($cash->amount + $total + $input) - $output}}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Por Cobrar :</strong>
                                    {{ $payCredit->total }}
                                </div>
                                <hr>
                    @if($cash->status == 0) 
                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Efectivo Real :</strong>
                                    {{ $cash->close_amount }}
                                    <br>
                                    <strong class="show-strong">Motivo :</strong>
                                    <span>{{ $cash->observation }}</span>
                                </div>
                    @else   
                                 <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Efectivo Real :</strong>
                                    <input type="number" name="amount" class="form-control">
                                </div>  

                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Observacion :</strong>
                                    <textarea name="observation" rows="2" class="form-control"></textarea>
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Cerrar Caja :</strong><br>
                                    <button type="submit" class="btn btn-outline-success">Cerrar</button>
                                </div>
                        </form>
                    @endif    
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .show-strong{
            display: inline-block;
            width: 140px;
        }
    </style>
@endsection
