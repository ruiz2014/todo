@extends('layouts.app')

@section('template_title')
    Cocina
@endsection


@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" /> -->
    <link href=" https://cdn.jsdelivr.net/npm/ionicons@7.4.0/dist/collection/components/icon/icon.min.css " rel="stylesheet"> 
    <style>
        /* .mesa{
            border:solid 1px red;
        } */
        img{
            width:100%;
        } 
        .wrapper-mesa{
            border: solid 1px #a7a7ae;
            border-radius: 8px; 
        }
        .wrapper-mesa:hover{
            background: rgb(219, 219, 219);
        }
        .table-tag{
            position: absolute;
            border-radius: 50%;
            color: white;
            background: black;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            font-weight: 600;
        }
        .crono{
            margin:0;
        }
        .center{
            text-align:center;
        }
        .ready td{
            color:#9d9f9f !important;
        }
    </style>
@endpush

<!-- <body class="" onload="miTime();"> -->


@section('content')
    <div class="container mt-4">
        <div class="row">

            @include('partials.audio', ['name'=>'cocina'])
        <!-- <button onclick="speak()">Speak</button> -->
            <div class="table-responsive">
                @csrf
                <table id="mytable" class="table table-bordred table-striped">
                    <thead class="table-dark">
                        <th>Mesa <ion-icon name="checkmark-outline" style="font-size:30px;color:red;"></ion-icon></th>
                        <th>Ambiente</th>
                        <th>Orden</th>
                        <th>Cantidad</th>
                        <th>Nota</th>
                        <th class="text-center">Estado</th>
                    </thead>
                
                    <tbody id="message-tbody">
                @foreach($orders as $order)
                        <tr class="{{ $order->status >= 3 ? 'ready' : '' }}">
                            <td>{{$order->identifier }}</td>
                            <td>{{$order->room }}</td>
                            <td>{{$order->name}}</td>
                            <td>{{$order->amount}}</td>
                            <td>{{$order->note}}</td>
                            <td class="text-center">
                                <button id="{{$order->id}}" data-status="{{$order->status}} " class="status btn btn-outline-info"><ion-icon name="{{ $order->status >= 3 ? 'checkmark-outline' : 'hourglass-outline' }}"></ion-icon></button>
                                <p class="{{ $order->status >= 3 ? '' : 'crono' }}" data-time="{{ $order->created_at }}"></p>
                            </td>
                        </tr>
                @endforeach    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

<!-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="https://unpkg.com/ionicons@latest/dist/ionicons.js"></script>
<script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script> 

<!-- <script>
    function mueveReloj(){
        momentoActual = new Date()
        hora = momentoActual.getHours()
        minuto = momentoActual.getMinutes()
        segundo = momentoActual.getSeconds()

        horaImprimible = hora + " : " + minuto + " : " + segundo

        let elementos = document.querySelectorAll('input[name="reloj"]');
        var numElementos = elementos.length;
        for (var i = 0; i < numElementos; i++) { 
            // if (elementos[i].style.backgroundColor != "red") {
            // elementos[i].style.backgroundColor = "red";
            // break;
            // }
            elementos[i].value = horaImprimible
        }
        // document.form_reloj.reloj.value = horaImprimible

        setTimeout("mueveReloj()",1000)
    }

