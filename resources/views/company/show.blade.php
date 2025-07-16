@extends('layouts.app')

@section('template_title')
    {{ $company->name ?? __('Show') . " " . __('Company') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Company</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('companies.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $company->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Company Name:</strong>
                                    {{ $company->company name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Document:</strong>
                                    {{ $company->document }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Address:</strong>
                                    {{ $company->address }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Ubigeo:</strong>
                                    {{ $company->ubigeo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Sector Id:</strong>
                                    {{ $company->sector_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Number Employees:</strong>
                                    {{ $company->number_employees }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Number Subsidiary:</strong>
                                    {{ $company->number_subsidiary }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
