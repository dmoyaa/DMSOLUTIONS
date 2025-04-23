<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{secure_asset("css/login.css")}}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
@if(session('status'))
    <script>
        alert("{{session('status')}}")
    </script>
@endif
<div class="form">

    <div class="tab-content">
        <!-- REGISTRO -->
        <!-- INICIO DE SESIÓN -->
        <div id="login" class="active">
            <h1>¡Bienvenid@ de Nuevo!</h1>
            <form action="{{route('login_validation')}}" method="post">
                @csrf
                <div class="field-wrap">
                    <label>Correo Electrónico<span class="req">*</span></label>
                    <input type="email" name="email" required autocomplete="off">
                </div>

                <div class="field-wrap">
                    <label>Contraseña<span class="req">*</span></label>
                    <input type="password" name="password" required autocomplete="off">
                </div>

                <button class="button button-block" >Iniciar Sesión</button>
            </form>
        </div>
    </div>
</div>
<script>
    @isset($user)
        @if($user == null || "")
            alert("Usuario no encontrado")
            window.location.href = "{{route("login")}}";
        @else
            window.location.href = "{{route("home")}}";
        @endif
    @endisset
</script>

</body>
</html>
