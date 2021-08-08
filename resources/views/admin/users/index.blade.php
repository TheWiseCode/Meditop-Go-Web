@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
@stop

@section('content')
    @if (session('gestion'))
        <div class="alert alert-success" role="alert">
            {{session('gestion')}}
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h3 class="float-left">Lista de personas</h3>
            <a href="{{route('doctor-requests')}}" class="btn btn-primary float-right ml-2">Doctores no verificados</a>
            @if(auth()->user()->isOwner())
                <a href="{{route('create-admin')}}" class="btn btn-primary float-right">Registrar
                    administrador</a>
            @endif
        </div>
        <div class="card-body">
            <table class="table table-bordered table-responsive-md" id="table">
                <thead style="background-color: #6fbfff">
                <tr class="font-weight-bold">
                    <td>Nombre</td>
                    <td>Apellido</td>
                    <td>Celular</td>
                    <td>Corre-o</td>
                    <td>Tipo</td>
                    <td>Opciones</td>
                </tr>
                </thead>
                <tbody>
                @foreach($persons as $pers)
                    <tr>
                        <td>{{$pers->name}}</td>
                        <td>{{$pers->last_name}}</td>
                        <td>{{$pers->cellphone}}</td>
                        <td>{{$pers->email}}</td>
                        <td>@if($pers->isAdmin())
                                Admin
                            @elseif($pers->isPatient())
                                Paciente
                            @else
                                Doctor
                            @endif
                        </td>
                        <td>
                            <a href="{{route('users.show', $pers->id_user)}}" class="btn btn-sm btn-secondary">Ver
                                datos</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            let value = "Mostrar "
            value += "<select class='custom-select custom-select-sm form-control form-control-sm'>";
            value += "<option value='10'>10</option>";
            value += "<option value='25'>25</option>";
            value += "<option value='50'>50</option>";
            value += "<option value='100'>100</option>";
            value += "<option value='-1'>Todos</option>";
            value += "</select>";
            value += " registros por pagina";
            $("#table").DataTable({
                responsive: true,
                autoWidth: true,
                "language": {
                    "lengthMenu": value,
                    "zeroRecords":
                        "No pudimos encontrar nada, lo siento",
                    "info":
                        "Mostrando pagina _PAGE_ de _PAGES_",
                    "infoEmpty":
                        "Sin registros disponibles",
                    "infoFiltered":
                        "(filtrado de _MAX_ registros totales)",
                    "search":
                        "Buscar:",
                    "paginate":
                        {
                            "next":
                                "Siguiente",
                            "previous":
                                "Anterior"
                        }
                }
            })
        })
    </script>
@endsection

@section('css')

@endsection
