@extends('layouts.app')

@section('content')
    <div class="content-wrapper" style="min-width: 729px; height: auto;">
        {{--<div class="content-header" style="height: 60px;">
            <div class="container-fluid">
                <a href="" class="btn btn-primary float-right">Registrar administrador</a>
            </div>
        </div>--}}
        <div class="content">
            <div class="container-fluid col-md-10">
                @if (session('gestion'))
                    <div class="alert alert-success" role="alert">
                        {{session('gestion')}}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3 class="float-left">Doctores por verificar</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-responsive-md" id="table">
                            <thead style="background-color: #6fbfff">
                            <tr class="font-weight-bold">
                                <td>Nombre</td>
                                <td>Apellido</td>
                                <td>Celular</td>
                                <td>Correo</td>
                                <td>Estado</td>
                                <td>Opciones</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($doctors as $doc)
                                <tr>
                                    <td>{{$doc->name}}</td>
                                    <td>{{$doc->last_name}}</td>
                                    <td>{{$doc->cellphone}}</td>
                                    <td>{{$doc->email}}</td>
                                    <td>{{$doc->state}}</td>
                                    <td>
                                        <a href="{{route('doctor-verification', $doc->id)}}" class="btn btn-sm btn-secondary">Ver documentos</a>
                                    </td>
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
