@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        /* body {
            font-family: monospace;
        } */

        /* .container {
            width: 100%;
            max-width: 1000px;
            margin: auto;
        } */

        .table_shop {
            width: 100%;
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            table-layout: fixed;
        }

        .table_shop caption {
            font-size: 28px;
            text-transform: uppercase;
            font-weight: bold;
            margin: 8px 0px;
        }

        .table_shop tr {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
        }

        .table_shop th, .table_shop td {
            font-size: 16px;
            padding: 8px;
            text-align: center;
        }

        .table_shop thead th{ 
            text-transform: uppercase;
            background-color: #ffffff67;
        }

        .table_shop tbody tr:hover {
            background-color: rgba(238, 244, 245, 0.3);
        }

        .table_shop tbody td:hover {
            background-color: rgba(206, 200, 200, 0.3);
        }

        @media screen and (max-width: 600px) {
            .table_shop {
                border: 0px;
            }
            .table_shop caption {
                font-size: 22px;
            }
            .table_shop thead {
                display: none;
            }
            .table_shop tr {
                margin-bottom: 8px;
                border-bottom: 4px solid #ddd;
                display: block;
            }
            .table_shop th, .table_shop td {
                font-size: 12px;
            }
            .table_shop td {
                display: block;
                border-bottom: 1px solid #ddd;
                text-align: right;
            }
            .table_shop  td:last-child {
                border-bottom: 0px;
            }
            .table_shop td::before {
                content: attr(data-label);
                font-weight: bold;
                text-transform: uppercase;
                float: left;
            }
        }
     </style>

@endpush

@section('template_title')
    Venta de Articulos
@endsection

