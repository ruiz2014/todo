@extends('layouts.app')

@section('template_title')
    Reporte
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form id="myForm" action="{{ route('report.sales') }}" >
                    <div class="mb-3">
                        <label for="start" class="form-label">Fecha Inicial</label>
                        <input name="start" type="date" class="form-control" id="start" aria-describedby="">
                        <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                    </div>
                    <div class="mb-3">
                        <label for="end" class="form-label">Fecha Final</label>
                        <input name="end" type="date" class="form-control" id="end">
                    </div>
                </form>
                <a href="#" class="btn btn-outline-dark" onclick="document.getElementById('myForm').submit()">Enviar</a>
            </div>
        </div>
    </div>
@endsection 
