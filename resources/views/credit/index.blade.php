@extends('layouts.app')

@section('template_title')
    Attentions
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Attentions') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('credits.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th >Cliente</th>
                                        <th >Total</th>
                                        <th >Seller</th>
                                        <th >Identifier</th>
                                        <th >Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attentions as $attention)
                                        <tr>

                                            <td >{{ $attention->name }}</td>
                                            <td >{{ $attention->total }}</td>
                                            <td >{{ $attention->seller }}</td>
                                            <td >{{ $attention->identifier }}</td>
                                            <td >{{ $attention->status }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-primary xs-margin" href="{{ route('shop.generated', $attention->document_code) }}"><ion-icon name="eye"></ion-icon></a>
                                                <a class="btn btn-sm btn-success btn-edit" id="{{ $attention->id }}" href="" data-bs-toggle="modal" data-bs-target="#exampleModal3"><ion-icon name="create"></ion-icon></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $attentions->withQueryString()->links() !!}
            </div>
        </div>
        <!-- Modal-3 -->
        <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModal3Label" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h1 class="modal-title fs-5" id="exampleModal3Label">Actualizar Pago</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                     
                    <form method="POST" action="{{ route('credits.store') }}"  role="form">
                        @csrf 
                        <div class="modal-body">
                            <div class="col-md-12 mt20 mt-2">
                                <div class="row padding-1 p-1">
                                    <div class="col-md-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="type_payment" type="checkbox" value="1" id="myCheckbox">
                                            <label class="form-check-label" for="myCheckbox">
                                                Esta venta fue Cancelada 
                                            </label>
                                        </div>
                                        <input type="hidden" id="sale_id" name="sale_id" value="">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label for="methods" class="form-label-2">{{ __('Metodo de Pago') }}</label>
                                            <select name="methods" id="methods" class="form-control-2 mt-1 line vld draw">
                                                <option value="">Seleccione metodo</option> 
                                            @foreach($methods as $key => $value)
                                                <option value="{{ $key }}" >{{ $value }}</option>
                                            @endforeach   

                                            </select>
                                            {!! $errors->first('methods', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}  
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
    </div>
@endsection
@section('script')
    <script>
        $('input:checkbox').prop('checked', false);
        $('.btn-edit').click(function(){
            let sale_id = $(this).attr("id");
            $('#sale_id').val(sale_id);
        })
    </script>
@endsection
