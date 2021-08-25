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
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                       role="tab" aria-controls="pills-home" aria-selected="true">Diagnosticos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile"
                       role="tab" aria-controls="pills-profile" aria-selected="false">Recetas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact"
                       role="tab" aria-controls="pills-contact" aria-selected="false">Analisis</a>
                </li>
            </ul>
            @for($i = 0;$i < count($diagnostics); $i++)
                <ul class="list-group list-group-flush">
                    {{--<li class="list-group-item"><strong>Fecha diagnostico: </strong>{{$diagnostics[$i]->time}}</li>--}}
                    {{--<li class="list-group-item"><strong>Diagnostico: </strong>{{$diagnostics[$i]->detail}}</li>--}}
                    {{--<li class="list-group-item">
                        <label for="">Medicamentos recetados</label><br>
                        <ul class="list-group list-group-flush">
                            @foreach($recetas[$i] as $rec)
                                <li class="list-group-item">{{$rec->name}}, {{$rec->dose}}
                                    {{$rec->concentration}}, {{$rec->detail}}
                                    --}}{{--, {{$rec->name_generic}}--}}{{--</li>
                            @endforeach
                        </ul>
                    </li>--}}
                    {{--<li class="list-group-item">
                        <label for="">Analisis ordenados</label><br>
                        <ul class="list-group list-group-flush">
                            @foreach($analisis[$i] as $anl)
                                <li class="list-group-item">{{$anl->detail}}</li>
                            @endforeach
                        </ul>
                    </li>--}}
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                         aria-labelledby="pills-home-tab">
                        <strong>Fecha diagnostico: </strong>{{$diagnostics[$i]->time}}<br>
                        <strong>Diagnostico: </strong>{{$diagnostics[$i]->detail}}
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        {{--<label for="">Medicamentos recetados</label><br>--}}
                        <strong>Fecha recetado: </strong>{{$recetas[$i][0]->time}}
                        <ul class="list-group list-group-flush">
                            @foreach($recetas[$i] as $rec)
                                <li class="list-group-item">{{$rec->name}}, {{$rec->dose}}
                                    {{$rec->concentration}}, {{$rec->detail}}
                                    {{--, {{$rec->name_generic}}--}}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                        {{--<label for="">Analisis ordenados</label><br>--}}
                        <strong>Fecha orden: </strong>{{$analisis[$i][0]->time}}
                        <ul class="list-group list-group-flush">
                            @foreach($analisis[$i] as $anl)
                                <li class="list-group-item">{{$anl->detail}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            @endfor
            {{$diagnostics->count()}}

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
            /*$('#myTab a').on('click', function (e) {
                e.preventDefault()
                $(this).tab('show')
            })*/

            /*$('#myTab a[href="#profile"]').tab('show') // Select tab by name
            $('#myTab li:first-child a').tab('show') // Select first tab
            $('#myTab li:last-child a').tab('show') // Select last tab
            $('#myTab li:nth-child(3) a').tab('show') //*/

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
