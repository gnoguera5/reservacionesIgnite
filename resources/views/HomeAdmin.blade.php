<?php
use \App\Helpers\Utilities;
$utilities = new Utilities;

?>
@extends('layouts.app')
@section('content')

    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <div class="row" id="app">
        <div class="container">
            @include('flash::message')
        </div>
        <div class="row">
            <div id="sucess" style="display: none">
                <div class="container">
                    <div role="alert" class="alert alert-info alert-important">
                        <button type="button" data-dismiss="alert" aria-hidden="true" class="close">×</button>
                        Se ha agregado una nueva reservación
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <h2 class="text-center">Pendientes</h2>
            <div class="jumbotron">

                <div class="table-responsive">
                    <table class="table" id="t_pendientes" style="font-size: 10px"> 
                        <tr>
                            <td>No. de maquina</td>
                            <td colspan="2">Folio</td>
                        </tr>
                        <tr v-for="(value, key) in pendentes">
                            <td>@{{value.equipo}}</td>
                            <td>@{{value.folio}}</td>
                            <td>
                                <a :href="'/statusReservation/'+value.id+'/1'" class="btn btn-primary btn-sm"><i class="fa fa-check" aria-hidden="true"></i></a>
                                <a class="btn btn-danger btn-sm" @click="cancelar(value.id,4)"><i class="fa fa-close" aria-hidden="true"></i></a>
                                <a :href="'/reservation/create/'+value.id+''" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                 <a href="#"  @click="mostarMotivo(value.comentario)">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>

                    </table>

                </div>

            </div>
        </div>

        {{----}}
        <div class="col-md-3">
            <h2 class="text-center">Agendadas</h2>
            <div class="jumbotron">
                <div class="row">
                    @include('flash::message')
                </div>
                <div class="table-responsive" style="font-size: 10px">
                    <table class="table">
                        <tr>
                            <td >No. de maquina</td>
                            <td colspan="2">Folio</td>
                        </tr>
                        @foreach($reservations as $reservation)
                            @if($reservation->status=='1')
                            <tr>

                                <td>
                                    <?php
                                    $equipment = App\Equipment::where('id',$reservation->equipment_id)->first();
                                    echo $equipment->numero_equipo;
                                    ?>
                                </td>
                                <td>{{$reservation->folio}}</td>
                                <td>
                                    <a href="/statusReservation/{{$reservation->id}}/2" class="btn btn-primary btn-sm"><i class="fa fa-check" aria-hidden="true"></i></a>
                                    <a href="/reservation/create/{{$reservation->id}}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                    <a href="#" class="btn btn-danger btn-sm" onclick="cancelar({{$reservation->id}},3);"><i class="fa fa-close" aria-hidden="true"></i></a>
                                    <a href="#"  onclick="mostarMotivo('{{$reservation->comentario}}')">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                </td>

                            </tr>
                            @endif
                        @endforeach
                    </table>
                </div>

            </div>
        </div>
        {{----}}
        <div class="col-md-5">
            <h2 class="text-center">Historial</h2>
            <div class="jumbotron">
                <div class="row">
                    @include('flash::message')
                </div>
                <div class="table-responsive">
                    <table class="table" style="font-size: 10px">
                        <tr>
                            <td>No. de maquina</td>
                            <td>Folio</td>
                            <td>Fecha</td>
                            <td>Horario</td>
                            
                            <td colspan="2"></td>

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
                                <td>{{substr($reservation->fecha_reservacion,0,10)}}</td>
                                <td>{{$utilities->convertFormat12Hour($reservation->hora_inicial)}} a {{$utilities->convertFormat12Hour($reservation->hora_final)}}</td>
                                <td>
                                    @if($reservation->status=='0')
                                        <span class="label label-primary">Pendiente</span>
                                    @elseif($reservation->status=='1')
                                        <span class="label label-primary">Agendado</span>
                                    @elseif($reservation->status=='2')
                                        <span class="label label-primary">Asistió</span>
                                    @elseif($reservation->status=='3')
                                        <a href="#" onclick="mostarMotivo('{{$reservation->comentario}}')">
                                            <span class="label label-danger">Cancelado</span>
                                        </a>
                                    @elseif($reservation->status=='4')
                                        <a href="#" onclick="mostarMotivo('{{$reservation->comentario}}')">
                                            <span class="label label-danger">Rechazado</span>
                                        </a>
                                    @endif
                                </td>
                                @if($reservation->status=='3' || $reservation->status=='4')
                                    <td>
                                        <a href="#" onclick="mostarMotivo('{{$reservation->comentario}}')">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                    </table>

                </div>

            </div>
        </div>


    </div>
    <input type="hidden" id="status_cancelado">
    <input type="hidden" id="reservation_id">
    <!-- Modal -->
    <div id="m_Rechazar" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="color: black">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Rechazar</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="email">Motivo:</label>
                            <textarea name="motivo_cancelado" id="motivo_cancelado" cols="30" rows="10" class="form-control"></textarea>
                        </div>


                        <button type="button" onclick="guardarCancelado()" class="btn btn-default">Aceptar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
                </div>
            </div>

        </div>
    </div>

    <div id="m_MotivoCancelacion" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" style="color: black">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Motivo</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <p id="motivo_cancelacion"></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">cerrar</button>
                </div>
            </div>

        </div>
    </div>

    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script src="https://unpkg.com/vue"></script>
    <script src="{{asset('js/vue-resource.min.js')}}"></script>
    <script src="/js/admin.js"></script>
    <script>
        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
    </script>
@endsection
