@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Category
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-start align-items-sm-center">

                           <span class="card-title">{{ __('Update') }} Categoria</span>

                             <div class="float-right">
                                <a href="{{ url()->previous() }}" class="float-end btn btn-outline-secondary btn-sm ms-3"  data-placement="left">
                                    <ion-icon name="refresh"></ion-icon>
                                </a>
                              </div>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('categories.update', $category->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('category.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
