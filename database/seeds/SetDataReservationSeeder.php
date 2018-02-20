<?php

use Illuminate\Database\Seeder;
use App\Reservation;
use App\User;
use App\Equipment;

class SetDataReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('id',1)->first();
        $equipment = Equipment::where('id',2)->first();

        $hora = date('H:i');
        $nuevaHora = strtotime ( '+30 minute' , strtotime ( $hora ) ) ;
        $nuevaHora = date ( 'H:i' , $nuevaHora );

        $fecha = date('Y-m-d');


        $reservation = new Reservation();
        $reservation->user_id=$user->id;
        $reservation->equipment_id=$equipment->id;
        $reservation->fecha_reservacion= date('Y-m-d');
        $reservation->hora_inicial=$hora;
        $reservation->hora_final= $nuevaHora;
        $reservation->save();

        $reservation = new Reservation();
        $reservation->user_id=$user->id;
        $reservation->equipment_id=$equipment->id;
        $reservation->fecha_reservacion= $fecha;
        $reservation->hora_inicial=$hora;
        $reservation->hora_final= $nuevaHora;
        $reservation->save();


    }
}
