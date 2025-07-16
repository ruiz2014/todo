<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">
   <!-- <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"> -->
</head>
<body>
    <div class="container">
        <div class="row">
			<div class="col-md-5 mx-auto">
                <div id="first">
                    <div class="myform form">
                        <div class="logo">
                            <div class="col-md-12 text-center">
                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ92l-724Mv6JJ4Fb8YIGeEQsa4EUDxuqtlgHkY8LBwYUUc4Lx7aevZwG2ChtsBpUMjxFM&usqp=CAU" alt="">
                            </div>
                        </div>
                        <form class="col m12 l6" method="POST" action="{{ route('auth.login') }}" name="login">
                        @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Usuario</label>
                                <input type="email" name="email"  class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                                    @error("password")
                                        <span class="red-text text-darken-1">** {{ $message }} **</span>
                                    @enderror
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Contraseña</label>
                                <input type="password" name="password" id="password"  class="form-control" aria-describedby="emailHelp" placeholder="Enter Password">
                                    @error("password")
                                        <span class="red-text text-darken-1">** {{ $message }} **</span>
                                    @enderror
                            </div>
                            
                            <div class="col-md-12 text-center ">
                                <button type="submit" class=" btn btn-block  btn-new tx-tfm">Login</button>
                            </div>
                            
                        </form>
                    
                    </div>
                </div>
			</div>
		</div>
    </div> 
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function (registrations) {
                //returns installed service workers
                if (registrations.length) {
                for(let registration of registrations) {
                    registration.unregister();
                }
                }
            });
        }
    </script>
</body>
</html>