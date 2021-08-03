@extends('layouts.app')

@section('content')
    <div class="content-wrapper" style="min-width: 729px; height: auto;">
        <div class="content">
            <div class="container-fluid col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="float-left">Editar horario</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{route('doctor-update-schedule', $offer)}}" method="post">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Especialidad</label>
                                        <select name="specialty" id="" class="form-control">
                                            @foreach($specialties as $spe)
                                                <option value="{{$spe->id}}">{{$spe->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('specialty')
                                        <small class="text-danger"><strong>*{{$message}}</strong></small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Dias de atencion</label><br>
                                        <div class="container border overflow-auto alto">
                                            @foreach($days as $d)
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="days[]"
                                                           value="{{$d->id}}">
                                                    <label for="">{{$d->name}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('days')
                                        <small class="text-danger"><strong>*{{$message}}</strong></small>
                                        @enderror
                                    </div>
                                </div>
                                {{--<label for="">Horario atencion</label>--}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Horario inicio</label>
                                        <input value="{{old('time-start')}}" name="time-start" id="time-start"
                                               class="form-control timepicker" required>
                                        @error('time-start')
                                        <small class="text-danger"><strong>*{{$message}}</strong></small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Horario final</label>
                                        <input value="{{old('time-end')}}" name="time-end" id="time-end"
                                               class="form-control timepicker" required>
                                        @error('time-end')
                                        <small class="text-danger"><strong>*{{$message}}</strong></small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <button class="form-control btn btn-success" type="submit" id="btn_form">
                                            Modificar
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="form-control btn btn-danger" type="reset">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $('form').on('submit', function (e) {
                let tstart = $('#time-start').val();
                let tend = $('#time-end').val();
                let start_am = tstart.substr(tstart.length - 2);
                let end_am = tend.substr(tend.length - 2);
                tstart = tstart.substr(0, tstart.length - 2)
                tend = tend.substr(0, tend.length - 2)
                let sparts = tstart.split(':').map(function (x) {
                    return parseInt(x);
                });
                let eparts = tend.split(':').map(function (x) {
                    return parseInt(x);
                });
                if (start_am == 'PM' && sparts[0] != 12)
                    sparts[0] += 12;
                if (end_am == 'PM')
                    eparts[0] += 12;
                let da = true;
                if (sparts[0] <= eparts[0]) {
                    if (sparts[0] == eparts[0]) {
                        if (sparts[1] >= eparts[1]) {
                            da = false;
                        }
                    }
                } else {
                    da = false;
                }
                if (!da) {
                    e.preventDefault();
                    alert('Horarios inconsistentes');
                }

            });
            $('#time-start').timepicker({
                timeFormat: 'h:mmp',
                interval: 30,
                minTime: '05',
                maxTime: '11:00pm',
                defaultTime: '11',
                startTime: '10:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
            $('#time-end').timepicker({
                timeFormat: 'h:mmp',
                interval: 30,
                minTime: '05',
                maxTime: '11:00pm',
                defaultTime: '11',
                startTime: '10:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });
    </script>
@endsection

@section('styles')
    <style>
        label {
            font-weight: bold;
        }
    </style>

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@endsection
