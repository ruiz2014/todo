@extends('layouts.app')

@section('template_title')
    Restaurant
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" /> -->
@endpush

@section('content')

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

    @include('partials.audio', ['name'=>'mesero'])

    <ul class="nav nav-tabs wrapper-tabs" id="myTab" role="tablist" style="">
        @foreach($rooms as $room)
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $room?->id == 1 ? 'active':'' }}" id="{{ preg_replace('/\s+/', '', $room?->name)}}-tab" data-bs-toggle="tab" data-bs-target="#{{ preg_replace('/\s+/', '', $room?->name)}}" type="button" role="tab" aria-controls="{{ preg_replace('/\s+/', '', $room?->name)}}" aria-selected="true">{{ $room?->name }}</button>
        </li>
        @endforeach
    </ul>
    <div class="tab-content wrapper-tabs" id="myTabContent" style="">
        @foreach($rooms as $room)
        <div class="tab-pane fade {{ $room?->id == 1 ? 'show active':'' }}" id="{{ preg_replace('/\s+/', '', $room?->name)}}" role="tabpanel" aria-labelledby="{{ preg_replace('/\s+/', '', $room?->name)}}-tab">
            <h4 class="text-capitalize text-center mb-3">{{ $room->name }}</h4>
            <div class="mierda"> 
            @foreach($tables as $table) 
                @if($room?->id == $table?->room_id)
                <div class="col-3 col-lg-2 col-xl-2 mesa1 p-2 p-sm-3">
                    <span class="table-tag">{{ $table?->identifier }}</span>
                    <div class="wrapper-mesa p-2 p-md-3 shadow btnModal" id="{{ $table?->id }}">
                        <img src="img/table.png" alt="">
                    </div>
                </div> 
                @endif
            @endforeach   
            </div>     
        </div>
        @endforeach
    </div>

    <div class="container">
        <div class="row">

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
              <div id="qrcode" class="p-4"></div>
              <div class="modal-body">
              @csrf
                <div class="mb-3">
                    <label for="" style="width:100%;">Platillos</label>
                    <select id="dishe" class="form-select dishes-select" aria-label="Default select example" style="width:100%;">  
                        <option value="">Seleccione platillo</option>    
                        @foreach($dishes as $id => $dishe)
                        <option value="{{$id}}">{{$dishe}}</option>
                        @endforeach
                    </select>
                </div> 
                <div class="mb-3">
                    <label for="" style="width:100%;">Bebidas</label>
                <select id="drink" class="form-select drinks-select" aria-label="Default select example" style="width:100%;">
                    <option value="">Seleccione bebida</option>     
                    @foreach($drinks as $id => $drink)
                    <option value="{{$id}}">{{$drink}}</option>
                    @endforeach
                </select>
                </div> 
                <div class="mb-3">
                    <label for="" style="width:100%;">Guarniciones</label>
                <select id="fitting" class="form-select fittings-select" aria-label="Default select example" style="width:100%;">
                    <option value="">Seleccione guarnision</option>     
                    @foreach($fittings as $id => $fitting)
                    <option value="{{$id}}">{{$fitting}}</option>
                    @endforeach
                </select>
                </div> 
                <div class="mb-3">
                    <label for="" style="width:100%;">Otros</label>
                    <select id="other" class="form-select others-select" aria-label="Default select example" style="width:100%;">
                        <option value="">Seleccione otros</option>     
                        @foreach($others as $id => $other)
                        <option value="{{$id}}">{{$other}}</option>
                        @endforeach
                    </select>
                </div> 

                <div class="mb-4">
                    <input type="hidden" name="" id="in_use">
                    <button id="btn-add" class="btn btn-outline-dark">Agregar <ion-icon name="restaurant-outline" style="position:relative;top:3px;left:3px;"></ion-icon></button>
                </div>

                <table class="table" style="width: 100%;">
                    <thead class="table-dark">
                        <tr>
                            <th>Articulo</th>
                            <th>Cantidad</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>

                <div class="row">
                    <div class="col-12">
                        <label for="" style="width:100%;">Anotacion General</label>
                        <textarea name="" id="" style="width:100%;"></textarea>
                    </div>
                </div>
                <button class="btn btn-outline-success mt-4" id="send-kitchen">Enviar a cocina <ion-icon name="bonfire-outline" style="position:relative;top:3px;left:3px;"></ion-icon></button>
              </div>
              <div class="modal-footer">
                <a id="vemos" class="btn btn-outline-info">Imprimir</a>
                <button id="show_debt" class="btn btn-outline-info">Mostrar QR</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="{{ route('finalizeOrder') }}" method="post">
                    @csrf
                    <input type="hidden" name="order_table" value="" id="order">
                    <button type="submit" class="btn btn-outline-primary" id="finalize_order" >Save changes</button>
                </form>
                
              </div>
            </div>
          </div>
        </div>

    <style>
        .occupied{
            background:#bf0707;
        }
    </style>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>
    <script src="https://unpkg.com/ionicons@latest/dist/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>  


    <script>

        const checkPermission = ()=>{
            if((!'serviceWorker' in navigator)){
                throw new Error('se fue todo a la mierda')
            }

            if(!('Notification' in window)){
                throw new Error('no aguanta nada');
            }
        }

        const registerSw = async () =>{
            const registration  = await navigator.serviceWorker?.register("{{ asset('js/serviceworker.js') }}");
            return registration;
        }

        const requestNotificationPermision = async () =>{
                const permission = await Notification.requestPermission();
                
                if(permission !== 'granted'){
                    throw new Error('Notification no pasa nada');
                }
                    // else{
                    //     new Notification("hello word")
                    // }
            }

        const main = async () => {
            // alert("salio");
            checkPermission();
            await registerSw();
            await requestNotificationPermision();
            // reg.showNotification("hello wordl");
        } 

        main();













        const socket = io('http://localhost:3000',
        {
            path: "/socket.io",
            transports: ["websocket"],
        });
        // alert("esto es una gran mierda sabes eso")
        // socket.emit('hall', "Se envio un orden a cocina .....");


        let finalize = document.getElementById('finalize_order');
        const btn = document.querySelectorAll(".btnModal");
        const modalRegistro = document.querySelector("#exampleModal");
        const myModal = new bootstrap.Modal(modalRegistro);
        let table = document.getElementById('table')
        let tb_data = document.getElementById('tbody')
        let qrDebt = document.getElementById("qrcode")
        let idSelect = null;
        let textSelect = null;
        let priceSelect = null;

        let productos = new Array();  //<--- BORRARRRRRRRRRRRRRR
        let obj = {}
        let total = 0;

        ocuped();

        socket.on('hall', (msg)=>{
            alert("llego algo de cocina")
            // audio.play();
            speak()
            let bolas = { title: "Este es mi texte", message: "tienes un plato para recoger de cocina.." };
            fetch('http://localhost:3000/send-kitchen', {
                method: "POST",
                headers: { 
                    'Content-Type': 'application/json',
                                // 'Accept':'application/json'
                },
                        // mode: 'no-cors',
                redirect: 'follow',
                body: JSON.stringify(bolas)
                })
                .then(response => response.json())
                .then(datos => {
                    console.log(datos)
                            // location.reload();
                });
        })

        finalize.onclick = ()=>{ validar();}

        function validar(){

            generate_receipt();
        }

        /*++++++++++++++++++ESTA FUNCION SI SIRVE ++++++ OJO*/
        function generate_receipt(){
                // alert("salio")
            let form = document.querySelector('form');
            socket.emit('box', 'LLego algo para que vea su pago')
            form.submit();
        }



        btn.forEach(mod => {
            mod.addEventListener("click",function(e){
                e.preventDefault()
                $('#send-kitchen').prop('disabled', true);
                $('#finalize_order').prop('disabled', true);
                $('.modal-body').show();
                $('#qrcode').hide();
                let table = $(this).attr("id");//e.target.getAttribute("id");
                $('#in_use').val(table);
                $('#order').val(table);
                $('#show_debt').hide();
                try {
                    $('#tbody').empty();
                        let body = ''
                        var data = { table: table };
                        fetch(`check`, {
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
                                showResponse(datos['orders']);
                                if(datos['sign']){
                                    $('#finalize_order').prop('disabled', false);
                                    $('#show_debt').show();
                                    $('#vemos').attr('href', link+'/'+datos['code']+'/2'); 
                                }
                            }
                            else{
                                $('#send-kitchen').prop('disabled', true);
                                console.log(datos)
                            }
                        });
                } catch (err) {
                    console.log("Error al realizar la petición AJAX: " + err.message);
                }

                myModal.show(); 
            })
        })

        $('.form-select').change(function(){
            idSelect = $(this).val();
            textSelect = $(this).find('option:selected').text();
            let getPrice = textSelect.split(' ').reverse();
            priceSelect  = getPrice[0];
        })

        $("#btn-add").click(function(){
            $('#finalize_order').prop('disabled', true);
            $('#show_debt').hide();
            tb_data.innerHTML=''
            let table = $('#in_use').val();
            alert(table)
            let producto = {table:table, id:idSelect, name:textSelect, cantidad:1, price:priceSelect}
            total += producto.price * producto.cantidad 
            productos.push(producto) //<--- BORRARRRRRRRRRRRRRR
            var data = { order: producto };
            $('#tbody').empty();
            let body = ''
            fetch(`add_order_restaurant`, {
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
                    showResponse(datos['orders']);
                    $('#send-kitchen').prop('disabled', false);
                    $("#"+table).addClass('occupied')
                }else{
                    console.log(datos)
                }
            });

            console.log(productos) //<--- BORRARRRRRRRRRRRRRR
            idSelect = null;
            textSelect = null;
            priceSelect = null;

            $('#dishe').val("")
            $('#dishe').change()
            $('#drink').val("")
            $('#drink').change()
            $('#fitting').val("")
            $('#fitting').change()
            $('#other').val("")
            $('#other').change()
        });

        const modifyAmount = (id, op) =>{

            let amount = $("#amount_"+id).text();
                if(op == 'add')
                    amount ++;
                else
                    amount --;

            var data = { id: id, amount: amount }; 
            fetch(`modify_amount_restaurant`, {
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
                    $("#amount_"+id).text(amount.toFixed(2));
                }else{
                    alert("no se pudo aumentar ")
                    resul = (op === 'add' ? --amount : ++amount);
                    $("#amount_"+id).text(resul.toFixed(2));
                }
            });   
        }

        const eliminarFila = (id) => {
            var data = { id: id };
            $('#tbody').empty();
            let body = ''
            fetch(`delete_order_restaurant`, {
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
                        $('#send-kitchen').prop('disabled', true);
                        return 0
                    }
                    showResponse(datos['orders']);
                    if(datos['sign']){
                        $('#finalize_order').prop('disabled', false);
                        $('#show_debt').show();
                    }
                }else{
                    console.log(datos)
                }
            });
        }

        function showResponse(data){
            let body = ''
            data.forEach( i =>{
                console.log(i) 
                body += `<tr>
                         <td>
                             ${i.name}
                             <p>${i.price}</p>
                         </td>
                         <td class="td-amount">
                            ${i.status == 1 ? `<button class="btn btn-outline-secondary btn-amount" id="btn_add_${i.id}" onclick="modifyAmount(${i.id}, 'add')" style="position:relative;top:2px;"><ion-icon name="add-outline"></ion-icon></button>
                                                <span id="amount_${i.id}">${i.amount}</span>
                                            <button class="btn btn-outline-secondary btn-amount" onclick="modifyAmount(${i.id}, 'sub')" style="position:relative;top:2px;"> <ion-icon name="remove-outline"></ion-icon> </button>`
                            : 
                            `<span id="amount_${i.id}">${i.amount}</span>`}
                         </td>
                         <td style="width:10%;">         
        ${i.status == 1 ? `<div class="btn-group">
                            <button type="button" class="btn btn-outline-danger" onclick="eliminarFila(${i.id})"><ion-icon name="trash-outline" style="position:relative;top:3px;left:0px;"></ion-icon></button>
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                <a class="dropdown-item" href="#">Nota</a>
                                    <textarea id="note_${i.id}" style="width:315px;padding:2px;"></textarea>
                                    <br>
                                    <button type="button" onclick="addNote(${i.id})">Enviar</button>
                                </li>
                            </ul>
                        </div>` 
        : 
        i.status == 2 ? '<ion-icon name="hourglass-outline"></ion-icon>'
        : 
        i.status == 3 ? '<ion-icon name="checkmark-outline" style="font-size:40px;color:green;"></ion-icon>' : ""}
                        </td>
                     </tr>`;
                 if(i.status == 1) {
                     $('#send-kitchen').prop('disabled', false); 
                     console.log("esto salio");
                 }   
            })
            $('#tbody').append(body)
        }

         $('#send-kitchen').click(function(){
            
            try {
                    let body = ''
                    let table = $('#in_use').val();
                    var data = { table: table };
                    fetch(`send_kitchen`, {
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
                            $('#tbody').empty();
                            showResponse(datos.orders);
                            socket.emit('kitchen', datos.sendOrders)
                            // // socket.emit('new_message', { 
                            // //     name: data.name,
                            // //     email: data.email,
                            // //     subject: data.subject,
                            // //     created_at: data.created_at,
                            // //     id: data.id
                            // // });
                            $('#send-kitchen').prop('disabled', true);
                        }
                        else{
                            console.log(datos)
                        }
                        $('#exampleModal').modal('hide');
                    });
                } catch (err) {
                    console.log("Error al realizar la petición AJAX: " + err.message);
                }

        });

        function ocuped(){
            fetch(`check_occupied`, {
                method: "POST",
                headers: { 
                    'Content-Type': 'application/json',
                    "X-CSRF-Token": document.querySelector('input[name=_token]').value
                },
                // body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(datos => {
                    datos.tables.forEach( i =>{
                        $("#"+i.table_id).addClass('occupied')
                        console.log(i.table_id);
                    })
                })
        }

        function speak() {
            //alert("Hola");
            // // Create a SpeechSynthesisUtterance
            //const utterance = new SpeechSynthesisUtterance("Welcome to this tutorial!");

            // // Select a voice
            //const voices = speechSynthesis.getVoices();
            //utterance.voice = voices[0]; // Choose a specific voice

            // // Speak the text
            //speechSynthesis.speak(utterance);
            const speech = new SpeechSynthesisUtterance("la mesa 7 tiene un plato listo para recoger de cocina..");
            speech.volume = 1;
            // speech.rate = 0.8;
            // speech.pitch = 0.2;
            speech.lang = 'es-ES'
            var timer = setInterval(() => {
                var voices = speechSynthesis.getVoices();
                if(voices.lenght != 0){
                   speech.voice = voices[0];
                    // speech.voiceURI = voices[0].voiceURI;
                   clearInterval(timer);
                }
            }, 1000);

            window.speechSynthesis.speak(speech);
        }

    </script>
@endpush

<!-- </body>
</html> -->