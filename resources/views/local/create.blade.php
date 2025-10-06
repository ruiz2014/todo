@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Local
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <div class="d-flex justify-content-between small-ch align-items-start align-items-sm-center">
                            <span id="card_title">
                               {{ __('Create') }} Local
                            </span>
                            <div class="float-right">
                                <a href="{{ url()->previous() }}" class="float-end btn btn-outline-secondary btn-sm ms-3"  data-placement="left">
                                    <ion-icon name="refresh"></ion-icon>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('locals.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('local.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
