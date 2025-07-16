<script>
    const title = 'Uno';
    const message = 'Esto es un mensaje';
   
    document.addEventListener('DOMContentLoaded', function() {
        const bell = document.getElementById('bell');
        socket.on('chat', (msg)=>{
            bell.classList.add('bell-item');
        })


        let bolas = { id:"1", message: "hola que tal .... vete a la mierda" };
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
        });
    })    
        
</script>