</script> -->
<script>
    //     let elementos = document.querySelectorAll('input[name="reloj"]');
    //     elementos.forEach(function(element){
    //     element.addEventListener("load", function () {
    //         // element.parentNode.style.background = element.value;
    //     });
    // })
    function siSale(id){
        console.log("a ver");
        alert("salio")
    }

    function miTime(){

/*******************shipping time tiene que guardarse la hora de envio y esa ser leida*********************/

        let elementos = document.querySelectorAll('.crono');
        elementos.forEach(function(element){
            let timeD= element.getAttribute('data-time');
            momentoActual = new Date(timeD)
            // minuto = momentoActual.getMinutes()
            // segundo = momentoActual.getSeconds()
            // horaImprimible = minuto + " : " + segundo

            const endDate = Date.now(); // Current date and time in milliseconds
            // Calculate the time difference in milliseconds
            const timeDifferenceMS = endDate - momentoActual;

            const timeDifferenceSecs = Math.floor(timeDifferenceMS / 1000);
            // const timeDifferenceMins = Math.floor(timeDifferenceMS / 60000);

            // console.log(`Time difference in seconds: ${timeDifferenceSecs}`);
            // console.log(`Time difference in minutes: ${timeDifferenceMins}`);

            // const timeString = `${hours}:${minutes}:${seconds}`;
            // element.addEventListener("load", function () {
            //     let timeD= elemento.getAttribute('data-id');
            //     // element.parentNode.style.background = element.value;
            // });
            // alert(horaImprimible);
            let minut = Math.trunc(timeDifferenceSecs / 60); // 13
            // console.log(minut)
            element.innerText = `${minut} Minutos`;
            setTimeout("miTime()",1000)
            
        })
    }

    

</script>


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



    // import { io } from "https://cdn.socket.io/4.8.1/socket.io.min.js";
    // // const socket = io('https://chapi.nafer.com.pe');
    // const socket = io('http://localhost:3000');
    const socket = io('http://localhost:3000',
    {
        path: "/socket.io",
        transports: ["websocket"],
    });
    
    
    
        const audio = document.querySelector("audio");
        let sound = false;
        let timer;
    // $('#aver').click(function(){
    //     alert("hola");
    //     socket.emit('chat', 'hola este es ')
    // })

    socket.on('kitchen', (msg)=>{
            let body = ''
            //alert('llego')
            // audio.play();
            console.log(msg)
            msg.forEach(p =>{
                body += `<tr>
                            <td>${p.identifier}</td>
                            <td>${p.room}</td>
                            <td>${p.name}</td>
                            <td>${p.amount}</td>
                            <td>${p.note == null ? '' : p.note}</td>
                            <td class="text-center">
                                <button id="${p.id}" class="status btn btn-outline-danger"><ion-icon name="hourglass-outline"></ion-icon></button>
                                <p class="crono" data-time="${p.created_at}"></p>
                            </td> 
                        </tr>`;
            })
            $('#message-tbody').prepend(body)
            miTime()
            speak()

            let bolas = { title: "Este es mi texte", message: "llego un maldito plato de la sala.." };
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

    $(document).on('click', '.status', function(){
        let temp_id = $(this).attr('id')
        
        var data = { id: temp_id };
        fetch(`dish_ready`, {
            method: "POST",
            headers: { 
                'Content-Type': 'application/json',
                "X-CSRF-Token": document.querySelector('input[name=_token]').value
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(datos => {
            // console.log(datos)
            if(datos.ok){
                socket.emit('hall', datos)
                location.reload();
            }
            
        })
        alert(temp_id);
    })

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

    // function eliminarFila(id, status){
    //     alert("Joder tio");
    // }

    // function updateClock() {
    //     const now = new Date();
    //     const hours = String(now.getHours()).padStart(2, "0");
    //     const minutes = String(now.getMinutes()).padStart(2, "0");
    //     const seconds = String(now.getSeconds()).padStart(2, "0");

    //     const timeString = `${hours}:${minutes}:${seconds}`;

    //     document.getElementById("clock").innerText = timeString;
    // }

    // setInterval(updateClock, 1000);

    // function mueveReloj(){
    //     momentoActual = new Date()
    //     hora = momentoActual.getHours()
    //     minuto = momentoActual.getMinutes()
    //     segundo = momentoActual.getSeconds()

    //     horaImprimible = hora + " : " + minuto + " : " + segundo

    //     // let elementos = document.querySelectorAll('input[name="reloj"]');
    //     // var numElementos = elementos.length;
    //     // for (var i = 0; i < numElementos; i++) { 
    //     //     // if (elementos[i].style.backgroundColor != "red") {
    //     //     // elementos[i].style.backgroundColor = "red";
    //     //     // break;
    //     //     // }
    //     //     elementos[i].value = horaImprimible
    //     // }
    //     document.form_reloj.reloj.value = horaImprimible

    //     setTimeout("mueveReloj()",1000)
    // }
    


</script>           
@endpush