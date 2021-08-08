@extends('adminlte::page')

@section('title', 'Registrar administrado')

@section('content_header')
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="float-left">Registrar administrador</h3>
        </div>
        <div class="card-body">
            <form action="{{route('store-admin')}}" method="post">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Nombres</label>
                            <input type="text" value="{{old('name')}}" name="name" class="form-control"
                                   required minlength="3">
                            @error('name')
                            <small class="text-danger"><strong>*{{$message}}</strong></small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="">Apellidos</label>
                            <input type="text" value="{{old('last_name')}}" name="last_name"
                                   class="form-control" required minlength="3">
                            @error('last_name')
                            <small class="text-danger"><strong>*{{$message}}</strong></small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Carnet Identidad</label>
                            <input type="text" value="{{old('ci')}}" name="ci" class="form-control"
                                   pattern=".{7,10}" title="Introduzca un valor numerico de minimo 7 digitos" required>
                            @error('ci')
                            <small class="text-danger"><strong>*{{$message}}</strong></small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="">Celular</label>
                            <input type="text" value="{{old('cellphone')}}" name="cellphone"
                                   class="form-control" pattern=".{7,10}"
                                   title="Introduzca un valor numerico de minimo 7 digitos" required>
                            @error('cellphone')
                            <small class="text-danger "><strong>*{{$message}}</strong></small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Fecha de nacimiento</label>
                            <input type="date" value="{{old('birthday')}}" name="birthday"
                                   class="form-control"
                                   required>
                            @error('birthday')
                            <small class="text-danger"><strong>*{{$message}}</strong></small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="">Genero</label>
                            <select name="sex" id="" class="form-control" required>
                                <option value="" selected disabled>Seleccione su genero</option>
                                <option value="M" @if(old('sex') == 'M') selected @endif>Masculino</option>
                                <option value="F" @if(old('sex') == 'F') selected @endif>Femenino</option>
                                <option value="O" @if(old('sex') == 'O') selected @endif>Otro</option>
                            </select>
                            @error('sex')
                            <small class="text-danger"><strong>*{{$message}}</strong></small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Correo electronico</label>
                            <input type="email" value="{{old('email')}}" name="email" class="form-control"
                                   required>
                            @error('email')
                            <small class="text-danger"><strong>*{{$message}}</strong></small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="">Profesion</label>
                            <input type="text" value="{{old('profession')}}" name="profession"
                                   class="form-control" required minlength="3">
                            @error('profession')
                            <small class="text-danger"><strong>*{{$message}}</strong></small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <button class="form-control btn btn-success">
                                Registrar
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="form-control btn btn-danger" type="reset">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
@endsection

@section('css')
    <style>
        label {
            font-weight: bold;
        }
    </style>
@endsection
