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
            <input type="hidden" value="{{old('tabnro', 1)}}" name="tabnro" id="tabnro">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="pills-diagnosticos-tab" data-toggle="pill" href="#pills-diagnosticos"
                       role="tab" aria-controls="pills-diagnosticos" aria-selected="false">Diagnosticos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-recetas-tab" data-toggle="pill" href="#pills-recetas"
                       role="tab" aria-controls="pills-recetas" aria-selected="false">Recetas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-analisis-tab" data-toggle="pill" href="#pills-analisis"
                       role="tab" aria-controls="pills-analisis" aria-selected="false">Analisis</a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="pills-diagnosticos" role="tabpanel"
                     aria-labelledby="pills-diagnosticos-tab">
                    @foreach($diagnostics as $diag)
                        <strong>Fecha diagnostico: </strong>{{$diag->time}}<br>
                        <strong>Detalle diagnostico: </strong>{{$diag->detail}}<br>
                        <hr style="height:1px;border-width:0;color:gray;background-color:gray">
                    @endforeach
                    {{$diagnostics->links("pagination::bootstrap-4")}}
                </div>
                <div class="tab-pane fade" id="pills-recetas" role="tabpanel" aria-labelledby="pills-recetas-tab">
                    @foreach($recetas as $rec)
                        <strong>Fecha recetado: </strong>{{$rec->time}}<br>
                        <strong>Detalle: </strong>{{$rec->name}}, {{$rec->dose}}
                        {{$rec->concentration}}, {{$rec->detail}}, {{$rec->name_generic}}<br>
                        <hr style="height:1px;border-width:0;color:gray;background-color:gray">
                    @endforeach
                    {{$recetas->links("pagination::bootstrap-4")}}
                </div>
                <div class="tab-pane fade" id="pills-analisis" role="tabpanel" aria-labelledby="pills-analisis-tab">
                    @foreach($analisis as $anl)
                        <strong>Fecha orden: </strong>{{$anl->time}}<br>
                        <strong>Detalle: </strong>{{$anl->detail}}<br>
                        <hr style="height:1px;border-width:0;color:gray;background-color:gray">
                    @endforeach
                    {{$analisis->links("pagination::bootstrap-4")}}
                </div>
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
            let tabpage = $('#tabnro').val();
            if (tabpage == '1') {
                $('#pills-diagnosticos-tab').addClass('active').attr('aria-selected', true);
                $('#pills-diagnosticos').addClass('show active');
            } else if (tabpage == '2') {
                $('#pills-recetas-tab').addClass('active').attr('aria-selected', true);
                $('#pills-recetas').addClass('show active');
            } else {
                $('#pills-analisis-tab').addClass('active').attr('aria-selected', true);
                $('#pills-analisis').addClass('show active');
            }

            $('#pills-tab li:nth-child(1) a').on('click', function (e) {
                e.preventDefault()
                $('#tabnro').val(1);
                $(this).tab('show')
            })
            $('#pills-tab li:nth-child(2) a').on('click', function (e) {
                e.preventDefault()
                $('#tabnro').val(2);
                $(this).tab('show')
            })
            $('#pills-tab li:nth-child(3) a').on('click', function (e) {
                e.preventDefault()
                $('#tabnro').val(3);
                $(this).tab('show')
            })

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
