<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
            integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"
            integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"
            integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

    <style>
        section {
            padding-top: 100px;
        }

        .form-section {
            padding-left: 15px;
            display: none;
        }

        .form-section.current {
            display: inherit;
        }

        .btn-primary, .btn-success {
            margin-top: 10px;
        }

        .parsley-errors-list {
            margin: 2px 0 3px;
            padding: 0;
            list-style-type: none;
            color: red;
        }
    </style>
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

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{__('Log in')}}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{__('Register')}}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Cerrar Sesion
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Registrarse</div>
                        <div class="card-body">
                            <form class="contact-form form-group" method="post" action="{{ route('register') }}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-section">
                                    <div class="form-group row">
                                        <label for="name"
                                               class="col-md-4 col-form-label text-md-right">Nombre</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="name"
                                                   value="{{old('name')}}"
                                                   required autocomplete="name" autofocus>
                                        </div>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <label for="last_name"
                                               class="col-md-4 col-form-label text-md-right">Apellidos</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="last_name"
                                                   value="{{old('last_name')}}"
                                                   required autocomplete="last_name" autofocus>
                                        </div>
                                        @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <label for="ci"
                                               class="col-md-4 col-form-label text-md-right">Carnet Identidad</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="ci"
                                                   value="{{old('ci')}}"
                                                   required autocomplete="ci" autofocus>
                                        </div>
                                        @error('ci')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <label for="cellphone"
                                               class="col-md-4 col-form-label text-md-right">Celular</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="cellphone"
                                                   value="{{old('cellphone')}}"
                                                   required autocomplete="cellphone" autofocus>
                                        </div>
                                        @error('cellphone')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <label for="birthday"
                                               class="col-md-4 col-form-label text-md-right">Fecha de nacimiento</label>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" name="birthday"
                                                   value="{{old('birthday', '2000-01-01')}}" max="2003-01-01"
                                                   required autocomplete="birthday" autofocus>
                                        </div>
                                        @error('birthday')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <label for="sex"
                                               class="col-md-4 col-form-label text-md-right">Genero</label>
                                        <div class="col-md-6">
                                            <select name="sex" id="" class="form-control" required>
                                                <option selected disabled>Seleccione su genero</option>
                                                <option @if(old('sex') == 'M') selected @endif
                                                value="M">Masculino
                                                </option>
                                                <option @if(old('sex') == 'F') selected @endif
                                                value="F">Femenino
                                                </option>
                                                <option @if(old('sex') == 'O') selected @endif
                                                value="O">Otro
                                                </option>
                                            </select>
                                        </div>
                                        @error('birthday')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <label for="email"
                                               class="col-md-4 col-form-label text-md-right">Correo electronico</label>
                                        <div class="col-md-6">
                                            <input type="email" class="form-control" name="email" id="email"
                                                   value="{{old('email')}}" required autocomplete="email" autofocus>
                                        </div>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <label for="password"
                                               class="col-md-4 col-form-label text-md-right">Contraseña</label>
                                        <div class="col-md-6 input-group">
                                            <button type="button" class="btn btn-outline-secondary" id="show-hide-pasw"
                                                    action="hide">
                                                <i id="icon-pasw" class="bi bi-eye-fill"></i>
                                            </button>
                                            <input id="password" type="password" value=""
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   name="password"
                                                   required autocomplete="new-password">
                                        </div>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <label for="password-confirm"
                                               class="col-md-4 col-form-label text-md-right">Confirmar
                                            Contraseña</label>
                                        <div class="col-md-6 input-group">
                                            <button type="button" class="btn btn-outline-secondary"
                                                    id="show-hide-pasw-conf" action="hide">
                                                <i id="icon-pasw-conf" class="bi bi-eye-fill"></i>
                                            </button>
                                            <input id="password-confirm" type="password" class="form-control"
                                                   name="password_confirmation" value=""
                                                   required autocomplete="new-password">
                                            @error('password-confirm')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-section">
                                    <div class="form-group row">
                                        <label for="reg_medico"
                                               class="col-md-4 col-form-label text-md-right">Nro Registro Médico</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="reg_medico"
                                                   value="{{old('reg_medico')}}"
                                                   required autocomplete="reg_medico" autofocus>
                                        </div>
                                        @error('reg_medico')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="form-group row">
                                        <label for="curriculum"
                                               class="col-md-4 col-form-label text-md-right">Curriculum</label>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="curriculum"
                                                   required autocomplete="reg_medico" autofocus>
                                        </div>
                                        @error('curriculum')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            Documentos anexos
                                        </div>
                                        <div class="card-body" id="card-docs">
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="name_docs[]"
                                                           autofocus placeholder="Tipo documento">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="file" class="form-control" name="docs[]"
                                                           autofocus placeholder="Archivo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button id="add-doc" type="button" class="btn btn-secondary float-right">
                                                Añadir documento
                                            </button>
                                        </div>
                                    </div>
                                    @error('docs')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-navigation">
                                    <button type="button" class="previous btn btn-primary float-left">Anterior</button>
                                    <button type="button" id="sig" class="next btn btn-primary float-right">Siguiente
                                    </button>
                                    <button type="submit" class="btn btn-success float-right"
                                            style="color: #000">Registrar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    $(function () {
        var $sections = $('.form-section');

        function navigateTo(index) {
            $sections.removeClass('current').eq(index).addClass('current');
            $('.form-navigation .previous').toggle(index > 0);
            var atTheEnd = index >= $sections.length - 1;
            $('.form-navigation .next').toggle(!atTheEnd);
            $('.form-navigation [type=submit]').toggle(atTheEnd);
        }

        function curIndex() {
            return $sections.index($sections.filter('.current'));
        }

        $('.form-navigation .previous').click(function () {
            navigateTo(curIndex() - 1);
        });

        $('.form-navigation .next').click(function () {
            $('.contact-form').parsley().whenValidate({
                group: 'block-' + curIndex()
            }).done(function () {
                if (curIndex() == 0) {
                    let value = $("#email").val();
                    $.ajax({
                        url: '/api/find-email',
                        data: {
                            email: value
                        },
                        success: function (data, status, jqXHR) {
                            switch (jqXHR.status) {
                                case 200: {
                                    let pass = $("#password.form-control.parsley-success").val();
                                    let pass_conf = $("#password-confirm.form-control.parsley-success").val();
                                    if (pass !== pass_conf) {
                                        alert('Las contraseñas no coinciden')
                                        return;
                                    }
                                    navigateTo(curIndex() + 1);
                                }
                                    break;
                            }
                        },
                        error: function (jqXHR, status, errorThrown) {
                            switch (jqXHR.status) {
                                case 406:
                                    alert('Correo invalido ya registrado')
                                    break;
                            }
                        }
                    });
                } else {
                    navigateTo(curIndex() + 1);
                }
            });
        });

        $sections.each(function (index, section) {
            $(section).find(':input').attr('data-parsley-group', 'block-' + index);
        });

        navigateTo(0);
    });
