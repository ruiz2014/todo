@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Customer
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Cliente</span>
                        <a href="{{ url()->previous() }}" class="float-end btn btn-outline-secondary btn-sm ms-3"  data-placement="left">
                            <ion-icon name="refresh"></ion-icon>
                        </a>
                    </div>
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('customers.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('customer.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
