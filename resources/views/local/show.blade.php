@extends('layouts.app')

@section('template_title')
    {{ $local->name ?? __('Show') . " " . __('Local') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Local</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('locals.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>User Id:</strong>
                                    {{ $local->user_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Company Id:</strong>
                                    {{ $local->company_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Local Name:</strong>
                                    {{ $local->local_name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Phone:</strong>
                                    {{ $local->phone }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Address:</strong>
                                    {{ $local->address }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
