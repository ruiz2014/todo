@extends('layouts.app')

@section('template_title')
    {{ $attention->name ?? __('Show') . " " . __('Attention') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Attention</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('credits.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Local Id:</strong>
                                    {{ $attention->local_id }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Customer Id:</strong>
                                    {{ $attention->customer_id }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Type Payment:</strong>
                                    {{ $attention->type_payment }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Total:</strong>
                                    {{ $attention->total }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Seller:</strong>
                                    {{ $attention->seller }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Identifier:</strong>
                                    {{ $attention->identifier }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Message:</strong>
                                    {{ $attention->message }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Low Motive:</strong>
                                    {{ $attention->low_motive }}
                                </div>

                             
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $attention->status }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
