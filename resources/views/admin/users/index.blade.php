@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header"><h3>Lista de personas</h3></div>

                    <div class="card-body">
                        <table class="table table-bordered" id="table">
                            <thead style="background-color: #6fbfff">
                            <tr class="font-weight-bold">
                                <td>Nombre</td>
                                <td>Apellido</td>
                                <td>Celular</td>
                                <td>Correo</td>
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
                                    <td></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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

@section('styles')

@endsection