</script>

<script>
    $(document).ready(function () {
        /*$('#sig').click(function () {
            alert('gg');
            $.ajax({
                url: '/api/find-email',
                //method: 'POST',
                //dataType: 'json',
                data: {
                    email: 'willy4k@email.com'
                }
            }).done(function (response) {
                alert(response);
            });
        })*/
        $('#show-hide-pasw').click(function (e) {
            let current = $(this).attr('action');
            if (current == 'hide') {
                $(this).next().attr('type', 'text');
                $(this).attr('action', 'show');
                $('#icon-pasw').removeClass('bi bi-eye-fill').addClass('bi bi-eye-slash-fill');
            }
            if (current == 'show') {
                $(this).next().attr('type', 'password');
                $(this).attr('action', 'hide');
                $('#icon-pasw').removeClass('bi bi-eye-slash-fill').addClass('bi bi-eye-fill');
            }
        })
        $('#show-hide-pasw-conf').click(function (e) {
            let current = $(this).attr('action');
            if (current == 'hide') {
                $(this).next().attr('type', 'text');
                $(this).attr('action', 'show');
                $('#icon-pasw-conf').removeClass('bi bi-eye-fill').addClass('bi bi-eye-slash-fill');
            }
            if (current == 'show') {
                $(this).next().attr('type', 'password');
                $(this).attr('action', 'hide');
                $('#icon-pasw-conf').removeClass('bi bi-eye-slash-fill').addClass('bi bi-eye-fill');
            }
        })
        $('#add-doc').click(function () {
            let hijo = '<div class="form-group row">';
            hijo += '<div class="col-md-6">';
            hijo += '<input type="text" class="form-control" name="name_docs[]" autofocus placeholder="Tipo documento">';
            hijo += '</div>';
            hijo += '<div class="col-md-6">';
            hijo += '<input type="file" class="form-control" name="docs[]" autofocus placeholder="Archivo">';
            hijo += '</div>';
            $('#card-docs').append(hijo);
        })
    });
</script>
</body>
</html>
