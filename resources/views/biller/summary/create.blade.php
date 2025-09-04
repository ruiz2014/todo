@extends('layouts.app')

@section('template_title')
    Sumary
@endsection

@push('styles')
   <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')

@if (session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif


@if (session('danger'))

    <div class="alert alert-danger">
    {{ session('danger') }}

</div>
@endif


@if (session('info'))

<div class="alert alert-info">

    {{ session('info') }}

</div>

@endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                Seleccione fecha
                            </span>

                             <div class="float-right">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm float-right"  data-placement="left">
                                  <ion-icon name="refresh"></ion-icon>
                                </a>
                              </div>
                        </div>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('summary.search') }}" method="POST">
                            @csrf    
                                <div class="form-group">
                                    <label>Date:</label>
                                    <!-- <div class="input-group date" id="" data-target-input="nearest">
                                    </div> -->
                                    <input type="date" name="birthday" class="form-control @error('birthday') is-invalid @enderror"  />
                                        {!! $errors->first('birthday', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                @if($documentos)
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Boletas A enviar</h3>
                        </div>
                        <div class="card-body">

                            <table class="table table-striped" id="table">
                                <thead>
                                    <tr>
                                        <th class="as">Documento</th>
                                        <th class="as">Estado</th>
                                        <th class="as">fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($documentos as $documento)
                                    <tr>
                                        <td>{{ $documento->identifier }}</td>
                                        <td>{{ $documento->status }}</td>
                                        <td>{{ $documento->created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>


                            <form action="{{ route('summary') }}" method="POST">
                            @csrf    
                            <input type="hidden" name="fecha" value="{{ $fecha }}">
                                <div class="card-footer">
                                <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>

                    </div>
                @endif
                </div>    

            </div>
   
@stop

@section('js')
   
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(function() {
  $('input[name="birthday"]').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 1901,
    maxYear: parseInt(moment().format('YYYY'),10)
  });

});
</script>
@stop