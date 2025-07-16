@extends('layouts.app')

@section('template_title')
    {{ $product->name ?? __('Show') . " " . __('Product') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Product</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('products.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Name:</strong>
                                    {{ $product->name }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Description:</strong>
                                    {{ $product->description }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Product Type:</strong>
                                    {{ $product->product_type }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Company Id:</strong>
                                    {{ $product->company_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Price:</strong>
                                    {{ $product->price }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Category Id:</strong>
                                    {{ $product->category_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Provider Id:</strong>
                                    {{ $product->provider_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Stock:</strong>
                                    {{ $product->stock }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Minimo:</strong>
                                    {{ $product->minimo }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