@section('content')
    <div class="container-fluid">

    @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('danger'))
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
    @endif

    @if($cash)

        <form id="form_sale" action="{{ route('shop.store') }}" method="POST">
                @csrf    
            <div class="row padding-1 p-1 mb-3">
                <div class="col-md-6">
                    <label for="" style="width:100%;">{{ __('Type of receipt') }}</label>
                    <select name="receipt" class="form-select" aria-label="Default select example" style="width:100%;">
                        <option value="00">Ticket</option>
                        <option value="03">Boleta</option>
                        <option value="01">Factura</option>
                    </select>
                </div>
            
                <div class="col-md-6 mtb-xs">
                    <div class="position-relative">
                        <label for="contact" class="form-label-2">{{ __('Customer') }}</label>
                        <div class="d-flex input-group-sm">
                            <div class="input-group-prepend">
                                <!-- <span class="input-group-text" id="inputGroup-sizing-sm">Small</span> -->
                                <button type="button" id="clean" class="btn btn-primary btn-sm"><ion-icon name="brush-outline"></ion-icon></button>
                            </div>
                            <input type="text" id="term" autocomplete="off" class="form-control-2" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                            <input type="hidden" name="customer_id" id="customer_id">
                            <input type="hidden" name="code" id="code" value="{{ $code }}">
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
                
                <div class="col-md-6 mt-md-3">
                    <label for="" style="width:100%;">Tipo Pago</label>
                    <select name="type_payment" id="type_payment" class="form-select" aria-label="Default select example" style="width:100%;">
                        <option value="1" selected>Contado</option>
                        <option value="2">Credito</option>
                        <!--<option value="01">Factura</option> -->
                    </select>
                    {!! $errors->first('type_payment', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                </div>
            </div>
            
            <div class="row padding-1 p-1 mb-3">     

                <div class="col-md-6">
                    <label for="" style="width:100%;">{{ __('Products') }}</label>
                    <select id="product_id" class="form-select" aria-label="Default select example" style="width:100%;">
                        <option value="">Seleccione productos</option>     
                        @foreach($products as $id => $prod)
                        <option value="{{$id}}">{{$prod}}</option>
                        @endforeach
                    </select>
                </div>
                
                
                <div class="col-md-3 mt-3">
                    <div class="form-group mb-2 mb20">
                        <label for="" class="form-label-2">{{ __('Amount') }}</label>
                        <input type="number" min="1" name="amount" class="form-control-2 @error('amount') is-invalid @enderror" value="" id="amount_form" placeholder="3.00">
                        {!! $errors->first('amount', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}
                    </div>
                </div>

            </div>
            <button id="btn-add" class="btn btn-outline-success mb-3" type="button">{{ __('Add') }}</button>
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

                    <div class="card-body bg-white">
                        <!-- <div class="table-responsive">
                            <table id="table" class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th >Producto</th>
                                        <th >Cantidad</th>
                                        <th >Precio Uni.</th>
                                        <th >Total</th>
                                        <th ></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody1">

                                </tbody>
                            </table>
                        </div> -->

                        <table class="table_shop">
                            <!-- <caption>Consolas</caption> -->
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio uni.</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                                
                            </tbody>
                        </table>
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
                        <span class="badge text-bg-primary rounded-pill" id="total"></span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <button id="btn-generate" class="btn btn-outline-primary" >Generar Comprobante</button>
            </div>
        </div>

    @else
        
        <div class="row padding-1 p-1 mb-3">
            <div class="col-md-6">
                Nesecita abrir caja ... antes de comenzar a generar ventas <a href="" id=""  class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#exampleModal3"><ion-icon name="archive"></ion-icon><ion-icon name="cart-outline"></ion-icon>Abrir Caja</a>
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
                                                <option value="{{ $local->id}}">{{ $local->local_name}}</option>    
                                           
                                            </select>
                                            {!! $errors->first('local_cash', '<div class="invalid-feedback d-block" role="alert"><strong>:message</strong></div>') !!}  
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group mb-4">
                                            <label for="seller" class="form-label-2">{{ __('Usuarios') }}</label>
                                            <select name="seller" id="seller" class="form-control-2 mt-1 line vld draw">
                                                <option value="{{ auth()->id() }}" selected>{{ auth()->user()->name }}</option>
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
    @endif








    </div>

    <style>

    /* .table th:first-child,
    .table td:first-child {
      position: sticky;
      left: 0;
      background-color: #f8f9fa; /* Example background color */
     /* z-index: 1; /* Ensure it stays on top of other content */
    /*} */

    
    </style>
@endsection
@section('script')  
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
 
    <script>
        // window.onload = function() {
        //     const checkboxs = document.querySelectorAll('input[type="checkbox"]');
        //     checkboxs.forEach(function(checkbox) {
        //         checkbox.checked = false;
        //     });
        // };
        window.addEventListener("DOMContentLoaded", function(){

            $('#product_id').select2( {
                theme: 'bootstrap-5'
            } );
            
            // let term = document.getElementById('term');
            // let box = document.getElementById('box-search');
            let table = document.getElementById('table_shop')
            let tb_data = document.getElementById('tbody')
            // let type_payment = document.getElementById('type_payment');
            // let payMethod = document.getElementById('payMethod');
            // let new_pay = document.getElementById('new_pay');
            
            // let clear_btn = document.getElementById('clean');
            let btn_generate = document.getElementById('btn-generate');

            // const simple = document.querySelectorAll('#new_pay input[type=radio]');
            // const simple_number = document.querySelectorAll('#new_pay input[type=number]');
            // const detailed = document.querySelectorAll('#payMethod li input[type=checkbox]');
            // const detailed_number = document.querySelectorAll('#payMethod li input[type=number]');

            let idSelect = null;
            let textSelect = null;
            let priceSelect = null;
            let productos = new Array();
            let radioPay = null;

            let temp_result = {!! $temps !!};
            showResponse(temp_result, 'joder');
       
            // clear_btn.onclick = ()=>{ clean(); } 
            btn_generate.onclick = ()=>{ validar();} 

            // type_payment.selectedIndex = 0;
            payMethod.style.display = "none";
            $('#product_id').change(function(){
                idSelect = $(this).val();
                // alert(idSelect)
                textSelect = $(this).find('option:selected').text();
                let getPrice = textSelect.split(' ').reverse();
                priceSelect  = getPrice[0];
                $('#amount_form').val(1);
                // $('#amount_form').focus();
                // console.log(priceSelect);
            })

        /**SEA4RCH CUSTOMER**** */    
            term.value = 'Seleccione Cliente';
            // customer_id.value = 1;
            

            // term.onclick = function(e){ 
            //     term.value = "";
            //     const url = `/tool/search?customer=`;
            //     if(box.childElementCount > 0){
            //         box.innerHTML = "";
            //         box.style.height = '0px'
            //         return
            //     }
            //     box.style.height = '150px'
            //     search(e, url)
            //     // console.log(box.childElementCount); 
            // }

            // term.addEventListener("keyup", (e)=>{
            //     // console.log(e.type)
            //     const url = `/tool/search?customer=${term.value}`;
            //     search(e, url)
            // })

            // function search(e, url){
            //     // alert(url)
            
            //     fetch(url,{
            //         method: "get",
            //         headers: { 
            //             'Content-Type': 'application/json',
            //         }
            //     })
            //     .then(response => response.json())
            //     .then(data => {
            //         console.log(data);
            //         box.innerHTML = "";
            //         data.forEach(p =>{
            //             box.innerHTML += `<li data-document=${p.document} data-search=${p.id}> ${p.name} </li>`;
            //         })
                    
            //         box.addEventListener("click", function(li) {

            //             term.value = li.target.innerHTML
            //             customer_id.value = li.target.dataset.search
            //             box.innerHTML = "";
            //             box.style.height = '0px'
            //             // customer_doc.value = li.target.dataset.document

            //             if(e.type == 'click'){
            //                 console.log(e.type)
            //                 term.onmousedown = function()
            //                 {
            //                     return false;
            //                 }
            //             }
                        
            //         }, false);
            //     })
            // }
        
            // function clean(){
            //     term.value=''; 
            //     customer_id.value=''
            //     // customer_doc.value = '';
            //     box.innerHTML = "";
            //     box.style.height = '0px'
            //     term.onmousedown = function()
            //     {
            //         return true;
            //     }
            // }
          /***********FIN SEARCH CUSSTOMER********* */

            /***************ADD SALE*************** */
            $("#btn-add").click(function(){

                let qty = $('#amount_form').val();

                if(!$.isNumeric(idSelect) || !$.isNumeric(qty)){
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Debe seleccionar un producto primero o la cantidad",
                    });
                    return 0;
                }
                tb_data.innerHTML=''
                let producto = {code:code.value, id:idSelect, name:textSelect, amount:qty, price:priceSelect}
                productos.push(producto) 
                var data = { order: producto };
                $('#tbody').empty();
                let body = ''
                fetch(`{{ url("add_order") }}`, {
                    method: "POST",
                    headers: { 
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": document.querySelector('input[name=_token]').value
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(datos => {
                    console.log(datos)

                    if(datos.ok){
                        showResponse(datos['orders'], 'new');
                        $('#send-kitchen').prop('disabled', false);
                    }else{
                        console.log(datos)
                    }
                });
                console.log(productos)
                idSelect = null;
                textSelect = null;
                priceSelect = null;
                $('#product_id').val("");
                $('#product_id').change();
                $('#amount_form').val("");
            });
            /***********FIN ADD SALE********* */ 

            /*************ELIMINAR FILA****************** */
            window.eliminarFila = (id) => {
                var data = { id: id };
                let body = ''
                $('#tbody').empty();
                fetch(`{{ url("delete_order") }}`, {
                    method: "POST",
                    headers: { 
                        'Content-Type': 'application/json',
                        "X-CSRF-Token": document.querySelector('input[name=_token]').value
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(datos => {
                    console.log(datos)
                    if(datos.ok){
                        if(datos['orders'].length === 0){
                            tax(0, 'nothy')
                            return 0
                        }
                        showResponse(datos['orders'], 'delete');
                    }else{
                        console.log(datos)
                    }
                });
            }
            /*******************************/

            /***************MODIFICAR AMOUNT*************** */
            window.modifyAmount = async (id, price, op)=>{

                const addBtn = document.getElementById(`btn_add_${id}`);
                const subBtn = document.getElementById(`btn_sub_${id}`);
                addBtn.disabled = true; // Deshabilita el botón
                subBtn.disabled = true; // Deshabilita el botón

                let amount = $("#amount_"+id).text();
                if(op == 'add')
                    amount ++;
                else
                    amount --;

                if(amount < 1){
                    resul = ++amount;
                    $("#amount_"+id).text(resul.toFixed(2));
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No se puede disminuir cantidad",
                    });
                    return 0;
                }

                var data = { id: id, amount: amount }; 
                // console.log(data)
                
                try {
                    response = await fetch(`{{ url("modify_amount") }}`, {
                        method: "POST",
                        headers: { 
                            'Content-Type': 'application/json',
                            "X-CSRF-Token": document.querySelector('input[name=_token]').value
                        },
                        body: JSON.stringify(data)
                    })

                    const dataJson = await response.json();
                    console.log(dataJson)

                    if(dataJson.ok){
                            $("#amount_"+id).text(amount.toFixed(2));
                            $("#operation_"+id).html(price * amount)
                            tax(price * 1, op)
                    }else{
                            alert("no se pudo realizar el cambio de cantidad")
                            resul = (op === 'add' ? --amount : ++amount);
                            $("#amount_"+id).text(resul.toFixed(2));
                    }

                } catch (error) {
                    console.error('Error al obtener los datos:', error);
                } finally {
                    addBtn.disabled = false; // Deshabilita el botón
                    subBtn.disabled = false; 
                }
                // .then(response => response.json()) 
                // .then(datos => {
                //     console.log(datos)
                //     if(datos.ok){
                //         $("#amount_"+id).text(amount.toFixed(2));
                //         $("#operation_"+id).html(price * amount)
                //         tax(price * 1, op)
                //     }else{
                //         alert("no se pudo realizar el cambio de cantidad")
                //         resul = (op === 'add' ? --amount : ++amount);
                //         $("#amount_"+id).text(resul.toFixed(2));
                //     }
                // });   
            }
            /******************************* */

// atAttributeForm(opt)

            // new_pay.onchange = function(ev){
            //     if(ev.target.checked){
            //         radioPay = ev.target.id;
            //         if(ev.target.id === 'radioPay_detail'){
            //             atAttributeForm(2, simple, simple_number, detailed, detailed_number);
            //             payMethod.style.display = "block";
            //             $('#radioMethod1').val(0);
            //             $('#radioMethod2').val(0);
            //         }else{
            //             // console.log(detailed)
            //             cleanPayMethodCheck(detailed, detailed_number);
            //             atAttributeForm(1, simple, simple_number, detailed, detailed_number);

            //             // payMethod.style.display = "none";
            //             if(ev.target.id === 'radioPay1'){
            //                 $('#radioMethod1').val(total);
            //                 $('#radioMethod2').val(0);
            //             }else{
            //                 $('#radioMethod2').val(total);
            //                 $('#radioMethod1').val(0);
            //             }
            //         } 
            //     }
            // }

            // $('#type_payment').change(function(){
            //     let type_pay = $(this).val();
            //     if(type_pay == 2 ){

            //         cleanPayMethodCheck(detailed, detailed_number);
            //         cleanPayMethodRadio(simple, simple_number);
                    
            //         return 0;
            //     }
            //     new_pay.style.display = "block";
            //     // payMethod.style.display = "block";
            // })

            $('#new_pay').change(function(ev){
                radioPay = ev.target.id;
            })

            payMethod.onclick = function(ev){
                // if(ev.target.value){
                // alert("paymethod")
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
                console.log(verbo, ev.target.value, 'hola')
            }

            function validar(){
                let type_payment = $('#type_payment').val();
                let cuenta =  $('.table_shop').find('tbody tr').length;
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

                if(cuenta === 0){
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No se puede guardar la venta .... verifique los productos o datos",
                    });
                    return 0;
                }

                let total =  null;
                let total_pay = null;
                
                if(type_payment == 1 && radioPay === 'radioPay_detail'){
                    
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
                    
                }else if(type_payment == 1 && radioPay !== 'radioPay_detail'){
                    total =  document.getElementById('total').innerHTML;
                    document.querySelectorAll('#new_pay input[type=radio]').forEach((e)=>{
                        if(e.checked === true){
                            total_pay += parseFloat(document.getElementById(`radioMethod${e.value}`).value);
                        }
                    });

                    // console.log(total+ ' ' +total_pay)
                    if(parseFloat(total) !== total_pay){
                        alert("El monto no es igual")
                        return 0;
                    }
                }

                console.log(total+ ' ' +total_pay)
                
                generate_receipt();
            }

            /*++++++++++++++++++ESTA FUNCION SI SIRVE ++++++ OJO*/
            function generate_receipt(){
                // alert("salio")
                let form = document.getElementById('form_sale');
                form.submit();
            }

            
            function showResponse(data, op){
                let body = ''
                total = 0;
                // alert("este problema");
                data.forEach( i =>{
                    console.log(i) 
                    tax(i.price * i.amount, op);
                    body += `<tr>
                            <td data-label="Producto">
                                ${i.name}
                            </td>
                            <td data-label="Cantidad" class="td-amount">
                                <button class="btn btn-outline-secondary btn-amount" id="btn_add_${i.id}" onclick="modifyAmount(${i.id}, ${i.price}, 'add')" style="position:relative;top:2px;"><ion-icon name="add-outline"></ion-icon></button>
                                    <span id="amount_${i.id}">${i.amount}</span>
                                <button class="btn btn-outline-secondary btn-amount" id="btn_sub_${i.id}" onclick="modifyAmount(${i.id}, ${i.price}, 'sub')" style="position:relative;top:2px;"> <ion-icon name="remove-outline"></ion-icon> </button>
                            </td>
                            <td data-label="Precio uni."> 
                                ${i.price}
                            </td>        
                            <td data-label="Total" id="operation_${i.id}">
                            ${i.price * i.amount}
                            </td>
                            <td>
                                <div class="btn-group d-block">
                                    <button type="button" class="btn btn-outline-danger w-100" onclick="eliminarFila(${i.id})"><ion-icon name="trash-outline" style="position:relative;top:3px;left:0px;"></ion-icon></button>  
                                </div>
                            </td>
                        </tr>`; 
                })
                $('#tbody').append(body)  
            }

            function tax(value, op){
                // let total = 0;
                switch (op) {
                    case "nothy":
                        total = 0;
                        break;
                    case "add":
                        total += value; 
                        break;
                    case "sub":
                        total -= value; 
                        break;    
                    default:
                        total += value; 
                }
                igv = total * 0.18;
                subtotal = total - igv;
                $('#total').html(total.toFixed(2))
                $('#subtotal').html(subtotal.toFixed(2))
                $('#igv').html(igv.toFixed(2))
            }
        })

        // function cleanPayMethodCheck(detailed, detailed_number){

        //     detailed.forEach(function(checkElement) {
        //         checkElement.checked = false;
        //     });
        //     detailed_number.forEach(function(checkElement) {
        //         checkElement.value = false;
        //     });
                           
        //     payMethod.style.display = "none";
        // }

        // function cleanPayMethodRadio(simple, simple_number){
        //     // console.log(simple)
        //     simple.forEach(function(radioElement) {
        //         radioElement.checked = false; 
        //     })
            
        //     simple_number.forEach(function(radioElement) {
        //         radioElement.value = false;
        //     });

        //     new_pay.style.display = "none";               
        // }

        // function atAttributeForm(opt, simple, simple_number, detailed, detailed_number){

        //     if(opt == 1){
        //         simple.forEach(function(radioElement) {
        //             radioElement.setAttribute('form', 'form_sale'); 
        //         })
                
        //         simple_number.forEach(function(radioElement) {
        //             radioElement.setAttribute('form', 'form_sale'); 
        //         });

        //         detailed.forEach(function(checkElement) {
        //             checkElement.removeAttribute('form');
        //         });
        //         detailed_number.forEach(function(checkElement) {
        //             checkElement.removeAttribute('form');
        //         });
        //     }else{
        //         simple.forEach(function(radioElement) {
        //             radioElement.removeAttribute('form'); 
        //         })
                
        //         simple_number.forEach(function(radioElement) {
        //             radioElement.removeAttribute('form'); 
        //         });

        //         detailed.forEach(function(checkElement) {
        //             checkElement.setAttribute('form', 'form_sale');
        //         });
        //         detailed_number.forEach(function(checkElement) {
        //             checkElement.setAttribute('form', 'form_sale');
        //         });
        //     }
        // }
    </script>

@endsection