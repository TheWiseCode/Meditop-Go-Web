@extends('adminlte::page')

@section('title', 'Reservaciones')

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
            <h3 class="float-left">Reservaciones</h3>
            <select name="reservations_state" id="state" class="form-control float-right col-md-2">
                <option value="todas">Todas</option>
                <option value="pendiente">Pendientes</option>
                <option value="aceptada">Aceptadas</option>
                <option value="rechazada">Rechazadas</option>
                <option value="cancelada">Canceladas</option>
            </select>
            <label for="" class="float-right mr-3">Estado de reservacion</label>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-responsive-md" id="table">
                <thead style="background-color: #6fbfff">
                <tr class="font-weight-bold">
                    <td>Especialidad</td>
                    <td>Paciente</td>
                    <td>Fecha y Hora Consulta</td>
                    <td>Fecha y Hora Solicitud</td>
                    <td>Estado</td>
                    <td>Opciones</td>
                </tr>
                </thead>
                <tbody>
                @for($i = 0; $i < count($reservations); $i++)
                    <tr>
                        <td>{{$reservations[$i]->name_specialty}}</td>
                        <td>{{$reservations[$i]->name_complete}}</td>
                        <td>
                            {{\Carbon\Carbon::createFromFormat( 'Y-m-d H:i:s', $reservations[$i]->time_consult)->format('d/m/Y H:i:s')}}
                        </td>
                        <td>
                            {{\Carbon\Carbon::createFromFormat( 'Y-m-d H:i:s', $reservations[$i]->time_reservation)->format('d/m/Y H:i:s')}}
                        </td>
                        <td>
                            {{$reservations[$i]->state}}
                        </td>
                        <td>
                            @if($reservations[$i]->state == 'pendiente')
                                <div class="row">
                                    <form action="{{route('accept-reservation')}}"
                                          method="post">
                                        @csrf
                                        <input type="hidden" name="id_reservation" value="{{$reservations[$i]->id}}">
                                        <button type="submit" class="btn btn-success mx-2">
                                            Aceptar
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger"
                                            onclick="loadIdDenied({{$reservations[$i]->id}})"
                                            data-toggle="modal"
                                            data-target="#exampleModal" data-whatever="@mdo">Rechazar
                                    </button>
                                </div>
                            @elseif($reservations[$i]->state == 'aceptada')
                                <button type="button" class="btn btn-danger"
                                        onclick="loadIdCancel({{$reservations[$i]->id}})"
                                        data-toggle="modal"
                                        data-target="#exampleModal1" data-whatever="@mdo">Cancelar
                                </button>
                            @endif
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
                    <h5 class="modal-title" id="exampleModalLabel">Rechazar
                        reservacion</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('denied-reservation')}}"
                      method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input id="deniedIdRes" type="hidden" name="id_reservation" value="">
                            <label for="recipient-name" class="col-form-label">Motivo
                                rechazo</label>
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

    <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancelar
                        reservacion</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('cancel-reservation')}}"
                      method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input id="cancelIdRes" type="hidden" name="id_reservation" value="">
                            <label for="recipient-name" class="col-form-label">Motivo
                                rechazo</label>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $("#state").change(function () {
                let value = $("#state").val();
                $.ajax({
                    url: '/reservations-filter',
                    method: 'GET',
                    data: {
                        state: value
                    }
                }).done(function (response) {
                    let reservations = JSON.parse(response);
                    console.log(reservations);
                    $('#table tbody').empty();
                    for (let i = 0; i < reservations.length; i++) {
                        let row = '<tr>'
                        row += `<td> ${reservations[i]['name_specialty']} </td>`;
                        row += `<td> ${reservations[i]['name_complete']} </td>`;
                        row += `<td> ${reservations[i]['time_consult']} </td>`;
                        row += `<td> ${reservations[i]['time_reservation']} </td>`;
                        row += `<td> ${reservations[i]['state']} </td>`;
                        row += '<td>';
                        if (reservations[i]['state'] === 'pendiente') {
                            row += '<div class="row">';
                            row += '<form action="{{route('accept-reservation')}}" method="post"> @csrf';
                            row += `<input type="hidden" name="id_reservation" value="${reservations[i]['id']}">`;
                            row += '<button type="submit" class="btn btn-success mx-2">Aceptar</button>';
                            row += '</form>';
                            row += '<button type="button" class="btn btn-danger"';
                            row += `onclick="loadIdDenied(${reservations[i]['id']})`;
                            row += 'data-toggle="modal"';
                            row += 'data-target="#exampleModal" data-whatever="@mdo">Rechazar';
                            row += '</button>';
                            row += '</div>';
                        }
                        row += '</td>';
                        row += '</tr>';
                        $('#table tbody').append(row);
                    }
                });
            });
        });

        function loadIdCancel(id) {
            $('#cancelIdRes').val(id);
        }

        function loadIdDenied(id) {
            $('#deniedIdRes').val(id);
        }

        /*$('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            var modal = $(this)
            //modal.find('.modal-title').text('New message to ' + recipient)
            //modal.find('.modal-body input').val(recipient)
        })*/
    </script>
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
