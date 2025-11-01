
@extends('layouts.app')

@section('template_title')
    Caja
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('partials.audio', ['name'=>'cajero'])
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
                        <button onclick="speak()">Speak</button>
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
                                <tbody id="message-tbody">
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
@push('scripts')
    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>  
    <script>
        const socket = io('http://localhost:3000',
        {
            path: "/socket.io",
            transports: ["websocket"],
        });

        socket.on('box', (msg)=>{
            alert("llego algo de cocina")
            audio.play();
            setTimeout(function() {
            location.reload();
            }, 3000);
            // location.reload();

            // let body = ''
            // console.log(msg)
            // msg.forEach(p =>{
            //     body += `<tr>
            //                 <td>1</td>
            //                 <td>${p.identifier}</td>
            //                 <td>${p.name}</td>
            //                 <td>${p.status}</td>
            //                 <td class="text-center">
            //                     <a class="btn btn-sm btn-primary " href="ute('pay.show', ['order'=> ${p.code}]) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
            //                 </td> 
            //             </tr>`;
            // })
            // $('#message-tbody').prepend(body)
        })

        function speak() {
            alert("Hola");
            // // Create a SpeechSynthesisUtterance
            // const utterance = new SpeechSynthesisUtterance("Welcome to this tutorial!");

            // // Select a voice
            // const voices = speechSynthesis.getVoices();
            // utterance.voice = voices[0]; // Choose a specific voice

            // // Speak the text
            // speechSynthesis.speak(utterance);

            const speech = new SpeechSynthesisUtterance("hola esto es un ejemplo de texto ....");
            speech.volume = 1;
            // speech.rate = 0.8;
            // speech.pitch = 0.2;
            speech.lang = 'es-ES'
            var timer = setInterval(() => {
                var voices = speechSynthesis.getVoices();
                if(voices.lenght != 0){
                   speech.voice = voices[0];
                   speech.voiceURI = voices[0].voiceURI;
                   clearInterval(timer);
                }
            }, 1000);

            window.speechSynthesis.speak(speech);
        }

    </script>
@endpush

