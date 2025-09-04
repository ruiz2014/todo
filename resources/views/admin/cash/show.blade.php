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
                        <form action="{{ route('cashes.end', $cash->id) }}" method="POST">
                            @csrf
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
                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Saldo Contado:</strong>
                                    {{ $contado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">Saldo Yapes:</strong>
                                    {{ $yape }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong class="show-strong">En Caja :</strong>
                                    {{ $cash->amount + $yape + $contado }}
                                </div>
                                <hr>
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
