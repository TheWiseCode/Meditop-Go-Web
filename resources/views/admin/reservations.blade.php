@extends('adminlte::page')

@section('title', 'Reservaciones')

@section('content_header')
@stop

@section('content')
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
                    <td>Doctor</td>
                    <td>Paciente</td>
                    <td>Fecha y Hora Consulta</td>
                    <td>Fecha y Hora Solicitud</td>
                    <td>Estado</td>
                </tr>
                </thead>
                <tbody>
                @for($i = 0; $i < count($reservations); $i++)
                    <tr>
                        <td>{{$reservations[$i]->name_specialty}}</td>
                        <td>{{$reservations[$i]->name_doctor}}</td>
                        <td>{{$reservations[$i]->name_patient}}</td>
                        <td>
                            {{\Carbon\Carbon::createFromFormat( 'Y-m-d H:i:s', $reservations[$i]->time_consult)->format('d/m/Y H:i:s')}}
                        </td>
                        <td>
                            {{\Carbon\Carbon::createFromFormat( 'Y-m-d H:i:s', $reservations[$i]->time_reservation)->format('d/m/Y H:i:s')}}
                        </td>
                        <td>
                            {{$reservations[$i]->state}}
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
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
                    url: '/admin-reservations-filter',
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
                        row += `<td> ${reservations[i]['name_doctor']} </td>`;
                        row += `<td> ${reservations[i]['name_patient']} </td>`;
                        row += `<td> ${reservations[i]['time_consult']} </td>`;
                        row += `<td> ${reservations[i]['time_reservation']} </td>`;
                        row += `<td> ${reservations[i]['state']} </td>`;
                        row += '</tr>';
                        $('#table tbody').append(row);
                    }
                });
            });
        });

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
