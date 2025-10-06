@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} User
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        

                        <div class="d-flex justify-content-between small-ch align-items-start align-items-sm-center">

                            <span class="">
                                {{ __('Update') }} Usuario
                            </span>

                             <div class="float-right d-flex mb-2 mb-sm-0">
                                <a class="btn btn-outline-primary btn-sm float-right"  data-placement="left" href="{{ route('register.edit', $user->id) }}">
                                    Actualizar Password
                                </a>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm ms-3"  data-placement="left">
                                    <ion-icon name="refresh"></ion-icon>
                                </a> 
                              </div>
                        </div>
                    </div>
                    <div class="card-body bg-white">

                    @if(is_null($user->rol) || $user->rol > 2)
                        <form method="POST" action="{{ route('users.update', $user->id) }}"  role="form">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('user.form')

                        </form>
                    @else
                        <form method="POST" action="{{ route('users.updateAdmin', $user->id) }}"  role="form">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('user.adminform')

                        </form>
                    @endif    
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
