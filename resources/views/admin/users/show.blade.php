@extends('layouts.app')

@section('content')
    <div class="content-wrapper" style="min-width: 729px; height: auto;">
        <div class="content">
            <div class="container-fluid col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="float-left">
                            @if($person->isAdmin())
                                Administrador:
                            @elseif($person->isDoctor())
                                Doctor:
                            @else
                                Paciente:
                            @endif
                            {{$person->name.' '.$person->last_name}}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Nombres</label>
                                    <input type="text" value="{{$person->name}}" name="name" class="form-control"
                                           readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Apellidos</label>
                                    <input type="text" value="{{$person->last_name}}" name="last_name"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Carnet Identidad</label>
                                    <input type="text" value="{{$person->ci}}" name="ci" class="form-control"
                                           readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Celular</label>
                                    <input type="text" value="{{$person->cellphone}}" name="cellphone"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Fecha de nacimiento</label>
                                    <input type="text" value="{{$person->birthday}}" name="birthday"
                                           class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Genero</label>
                                    <input type="text" value="{{\App\Models\Person::sexo($person->sex)}}" name="sex"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Correo electronico</label>
                                    <input type="email" value="{{$person->email}}" name="email" class="form-control"
                                           readonly>
                                </div>
                            </div>
                            @if($person->isAdmin())
                            @elseif($person->isDoctor())
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Registro medico</label>
                                        <input type="text" value="{{$person->reg_doctor}}" name="reg_doctor"
                                               class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="my-4">
                                    <div class="card">
                                        <div class="card-header"><strong>Documentos</strong></div>
                                        <div class="card-body">
                                            <table class="table table-bordered table-responsive-md">
                                                <thead style="background-color: #6fbfff; font-weight: bold">
                                                <tr>
                                                    <td>Nombre del documento</td>
                                                    <td>Documento</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($docs as $dc)
                                                    <tr>
                                                        <td>{{$dc->name}}</td>
                                                        <td>
                                                            <a href="{{$dc->url}}" target="_blank">Abrir documento</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Tipo de sangre</label>
                                        <input type="text" value="{{$person->blood_type}}" name="blood_type"
                                               class="form-control" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Alergias</label>
                                        <input type="text" value="{{\App\Models\Person::sexo($person->allergy)}}"
                                               name="allergy"
                                               class="form-control" readonly>
                                    </div>
                                </div>
                            @endif
                            {{--<div class="row mt-2">
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
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection

@section('styles')
    <style>
        label {
            font-weight: bold;
        }
    </style>
@endsection
