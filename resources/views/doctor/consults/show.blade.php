@extends('adminlte::page')

@section('title', 'Consulta')

@section('content_header')
    <style>
        label {
            font-weight: bold;
        }
    </style>
    {{--<h3>Consulta en proceso</h3>--}}
    {{--{{$consult}}
    {{$room}}--}}
@stop

@section('content')
    <input type="hidden" id="roomName" value="{{$room}}">
    <input type="hidden" id="patientName" value="{{$consult->name_patient}}">
    <input type="hidden" id="patientEmail" value="{{$consult->email_patient}}">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3>Detalles de la consulta</h3></div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <label for="">Especialidad</label>
                                <input type="text" value="{{$consult->name_specialty}}" readonly class="form-control">
                            </div>
                            <div class="row">
                                <label for="">Paciente</label>
                                <input type="text" value="{{$consult->name_patient}}" readonly class="form-control">
                                <a href="{{route('patient.historial', $consult->id_patient)}}" target="_blank"
                                   class="form-control btn btn-secondary">
                                    Ver historial médico
                                </a>
                            </div>
                            <div class="row">
                                <label for="">Diagnostico</label>
                                <button type="button" class="btn btn-secondary form-control" data-toggle="modal"
                                        data-target="#modalDiagnostic" data-whatever="@mdo">Registrar diagnostico
                                </button>
                            </div>
                            <div class="row">
                                <label for="">Recetas</label>
                                <button type="button" class="btn btn-secondary form-control" data-toggle="modal"
                                        data-target="#modalReceta" data-whatever="@mdo">Realizar receta
                                </button>
                            </div>
                            <div class="row">
                                <label for="">Analisis</label>
                                <button type="button" class="btn btn-secondary form-control" data-toggle="modal"
                                        data-target="#modalAnalisis" data-whatever="@mdo">Ordenar analisis
                                </button>
                            </div>
                            <div class="row m-4">
                                <button class="btn btn-primary form-control" onclick="endConsult()">Finalizar consulta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="meet" style="height: 500px;width: 500px;"/>
        </div>
    </div>
    <form action="{{route('consults.store')}}" method="post">
        @csrf
        <div class="div" style="display: none">
            <input type="text" value="{{$consult->id}}" name="id_consult">
        </div>
        <input type="hidden" value=0 name="diagnostic" id="diagnostic">
        <input type="hidden" value=0 name="receta" id="receta">
        <input type="hidden" value=0 name="analisis" id="analisis">

        <div class="modal fade" id="modalDiagnostic" tabindex="-1" role="dialog"
             aria-labelledby="modalDiagnosticLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Diagnostico</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Detalle</label>
                            <textarea class="form-control" id="detail_consult"
                                      name="detail_diagnostic"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" onclick="regDiagnostic()">Registrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalReceta" tabindex="-1" role="dialog"
             aria-labelledby="modalRecetaLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Receta</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="medicamentos">
                        </div>
                        <button id="add-med" type="button" class="btn btn-secondary float-right"
                                onclick="addMedicamento()">
                            Añadir medicamento
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" onclick="regReceta()">Registrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalAnalisis" tabindex="-1" role="dialog"
             aria-labelledby="modalAnalisisLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Analisis</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Detalle</label>
                            <textarea class="form-control" id="detail_analisis"
                                      name="detail_analisis"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" onclick="regAnalisis()">Registrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src='https://meet.jit.si/external_api.js'></script>
    <script>
        function jitsi() {
            const domain = 'meet.jit.si';
            const options = {
                roomName: $('#roomName').val(),
                width: '100%',
                height: '100%',
                parentNode: document.querySelector('#meet'),
                userInfo: {
                    email: $('#patientEmail').val(),
                    displayName: $('#patientName').val()
                }
            };
            const api = new JitsiMeetExternalAPI(domain, options);
        }

        window.onload = jitsi;
    </script>
    <script>
        function regDiagnostic() {
            let detail = $('#detail_consult').val();
            if (detail === '') {
                $.alert({
                    title: 'Registrar diagnostico',
                    content: 'El detalle del diagnostico es obligatorio',
                });
                return;
            }
            $('#diagnostic').val(1);
            $('#modalDiagnostic').modal('hide');
        }

        function regAnalisis() {
            let detail = $('#detail_consult').val();
            if (detail === '') {
                $.alert({
                    title: 'Ordenar analisis',
                    content: 'El detalle del analisis no debe estar vacio',
                });
                return;
            }
            $('#analisis').val(1);
            $('#modalAnalisis').modal('hide');
        }

        function regReceta() {
            /*let detail = $('#detail_consult').val();
            if (detail === '') {
                $.alert({
                    title: 'Ordenar analisis',
                    content: 'El detalle del analisis no debe estar vacio',
                });
                return;
            }*/
            $('#receta').val(1);
            $('#modalReceta').modal('hide');
        }

        var meds = 0;

        function delMedicamento(med) {
            $('#med' + med).remove();
        }

        function addMedicamento() {
            let hijo = '<div class="form-group row" id="med' + meds + '">'
            hijo += '<label class="font-weight-bold col-md-6">Medicamento</label><br>'
            hijo += '<button type="button" class="btn" style="background-color: transparent" onclick="delMedicamento(' + meds + ')"><i class="fas fa-times"></i></button>';
            hijo += '<div class="col-md-6">'
            hijo += '<input type="text" class="form-control" name="medicamento[]" autofocus placeholder="Nombre Medicamento">'
            hijo += '</div>'
            hijo += '<div class="col-md-6">'
            hijo += '<input type="text" class="form-control" name="generico[]" autofocus placeholder="Nombre generico">'
            hijo += '</div>'
            hijo += '<div class="col-md-6">'
            hijo += '<input type="text" class="form-control" name="dosis[]" autofocus placeholder="Dosis">'
            hijo += '</div>'
            hijo += '<div class="col-md-6">'
            hijo += '<input type="text" class="form-control" name="concentracion[]" autofocus placeholder="Concentracion">'
            hijo += '</div>'
            hijo += '<div class="col-md-12">'
            hijo += '<textarea name="detail_receta[]" id="" cols="30" rows="3" placeholder="Detalle receta" class="form-control" style="resize: none;"></textarea>'
            hijo += '</div>'
            hijo += '</div>';
            meds++;
            $('#medicamentos').append(hijo);
        }

        function endConsult() {
            let diag = $('#diagnostic').val();
            if (diag == 0) {
                $.alert({
                    title: 'Finalizar consulta',
                    content: 'Registre un diagnostico para poder finalizar la consulta',
                });
                return;
            }
            $.confirm({
                title: 'Finalizar consulta!',
                content: 'Desea finalizar la consulta',
                buttons: {
                    confirmar: function () {
                        $('form').submit();
                    },
                    cancelar: function () {
                        $.alert('Cancelado!');
                    },
                    /*somethingElse: {
                        text: 'Something else',
                        btnClass: 'btn-blue',
                        keys: ['enter', 'shift'],
                        action: function(){
                            $.alert('Something else?');
                        }
                    }*/
                }
            });
        }
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection
