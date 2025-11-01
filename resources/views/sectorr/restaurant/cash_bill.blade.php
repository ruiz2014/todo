@extends('layouts.app')

@section('template_title')
    Caja
@endsection

@section('content')
    <div class="container-fluid">

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

    <!-- https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/FrameCriterioBusquedaWeb.jsp -->
    <!-- <div class="wrapper-mesa p-2 p-md-3 shadow" id="btnModal">
                hola
    </div> -->
    <!-- <button type="button" class="btn btn-default btn-circle btn-lg">C</i></button>
    <button type="button" class="btn btn-primary btn-circle btn-lg"><i class="fa fa-check"></i></button> -->
    <button id="btnModal" type="button" class="btn btn-outline-success btn-circle btn-lg"><i class="glyphicon glyphicon-link"></i></button>
    <!-- <button type="button" class="btn btn-info btn-circle btn-lg"><i class="glyphicon glyphicon-ok"></i></button>
    <button type="button" class="btn btn-warning btn-circle btn-lg"><i class="glyphicon glyphicon-remove"></i></button>
    <button type="button" class="btn btn-outline-danger btn-circle btn-lg"><i class="glyphicon glyphicon-heart"></i></button> -->
    <form id="form_sale" action="{{ route('pay.store') }}" method="POST">
            @csrf    
        <div class="row padding-1 p-1">
            
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label for="receipt" class="form-label-2">{{ __('Type of receipt') }}</label>
                    <select name="receipt" id="receipt" class="form-control-2 line vld draw mt-2">
                        <option value="00">Ticket</option>
                        <option value="03">Boleta</option>
                        <option value="01">Factura</option>
                    </select>
                    {!! $errors->first('receipt', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="position-relative">
                    <label for="contact" class="form-label-2">{{ __('Contact') }}</label>
                    <div class="d-flex input-group-sm">
                        <div class="input-group-prepend">
                            <!-- <span class="input-group-text" id="inputGroup-sizing-sm">Small</span> -->
                            <button type="button" id="clean" class="btn btn-primary btn-sm">Small</button>
                        </div>
                        <input type="text" id="term" autocomplete="off" class="form-control-2" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                        <input type="hidden" name="customer_id" id="customer_id">
                        <input type="hidden" name="code" id="code" value="{{ $order }}">
                    </div>
                    
                    <div class="result position-absolute">
                        <ul id="box-search">
                                <!-- <li>juan</li>
                                <li>luis</li>
                                <li>varios</li> -->
                        </ul>
                    </div>
                </div>
                {!! $errors->first('customer_id', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                {!! $errors->first('code', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
            </div> 

            <div class="col-md-6">
                <div class="form-group mb-2 mb20">
                    <label for="customer_doc" class="form-label-2">{{ __('Document') }}</label>
                    <input type="text" name="customer_doc" class="form-control-2 @error('customer_doc') is-invalid @enderror" value="" id="customer_doc" placeholder="Documento">
                    {!! $errors->first('customer_doc', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
        </div>
    </form>

        <div class="row mb-3">
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
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th >Mesa</th>
                                        <th >Ambiente</th>
                                        <th >Nombre</th>
                                        <th >Cantidad</th>
                                        <th >Precio Uni.</th>
                                        <th >Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attentions as $attention)
                                        <tr>
                                            <td >{{ $attention->table->identifier}}</td>
                                            <td >{{ $attention->table->room->name }}</td>
                                            <td >{{ $attention->name }}</td>
                                            <td >{{ $attention->amount }}</td>
                                            <td >{{ $attention->price }}</td>
                                            <td >{{ $attention->amount * $attention->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
          
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

                <div class="ps-3 mb-3" id="new_pay">
                    @foreach($payment_methods as $pay)  
                    <div class="form-check d-flex mb-2">
                        <div class="w-50">
                            <input class="form-check-input" type="radio" name="payMethod" value="{{ $pay->id }}" id="radioPay{{$pay->id}}" form="form_sale">
                            <label class="form-check-label" for="radioPay{{$pay->id}}">
                                {{ $pay->name }}
                            </label>
                        </div>
                        <input type="number" class="form-control-2" name="payMethodVal[]" id="radioMethod{{$pay->id}}" form="form_sale">
                    </div>
                    @endforeach
                    <div class="form-check d-flex mb-2">
                        <div class="w-50">
                            <input class="form-check-input" type="radio" name="payMethod" value="3" id="radioPay_detail" form="form_sale">
                            <label class="form-check-label" for="radioPay_detail">
                                Detallado
                            </label>
                        </div>    
                    </div>
                </div>


            {!! $errors->first('payMethod', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                <ul class="list-group mb-4" id="payMethod">
                @foreach($payment_methods as $pay)    
                    <li class="list-group-item d-flex">
                        <div class="w-50">
                            <input class="form-check-input me-1" name="payMethod[]" type="checkbox" value="{{ $pay->id }}" aria-label="..." form="form_sale">
                            {{ $pay->name }}
                        </div>
                        <input type="number" class="form-control-2" name="payMethodVal[]" id="payMethod_{{ $pay->id }}" placeholder="Monto S/."  style="width:50%;display:none" form="form_sale">
                    </li>
                @endforeach    
                </ul>
            </div>

            <div class="col-md-6">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Sub Total
                        <span class="badge text-bg-primary rounded-pill" id="subtotal"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        IGV
                        <span class="badge text-bg-primary rounded-pill" id="igv"></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Total
                        <span class="badge text-bg-primary rounded-pill" id="total">{{ $total }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <button id="btn-generate" class="btn btn-outline-primary" >Generar Comprobante</button>
            </div>
        </div>

        
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                
                <a href="https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/FrameCriterioBusquedaWeb.jsp" target="_blank" class="btn btn-outline-primary mb-3">Validar Usuario</a>
                <div class="alert alert-success d-none" role="alert" id="success">
                    <p>Hola</p>
                </div>
                <div class="alert alert-danger d-none" role="alert" id="error">
                    <p>Hola</p>
                </div>

                <form id="customer_form">
                @csrf
                <div class="row padding-1 p-1">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo_doc" class="form-label-2">{{ __('Type of document') }}</label>
                            <select name="tipo_doc" id="tipo_doc" class="form-control-2 line vld draw mt-2">
                                <option value="1">DNI</option>
                                <option value="6">RUC</option>
                                <option value="7">Pasaporte</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-2 mb-2 mb20">
                            <label for="document" class="form-label-2">{{ __('Document') }}</label>
                            <input type="text" name="document" class="form-control-2" value="" id="document" placeholder="Documento">
                            <div class="invalid-feedback error-document" role="alert"><strong></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2 mb20">
                            <label for="name" class="form-label-2">{{ __('Name') }}</label>
                            <input type="text" name="name" class="form-control-2" value="" id="name" placeholder="Nombre">
                            <div class="invalid-feedback error-name" role="alert"><strong></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2 mb20">
                            <label for="Address" class="form-label-2">{{ __('Address') }}</label>
                            <input type="text" name="address" class="form-control-2" value="" id="Address" placeholder="Direccion">
                            <div class="invalid-feedback error-address" role="alert"><strong></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2 mb20">
                            <label for="phone" class="form-label-2">{{ __('Phone') }}</label>
                            <input type="text" name="phone" class="form-control-2" value="" id="phone" placeholder="Telefono">
                            <div class="invalid-feedback error-phone" role="alert"><strong></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2 mb20">
                            <label for="email" class="form-label-2">{{ __('Email') }}</label>
                            <input type="text" name="email" class="form-control-2" value="" id="email" placeholder="Correo">
                            <div class="invalid-feedback error-email" role="alert"><strong></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2 mb20">
                            <label for="ubigeo" class="form-label-2">{{ __('Ubigeo') }}</label>
                            <input type="text" name="ubigeo" class="form-control-2" value="" id="ubigeo" placeholder="Ubigeo">
                            <div class="invalid-feedback error-ubigeo" role="alert"><strong></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2 mb20">
                            <label for="status" class="form-label-2">{{ __('Status') }}</label>
                            <input type="text" name="status" class="form-control-2" value="" id="status" placeholder="Estado">
                            <div class="invalid-feedback error-status" role="alert"><strong></strong></div>
                        </div>
                    </div>
                </div>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline-primary">Save changes</button>
              </div>
            </form>  
            </div>
          </div>
        </div>
    </div>

@endsection
@section('script')
<script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>  
<script>
    const socket = io('http://localhost:3000',
        {
            path: "/socket.io",
            transports: ["websocket"],
        });
    window.addEventListener("DOMContentLoaded", function(){
        let total_pay = 0;
        let customer_id = document.getElementById('customer_id');
        // let customer_doc = document.getElementById('customer_doc');
// NO SE POR QUE SIRVE ---->let code = document.getElementById('code');
        
        // let clear_btn = document.getElementById('clean');
        let error = document.querySelector('.alert-danger');
        let success = document.querySelector('.alert-success');

        let ed = document.querySelector('.error-document');
        let cn = document.querySelector('.error-name');

        let ifeed = document.querySelectorAll('.invalid-feedback');
        let tipo_doc = document.getElementById('tipo_doc');
        let current_doc = document.getElementById('document');
        let customer_name = document.getElementById('name');

        let btn_generate = document.getElementById('btn-generate');

        let customer_form = document.getElementById('customer_form');

        const btn = document.getElementById("btnModal");
        const modalRegistro = document.querySelector("#exampleModal");
        //las opciones son opcional - puedes quitarlo
        const myModal = new bootstrap.Modal(modalRegistro);
        let radioPay = null;

        payMethod.style.display = "none";

        $('#new_pay').change(function(ev){
            radioPay = ev.target.id;
        })
        

        payMethod.onclick = function(ev){
                // if(ev.target.value){
                // alert("paymethod")
                // const form = document.getElementById('form_sale'); //document.querySelector('form');
                // const formData = new FormData(form);

                // // Para obtener un valor
                // console.log(formData.get('nombre'));

                // // Para obtener todos los valores
                // for (const [key, value] of formData.entries()) {
                // console.log(`${key}: ${value}`);
                // }

                if(ev.target.checked){
                    document.getElementById(`payMethod_${ev.target.value}`).style.display="block"
                    console.log(ev.target.checked, ev.target.value)
                }else{
                    verbo = document.getElementById(`payMethod_${ev.target.value}`)
                    if(typeof verbo !== 'undefined' && verbo !== null) {
                        verbo.style.display="none";
                        verbo.value=""
                    }
                    // document.getElementById(`payMethod_${ev.target.value}`).value=""
                }
                console.log(verbo, ev.target.value, 'hola', ev.target.checked)
        }

        btn.onclick = () =>{ myModal.show(); }
        tipo_doc.onchange = () =>{ current_doc.value = ''; }
        btn_generate.onclick = ()=>{ validar();} // }

        customer_form.addEventListener('submit', e =>{
            e.preventDefault();
            sendData()
        })

        function sendData(){
            ifeed.forEach(xale => { 
                xale.style.display = "none"
			});

            if(tipo_doc.value == 1 && current_doc.value.length != 8){
                ed.style.display='block';
                ed.textContent = "El DNI debe ser 8 digitos";
                return 0;
            }

            if(tipo_doc.value == 6 && current_doc.value.length != 11){
                ed.style.display='block';
                ed.textContent = "El RUC debe ser 11 digitos";
                return 0;
            }

            const pattern = new RegExp('^[A-ZÁÉÍÓÚÑ ]+$', 'i');

            if(!pattern.test(customer_name.value)){
                cn.style.display='block';
                cn.textContent = "El campo nombre es obligatorio";
                return 0;
            }

            const form = new FormData(customer_form); 

            fetch(`/tool/register_customer`, {
                method: "POST",
                body: form,
                headers: { 
                    'Accept':'application/json',
                    "X-CSRF-Token": document.querySelector('input[name=_token]').value
                } 
            })
            .then(response => response.json())
            .then(result => {
                if(result.errors){

                    for (var key in result.errors) {
                        merror = document.querySelector(`.error-${key}`);
                        merror.style.display='block';
                        merror.textContent = result.errors[key][0];
                    }
                }
                else{
                    error.classList.add("d-none");
                    success.classList.remove("d-none");
                    success.textContent = 'Agregado con Exito';
                    
                }
                // setTimeout(() => {
                //     ifeed.forEach(xale => { 
                //         xale.style.display = "none";
                //     });
			    // }, 3000);
            })
        }

        function validar(){
            // alert(radioPay);
            let total =  document.getElementById('total').innerHTML;
            total_pay = 0;
            const customer_val = $('#customer_id').val();

            if (customer_val === null || customer_val === undefined || customer_val === "") {
                    console.log("No se ha seleccionado ninguna opción.");
                
                    Swal.fire({
                        icon: "error",
                        title: "Problema con cliente...",
                        text: "No ha elegido un cliente .... verifique los datos",
                    });
                    return 0;
            }

            if(radioPay === 'radioPay_detail'){
                // alert("si es details es");    
                    total =  document.getElementById('total').innerHTML;
                    total_pay = 0;
                    document.querySelectorAll('#payMethod input[type=checkbox]').forEach((e)=>{
                        if(e.checked === true){
                            total_pay += parseFloat(document.getElementById(`payMethod_${e.value}`).value);
                        }
                    });

                    // console.log(total+ ' ' +total_pay)
                    if(parseFloat(total) !== total_pay){
                        alert("El monto no es igual")
                        return 0;
                    }
                    
            }else if(radioPay !== 'radioPay_detail'){
                
                    total =  document.getElementById('total').innerHTML;
                    document.querySelectorAll('#new_pay input[type=radio]').forEach((e)=>{
                        if(e.checked === true){
                            total_pay += parseFloat(document.getElementById(`radioMethod${e.value}`).value);
                        }
                    });
                // alert("no es");
                    console.log(total+ ' ' +total_pay)
                    if(parseFloat(total) !== total_pay){
                        alert("El monto no es igual")
                        return 0;
                    }
            }

            generate_receipt();
        }


        function generate_receipt(){

            socket.emit('hall', 'se libero la mesa mierda no jodas....')

            const form = document.getElementById('form_sale'); //document.querySelector('form');
                // const formData = new FormData(form);
                // // Para obtener un valor
                // console.log(formData.get('nombre'));

                // // Para obtener todos los valores
                // for (const [key, value] of formData.entries()) {
                // console.log(`${key}: ${value}`);
                // }
            // var form_receipt = new FormData(prue); 
            // form_receipt.append("customer_id", customer_id.value);
            // form_receipt.append("customer_doc", customer_doc.value);
            // form_receipt.append("receipt", receipt.value);
            // form_receipt.append("code", code.value);
            // form_receipt.append("_token", document.querySelector('input[name=_token]').value);
           form.submit();
            // var request = new XMLHttpRequest();
            // request.open("POST", "route('pay.store') }}");
            // request.send(form_receipt);
        }
    });
</script>
@endsection