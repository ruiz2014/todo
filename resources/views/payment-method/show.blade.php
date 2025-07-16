@extends('layouts.app')

@section('template_title')
    {{ $paymentMethod->name ?? __('Show') . " " . __('Payment Method') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Payment Method</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('payment-methods.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Company Id:</strong>
                                    {{ $paymentMethod->company_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $paymentMethod->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $paymentMethod->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Observation:</strong>
                                    {{ $paymentMethod->observation }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Image:</strong>
                                    {{ $paymentMethod->image }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
