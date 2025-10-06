@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Warehouse
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span class="card-title">
                                {{ __('Update') }} Almacen
                            </span>

                             <div class="float-right">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm ms-3"  data-placement="left">
                                    <ion-icon name="refresh"></ion-icon>
                                </a> 
                              </div>
                        </div>
                    </div>
                    
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('warehouses.update', $warehouse->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('warehouse.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
