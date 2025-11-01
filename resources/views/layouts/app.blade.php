<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if (trim($__env->yieldContent('template_title'))) @yield('template_title') | @endif {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @stack('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script src="{{ asset('js/main/app.js') }}" defer></script>
<style>
    svg{
        width:20px;
    }
    button ion-icon, a ion-icon{
        /* font-size:20px; */
        position: relative;
        top: 2px;
    }
    input[name="search"] ~ button ion-icon {
        padding: 0px 20px;
    }


    .loader {
        transform: rotateZ(45deg);
        perspective: 1000px;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        color: #fff;
      }
        .loader:before,
        .loader:after {
          content: '';
          display: block;
          position: absolute;
          top: 0;
          left: 0;
          width: inherit;
          height: inherit;
          border-radius: 50%;
          transform: rotateX(70deg);
          animation: 1s spin linear infinite;
        }
        .loader:after {
          color: #FF3D00;
          transform: rotateY(70deg);
          animation-delay: .4s;
        }

        .caja_loader{
            position: relative;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 9999;
            display:none;
            display: flex;
            justify-content: center;
            align-items: center;
        }

      @keyframes rotate {
        0% {
          transform: translate(-50%, -50%) rotateZ(0deg);
        }
        100% {
          transform: translate(-50%, -50%) rotateZ(360deg);
        }
      }

      @keyframes rotateccw {
        0% {
          transform: translate(-50%, -50%) rotate(0deg);
        }
        100% {
          transform: translate(-50%, -50%) rotate(-360deg);
        }
      }

      @keyframes spin {
        0%,
        100% {
          box-shadow: .2em 0px 0 0px currentcolor;
        }
        12% {
          box-shadow: .2em .2em 0 0 currentcolor;
        }
        25% {
          box-shadow: 0 .2em 0 0px currentcolor;
        }
        37% {
          box-shadow: -.2em .2em 0 0 currentcolor;
        }
        50% {
          box-shadow: -.2em 0 0 0 currentcolor;
        }
        62% {
          box-shadow: -.2em -.2em 0 0 currentcolor;
        }
        75% {
          box-shadow: 0px -.2em 0 0 currentcolor;
        }
        87% {
          box-shadow: .2em -.2em 0 0 currentcolor;
        }
      }
</style>

</head>
<body>
    <!-- <div id="app"> -->
    <div class="caja_loader" id="loader">
        <span class="loader"></span>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">

                        <a class="nav-link" aria-current="page" href="{{ url(\App\Helpers\CompanyHelper::getSector()['url']) }}">
                            {{ \App\Helpers\CompanyHelper::getSector()['name'] }}
                        </a>
                    </li>

                    <li class="nav-item">
                         <a class="nav-link" aria-current="page" href="{{ route('quotes.index') }}">
                           Cotizacion
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administracion Local
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('lp.index') }}">Productos</a></li>
                            <li><a class="dropdown-item" href="{{ route('customers.index') }}">Cliente</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('credits.index') }}">Credito por Cobrar</a></li>
                            <li><a class="dropdown-item" href="{{ route('cashes.index') }}">Caja</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Staff
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                            <li><a class="dropdown-item" href="{{ route('users.index') }}">Usuarios</a></li>
                            <li><a class="dropdown-item" href="{{ route('buy-products.index') }}">Compras</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('warehouses.index') }}">Almacenes</a></li>
                            <li><a class="dropdown-item" href="{{ route('locals.index') }}">Locales</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Productos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}">Producto</a></li>
                            <li><a class="dropdown-item" href="{{ route('categories.index') }}">Categorias</a></li>
                            <!-- <li><a class="dropdown-item" href="oute('providers.index') }}">Proveedores</a></li> -->
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li> 


                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Atenciones
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('attentions.index', ['type' => 'ticket']) }}">Ticket</a></li>
                            <li><a class="dropdown-item" href="{{ route('attentions.index', ['type' => 'boleta']) }}">Boletas</a></li>
                            <li><a class="dropdown-item" href="{{ route('attentions.index', ['type' => 'factura']) }}">Facturas</a></li>
                            <li><a class="dropdown-item" href="{{ route('summary.index') }}">Resumenes</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Reportes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('report.date') }}">Ventas</a></li>
                            <li><a class="dropdown-item" href="{{ route('attentions.index', ['type' => 'boleta']) }}">X</a></li>
                            <li><a class="dropdown-item" href="{{ route('attentions.index', ['type' => 'factura']) }}">X</a></li>
                            <li><a class="dropdown-item" href="{{ route('summary.index') }}">X</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span id="bell" class="d-block" style="height:20px;width:20px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell" style="height:23px;width:23px;">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                        </span>
                        <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                            <span class="visually-hidden">New alerts</span>
                        </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="">a new moth have a grated offert de sale buy garage becouse somos product man</a> 
                            <div>a ver</div>
                            <div>aver</div>
                        </div>
                        <!-- <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul> -->
                    </li>
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="btn btn-outline-success userw rounded-circle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                           {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow position-absolute profile" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <!-- <a class="dropdown-item" href="#">Cerrar Session</a> -->
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest    
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
    <!-- </div> -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/ionicons@latest/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>   
    
    <script>

        $('.caja_loader').hide();
            $('.dropdown-item, a[aria-current="page"], .btn').click(function(){
                $('.caja_loader').show();
                setTimeout(()=>{ mostrar()}, 500);
        });

        function mostrar(){
            // alert("hola")
            $('.caja_loader').hide();
        }

        window.addEventListener('load', cerrarLoader);

        function cerrarLoader(){
             //alert("hola");
            let loader = document.getElementById('loader');
            loader.style.display = 'none';
        }

    @isset($algo)

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
    @yield('notification')  
    @isset($noty)
     

        let title = 'Notificacion';
        let message = 'Tiene una Notificacion';
        
        @if(session('notification') == 4)
            
            alert('{{ $noty->notes }}');
           
        @endif

        @switch(session('notification'))
            @case(1)
                title = '{{ $noty->title }}';
                message = '{{ $noty->notes }}';
                @break

            @case(4)
                title = '{{ $noty->title }}';
                message = '{{ $noty->notes }}';
                @break

            @default
                // alert("este es ")
                title = '{{ $noty->title }}';
                message = '{{ $noty->notes }}';
        @endswitch
        

        document.addEventListener('DOMContentLoaded', function() {
        const bell = document.getElementById('bell');
                // const socket = io('http://localhost:3000',
                // {
                //     path: "/socket.io",
                //     transports: ["websocket12"],
                // });
            
        socket.on('chat', (msg)=>{
            bell.classList.add('bell-item');
        })

                /*****************ORIGINAL DESCOMENTAR*************** */
                // fetch(`http://localhost:3000/send-notification`, {
                // // method: "POST",
                //     // headers: { 
                //     //     'Content-Type': 'application/json',
                //     // }
                //     // mode: 'no-cors',
                //     headers: { 'Content-Type': 'application/json','Accept':'application/json' },
                //     redirect: 'follow'
                //     // body: JSON.stringify(data2)
                // })
                // .then(response => response.json())
                // .then(datos => {
                //     console.log(datos)
                // });

        let bolas = { title: title, message: message };
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
            // setTimeout(function(){
            //     window.location.reload();
            // }, 4000);
        @endisset
    @endisset


    </script>
     
    
    @stack('scripts')
    @yield('script')
    <!-- if (session('n__otification'))
    
    ript src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>   
        y__ield('notification')
        
        

    </script>
    ndif    -->
</body>
</html>
