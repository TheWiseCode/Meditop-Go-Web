@extends('adminlte::page')

@section('title', 'Historial medico')

@section('content_header')
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="float-left">Historial medico</h3>
        </div>
        <div class="card-body">
            <label class="h4">Paciente {{$patient->name.' '.$patient->last_name}}</label><br>
            <span><strong>Sexo: </strong>{{\App\Models\Person::sexo($patient->sexo)}}</span><br>
            <span><strong>Tipo de sangre: </strong>{{$patient->blood_type}}</span><br>
            <span><strong>Alergias: </strong>{{$patient->allergy}}</span><br><br>
            <label class="h5">Consultas</label><br>
            @for($i = 0;$i < count($diagnostics); $i++)
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Fecha consulta: </strong>{{$diagnostics[$i]->time}}</li>
                    <li class="list-group-item"><strong>Diagnostico: </strong>{{$diagnostics[$i]->detail}}</li>
                    <li class="list-group-item">
                        <label for="">Medicamentos recetados</label><br>
                        <ul class="list-group list-group-flush">
                            @foreach($recetas[$i] as $rec)
                                <li class="list-group-item">{{$rec->name}}, {{$rec->dose}}
                                    {{$rec->concentration}}, {{$rec->detail}}
                                    {{--, {{$rec->name_generic}}--}}</li>
                            @endforeach
                        </ul>
                    </li>
                    <li class="list-group-item">
                        <label for="">Analisis ordenados</label><br>
                        <ul class="list-group list-group-flush">
                            @foreach($analisis[$i] as $anl)
                                <li class="list-group-item">{{$anl->detail}}</li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            @endfor
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
