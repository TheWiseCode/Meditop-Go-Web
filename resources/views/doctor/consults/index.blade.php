@extends('adminlte::page')

@section('title', 'Consultas')

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
            <h3 class="float-left">Consultas agendadas</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-responsive-md" id="table">
                <thead style="background-color: #6fbfff">
                <tr class="font-weight-bold">
                    <td>Especialidad</td>
                    <td>Paciente</td>
                    <td>Fecha y Hora Consulta</td>
                    <td>Opciones</td>
                </tr>
                </thead>
                <tbody>
                @for($i = 0; $i < count($consults); $i++)
                    <tr>
                        <td>{{$consults[$i]->name_specialty}}</td>
                        <td>{{$consults[$i]->name_complete}}</td>
                        <td>
                            {{\Carbon\Carbon::createFromFormat( 'Y-m-d H:i:s', $consults[$i]->time)->format('d/m/Y H:i:s')}}
                        </td>
                        <td>
                            <div class="row">
                                @if(\Carbon\Carbon::createFromFormat( 'Y-m-d H:i:s', $consults[$i]->time)->modify('-10 minutes')->lte($now))
                                    <a href="{{route('consults.show', $consults[$i]->id)}}"
                                       class="btn btn-primary mr-2">Entrar</a>
                                @else
                                    <button type="button" class="btn btn-danger"
                                            onclick="loadIdConsult({{$consults[$i]->id}})"
                                            data-toggle="modal"
                                            data-target="#exampleModal" data-whatever="@mdo">Cancelar
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancelar
                        consulta</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('consult.cancel')}}"
                      method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input id="consultId" type="hidden" name="id_consult" value="">
                            <label for="recipient-name" class="col-form-label">Motivo
                                cancelacion</label>
                            <textarea class="form-control" id="message-text"
                                      name="detail" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary">Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

    <script>
        function loadIdConsult(id) {
            $('#consultId').val(id);
        }

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
                responsive: false,
                autoWidth: false,
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
@stop
