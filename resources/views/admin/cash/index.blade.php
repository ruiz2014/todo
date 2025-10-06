@extends('layouts.app')

@section('template_title')
    Cashes
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Cashes') }}
                            </span>

                             <div class="float-right">
                                <a href="" id=""  class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal3"><ion-icon name="cart-outline"></ion-icon>Abrir Caja</a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <form class="d-flex">
                            <div class="input-group">
                                <input name="search" class="form-control form-control-sm" value="{{ $text }}" type="search" placeholder="Buscar" aria-label="Search">
                                <button class="btn btn-outline-primary px-4" type="submit">
                                    <ion-icon name="search"></ion-icon>
                                </button>
                            </div>
                        </form>
                        <div class="table-responsive mt-3">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th >Local</th>
                                        <th >Vendedor</th>
                                        <th >Saldo A.</th>
                                        <th >Fecha</th>
                                        <th >Fe. Cierre</th>
                                        <th >Saldo C.</th>
                                        <th >Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cashes as $cash)
                                        <tr>
                                            <td >{{ $cash->local_name }}</td>
                                            <td >{{ $cash->name }}</td>
                                            <td >{{ $cash->amount }}</td>
                                            <td >{{ $cash->created_at }}</td>
                                            <td >{{ $cash->close_cash }}</td>
                                            <td >{{ $cash->close_amount }}</td>
                                            <td >{{ $cash->status }}</td>
                                            <td>
                                                <!-- <a href="" id="{{$cash->id}}"  class="btn btn-sm btn-outline-primary cash" data-bs-toggle="modal" data-bs-target="#exampleModal2"><ion-icon name="cart-outline"></ion-icon>Mostrar Caja</a> -->
                                                <a class="btn btn-sm btn-outline-primary  xs-margin" href="{{ route('cashes.show', $cash->id) }}"><ion-icon name="eye"></ion-icon></a>
                                            @if($cash->status == 1)    
                                                <a id="{{$cash->id}}" class="cash btn btn-sm btn-outline-success xs-margin" href="" data-bs-toggle="modal" data-bs-target="#exampleModal2"><ion-icon name="create"></ion-icon></a>
                                                <!-- <a class="btn btn-sm btn-danger xs-margin" href="route('cashes.close', $cash->id) }}"><ion-icon name="trash"></ion-icon></a> -->
                                                <!-- <a class="btn btn-sm btn-danger xs-margin" href="oute('cashes.destroy', $cash->id) }}" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? true : false;"><ion-icon name="trash"></ion-icon></a> -->
                                            @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $cashes->withQueryString()->links() !!}
            </div>
        </div>
        <!-- Modal-1 -->
        <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModal3Label" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="exampleModal3Label">Abrir Caja</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                     
                    <form method="POST" action="{{ route('cashes.store') }}"  role="form">
                        @csrf 
                        <div class="modal-body">
                            <div class="col-md-12 mt20 mt-2">
                                <div class="row padding-1 p-1">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label for="local_cash" class="form-label-2">{{ __('Locales') }}</label>
                                            <select name="local_cash" id="local_cash" class="form-control-2 mt-1 line vld draw">
                                                <option value="">Seleccione local</option>    
                                            @foreach($local_cash as $key => $value)
                                                <option value="{{ $key }}" >{{ $value }}</option>
                                            @endforeach
                                            </select>
                                            {!! $errors->first('local_cash', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}  
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label for="seller" class="form-label-2">{{ __('Usuarios') }}</label>
                                            <select name="seller" id="seller" class="form-control-2 mt-1 line vld draw">
  
                                            </select>
                                            {!! $errors->first('seller', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}  
                                        </div>
                                    </div>

                                    <div class="col-md-12">    
                                        <div class="form-group mb-2 mb20">
                                            <label for="amount_id" class="form-label-2">{{ __('Monto') }}</label>
                                            <input type="number" name="amount" class="form-control-2 @error('amount') is-invalid @enderror" value="{{ old('amount') }}" id="stock" placeholder="Stock">
                                            {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                        </div>
                                    </div>
        
                                    <div class="col-md-12 mt20 mt-2">
                                        <button type="submit" id="mierda_b" class="btn btn-primary">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    
                    </form>
                </div> 
            </div>
        </div>

                <!-- Modal-2 -->
        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModal2Label" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h1 class="modal-title fs-5" id="exampleModal3Label">Caja</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="POST" action="{{ route('cashes.iocash') }}"  role="form">
                        @csrf 
                        <div class="modal-body">
                            <div class="col-md-12 mt20 mt-2">
                                <div class="row padding-1 p-1">
                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label for="type" class="form-label-2">{{ __('Locales') }}</label>
                                            <select name="type" id="type" class="form-control-2 mt-1 line vld draw">
                                                <option value="">Seleccione local</option> 
                                                <option value="1">Entrada</option> 
                                                <option value="2">Salida</option> 
                                            </select>
                                            {!! $errors->first('local_cash', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}  
                                        </div>

                                        <div class="col-md-12">    
                                            <div class="form-group mb-2 mb20">
                                                <label for="io_amount" class="form-label-2">{{ __('Monto') }}</label>
                                                <input type="number" name="io_amount" class="form-control-2 @error('amount') is-invalid @enderror" value="{{ old('io_amount') }}" id="stock" placeholder="3.0">
                                                {!! $errors->first('io_amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                            </div>
                                        </div>
                                        <input type="hidden" name="cash_id" id="cash_id">
                                        <div class="col-md-12">    
                                            <div class="form-group mb-2 mb20">
                                                <label for="reason_amount" class="form-label-2">{{ __('Reason') }}</label>
                                                <input type="text" name="observation" class="form-control-2 @error('amount') is-invalid @enderror" value="{{ old('reason_amount') }}" id="stock" placeholder="Ingreso por cancelacion">
                                                {!! $errors->first('amount', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt20 mt-2">
                                        <button type="submit" class="btn btn-outline-success">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>    
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')  
    <script>

        let local_cash = document.getElementById('local_cash');
        let seller = document.getElementById('seller');
        let type = null; 

        local_cash.addEventListener('change', ()=>{
            const seller_id = local_cash.value;
// alert(seller_id)
            fetch(`{{ url('cashes/seller/${seller_id}') }}`)
            .then(response => response.json())
            .then(data => {
                console.log(data)
                seller.innerHTML = '<option value="">Seleccione Usuario</option>';
                // Agregar nuevas opciones
                data.forEach(data => {
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = data.name;
                    seller.appendChild(option);
                });
            })

        });

        $('.cash').click(function(){
            let cash = $(this).attr('id');
            $('#cash_id').val(cash)
            /*fetch(`{{ url('cashes/display/${cash}') }}`)
            .then(response => response.json())
            .then(data => {*/
                //console.log(data)
                // seller.innerHTML = '<option value="">Seleccione Usuario</option>';
                // // Agregar nuevas opciones
                // data.forEach(data => {
                //     const option = document.createElement('option');
                //     option.value = data.id;
                //     option.textContent = data.name;
                //     seller.appendChild(option);
                // });
           // })
            
        })
    </script>
@endsection    