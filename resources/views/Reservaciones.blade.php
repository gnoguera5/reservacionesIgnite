@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 ">

            <h2 class="text-center">Reservas agendadas</h2>
            <div class="jumbotron">

                <div class="table-responsive" style="padding: 10px">
                    <form class="form-inline pull-right">
                        <div class="input-group ">
                            <input class="form-control pull-right" value="{{$fecha}}" onchange="sendData()" placeholder="Filtrar por fecha" aria-describeby="search" name="fecha" id="fecha" type="text">
                            <span class="input-group-addon" id="search">
                                <span class="fa fa-search" aria-hidden="true"></span>
                            </span>
                        </div>
                    </form>
                    <br><br>

                    <table class="table">
                        <tr>
                            <th>No. de maquina</th>
                            <th>Folio</th>
                            <th>Fecha</th>
                            <th>Horario</th>

                        </tr>
                        @foreach($reservations as $reservation)
                        <tr>
                            <td>
                                <?php
                                $equipment = App\Equipment::where('id',$reservation->equipment_id)->first();
                                echo $equipment->numero_equipo;
                                ?>
                            </td>
                            <td>{{$reservation->folio}}</td>
                            <td>{{$reservation->fecha_reservacion}}</td>
                            <td>{{$reservation->hora_inicial}} a {{$reservation->hora_final}}</td>
                        </tr>
                        @endforeach
                    </table>
                   <div class="text-center">
                       {{ $reservations->links() }}
                   </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('js/jquery.timepicker.min.js')}}"></script>
    <script src="{{asset('js/admin.js')}}"></script>
    <script>
        var $ = jQuery;
        var dateToday = new Date();
        $(function() {
            $( "#fecha" ).datepicker(
                {
                    minDate: dateToday,
                    maxDate: "+1M +10D",
                    dateFormat: 'yy-mm-dd'
                }
            );
            

        });
        function sendData() {
            var fecha = $('#fecha').val();
            window.location = '/Reservadas/' + fecha;
        }
    </script>
@endsection