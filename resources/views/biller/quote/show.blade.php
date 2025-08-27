@extends('layouts.app')

@section('template_title')
    {{ $quote->name ?? __('Show') . " " . __('Quote') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Quote</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('quotes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Company Id:</strong>
                                    {{ $quote->company_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Local Id:</strong>
                                    {{ $quote->local_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Customer Id:</strong>
                                    {{ $quote->customer_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Document Code:</strong>
                                    {{ $quote->document_code }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Reference  Document:</strong>
                                    {{ $quote->reference _document }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Currency:</strong>
                                    {{ $quote->currency }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Total:</strong>
                                    {{ $quote->total }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Seller:</strong>
                                    {{ $quote->seller }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Serie:</strong>
                                    {{ $quote->serie }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Identifier:</strong>
                                    {{ $quote->identifier }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Numeration:</strong>
                                    {{ $quote->numeration }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Message:</strong>
                                    {{ $quote->message }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Status:</strong>
                                    {{ $quote->status }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
