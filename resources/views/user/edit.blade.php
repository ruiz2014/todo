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
                        

                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span class="card-title">
                                {{ __('Update') }} User
                            </span>

                             <div class="float-right">
                                <a class="btn btn-primary btn-sm float-right"  data-placement="left" href="{{ route('register.edit', $user->id) }}">
                                    Actualizar Password
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
