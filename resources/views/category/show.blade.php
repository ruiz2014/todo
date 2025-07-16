@extends('layouts.app')

@section('template_title')
    {{ $category->name ?? __('Show') . " " . __('Category') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Category</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('categories.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Company Id:</strong>
                                    {{ $category->company_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Category Name:</strong>
                                    {{ $category->category_name }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
