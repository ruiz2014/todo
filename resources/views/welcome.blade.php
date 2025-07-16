<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <button id="botones">Enviar</button>
    <button onclick="main()">Este es el main</button>
    <button id="doble">La Prueba Final A ver si lloro</button>
    <p id="prueba"></p>
    <script src="https://cdn.socket.io/4.8.1/socket.io.min.js"></script>    
    <script>
        let aver = document.getElementById('prueba');
        let btn = document.getElementById('botones');
        let doble = document.getElementById('doble');
        const socket = io('http://localhost:3000',
        {
            path: "/socket.io",
            transports: ["websocket"],
        });

        btn.addEventListener('click', function(){
            alert("salio ...");
            socket.emit('chat', "hola como estan");
        })

        socket.on('chat', (msg)=>{
            aver.textContent = msg;
        })

        socket.on("connect_error", (err) => {
            // the reason of the error, for example "xhr poll error"
            console.log(err.message);

            // some additional description, for example the status code of the initial HTTP response
            console.log(err.description);

            // some additional context, for example the XMLHttpRequest object
            console.log(err.context);
        });


        doble.addEventListener('click', function(){
            alert("doble fecth");
            let data = { table: 1};
            let data2 = { table2: 2};
            fetch(`http://localhost:3000/prueba-fuego`, {
                method: "POST",
                headers: { 
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(datos => {
                console.log(datos)
                socket.emit('chat', "hola como estan este debe ser otro mensaje para saber si salio");
            });

            // fetch(`http://localhost:3000/prueba-fuego2`, {
            fetch(`http://localhost:3000/send-notification`, {
                // method: "POST",
                headers: { 
                    'Content-Type': 'application/json',
                }
                // body: JSON.stringify(data2)
            })
            .then(response => response.json())
            .then(datos => {
                console.log(datos)
            });
        });


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
            alert("salio");
            checkPermission();
            await registerSw();
            await requestNotificationPermision();
            // reg.showNotification("hello wordl");
        }    

    </script>
</body>
</html>