<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">

                        <div class="card-body">

                            <div class="form-group">
                                <strong>Gracias por registrarte!</strong><br>
                                Antes de comenzar, ¿podría verificar su dirección de correo electrónico haciendo clic en
                                el
                                enlace que le acabamos de enviar?<br>
                                Si no recibió el correo electrónico, con gusto le enviaremos otro.
                            </div>

                            <div class="form-group row">
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <div class="col-md-auto">
                                        <button type="submit" class="btn btn-primary">
                                            Reenviar correo de verificación
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <div class="col-md-auto">
                                        <button type="submit"
                                                class="btn btn-link text-secondary">
                                            <u>Cerrar sesión</u>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>

