@extends('layouts.app')

@section('template_title')
    Productos Almacen
@endsection

@section('content')
    <h3>Bienvenido a Casa {{ $local->local_name }}</h3>
    <p>Date: {{ \App\Helpers\CompanyHelper::getSector() }}</p>
@endsection
@section('script')  
<script>
    if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function (registrations) {
                //returns installed service workers
                if (registrations.length) {
                for(let registration of registrations) {
                    registration.unregister();
                }
                }
            });
        }
</script>
@endsection