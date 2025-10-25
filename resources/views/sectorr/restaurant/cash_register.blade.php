
@extends('layouts.app')

@section('template_title')
    Caja
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Atenciones
                            </span>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4" role="alert">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    @if ($message = Session::get('danger'))
                        <div class="alert alert-danger m-4" role="alert">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th >Mesa</th>
                                        <th >Ubicacion</th>
                                        <th >Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attentions as $attention)
                                        <tr>
                                            <td>1</td>
                                            <td >{{ $attention->table->identifier }}</td>
                                            <td >{{ $attention->table->room->name }}</td>
                                            <td >{{ $attention->status }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary " href="{{ route('pay.show', ['order'=> $attention->code]) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                <!-- <a class="btn btn-sm btn-success" href=""><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a> -->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
          
            </div>
        </div>
    </div>
@endsection
