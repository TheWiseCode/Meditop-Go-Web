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
                        <h3 class="float-left">Mis horarios</h3>
                        <a href="{{route('doctor-add-schedule')}}" class="btn btn-primary float-right ml-2">Registrar
                            nuevo horario</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-responsive-md" id="table">
                            <thead style="background-color: #6fbfff">
                            <tr class="font-weight-bold">
                                <td>Especialidad</td>
                                <td>Dias de atencion</td>
                                <td>Horario inicio</td>
                                <td>Horario final</td>
                                <td>Opciones</td>
                            </tr>
                            </thead>
                            <tbody>
                            @for($i = 0; $i < count($offers); $i++)
                                <tr>
                                    <td>{{$offers[$i]->name}}</td>
                                    <td>
                                        @foreach($schedules[$i] as $sch)
                                            {{$sch->name.' '}}
                                        @endforeach
                                    </td>
                                    <td>{{$offers[$i]->time_start}}</td>
                                    <td>{{$offers[$i]->time_end}}</td>
                                    <td>
                                        <div class="row">
                                            <a style="background-color: transparent; color: #136eff" class="btn mr-1"
                                               href="#">
                                                <i id="" class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="#" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit"
                                                        style="background-color: transparent; color: #136eff"
                                                        class="btn">
                                                    <i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
@endsection
