@extends('layouts.app')

@section('content')
    <div class="content-wrapper" style="min-width: 729px; height: auto;">
        <div class="content">
            <div class="container-fluid col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="float-left">
                            Doctor: {{$person->name.' '.$person->last_name}}</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Nombres</label>
                                    <input type="text" value="{{$person->name}}" name="name" class="form-control"
                                           readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Apellidos</label>
                                    <input type="text" value="{{$person->last_name}}" name="last_name"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Carnet Identidad</label>
                                    <input type="text" value="{{$person->ci}}" name="ci" class="form-control"
                                           readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Celular</label>
                                    <input type="text" value="{{$person->cellphone}}" name="cellphone"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Fecha de nacimiento</label>
                                    <input type="text" value="{{$person->birthday}}" name="birthday"
                                           class="form-control" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Genero</label>
                                    <input type="text" value="{{\App\Models\Person::sexo($person->sex)}}" name="sex"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Correo electronico</label>
                                    <input type="email" value="{{$person->email}}" name="email" class="form-control"
                                           readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Registro medico</label>
                                    <input type="text" value="{{$person->reg_doctor}}" name="reg_doctor"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="my-4">
                                <div class="card">
                                    <div class="card-header"><strong>Documentos</strong></div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-responsive-md">
                                            <thead style="background-color: #6fbfff; font-weight: bold">
                                            <tr>
                                                <td>Nombre del documento</td>
                                                <td>Documento</td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($docs as $dc)
                                                <tr>
                                                    <td>{{$dc->name}}</td>
                                                    <td>
                                                        <a href="{{$dc->url}}" target="_blank">Abrir documento</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <form action="{{route('accept-verification')}}" method="post">
                                        @csrf
                                        <input type="hidden" value="{{$person->id}}" name="person_id">
                                        <button class="form-control btn btn-success">
                                            Aceptar registro
                                        </button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="form-control btn btn-danger" data-toggle="modal"
                                            data-target="#exampleModal" data-whatever="@mdo">Rechazar registro
                                    </button>
                                    {{--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@fat">Open modal for @fat</button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@getbootstrap">Open modal for @getbootstrap</button>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rechazar registro</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('denied-verification')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" value="{{$person->id}}" name="person_id">
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Motivo rechazo</label>
                            <textarea class="form-control" id="message-text" name="detail" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('whatever') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text('New message to ' + recipient)
            modal.find('.modal-body input').val(recipient)
        })
    </script>
@endsection

@section('styles')
    <style>
        label {
            font-weight: bold;
        }
    </style>
@endsection
