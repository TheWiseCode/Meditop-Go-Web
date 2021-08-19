<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Receta medica</title>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{--<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous">

    <style>
        html {
            font-family: "Nunito", sans-serif;
        }

        main {
            margin: 2.5cm 1cm 2.5cm 1cm;
            font-size: 13px;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 2cm;
            text-align: center;
            line-height: 30px;
            font-size: 15px;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2cm;
            text-align: center;
            line-height: 35px;
            font-size: 15px;
        }

        @page {
            margin: 0 0;
            font-size: 13px;
        }
    </style>
</head>
<body>
<header class="bg bg-primary">
    <div class="m-4">
        <p class=""><strong>Receta medica</strong></p>

    </div>
</header>
<main class="py-4 text-center">
    <label class="h4"><strong>Doctor: </strong>{{$doctor->name.' '.$doctor->last_name}}</label><br><br>
    <label class="h4"><strong>Fecha recetada: </strong>{{$receta[0]->time}}</label><br><br>
    <label><strong>Medicamentos recetados</strong></label><br>
    <ul class="list-group list-group-flush">
        @foreach($receta as $rec)
            <li class="list-group-item">{{$rec->name}}, {{$rec->dose}}
                {{$rec->concentration}}, {{$rec->detail}}</li>
        @endforeach
    </ul>
</main>
</body>
</html>
