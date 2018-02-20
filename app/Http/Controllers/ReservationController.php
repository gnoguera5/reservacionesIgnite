<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Reservation;
use App\CatalogHour;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use DateTime;
use App\Equipment;
use App\Helpers\Utilities;
use Illuminate\Mail\Mailer;
use App\Mail\SendMail;
use Pusher\Pusher;

use function PHPSTORM_META\type;

class ReservationController extends Controller
{
    protected $utilities;
    protected $mailer;
    protected $messageBag;

    public function __construct(MessageBag $messageBag, Utilities $utilities,Mailer $mailer)
    {
        $this->messageBag = $messageBag;
        $this->middleware('auth');
        $this->utilities = $utilities;
        $this->mailer = $mailer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservation = Reservation::orderBy('id','desc')
            ->paginate(10);
        $data = array('reservations'=>$reservation);


        return view('Reservation',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($reserva_id=0)
    {
        $data                         = array();
        $data['fecha_reservacion']    = '';
        $data['user_id']              = '';
        $data['reserva_id']           = '';
        $data['fecha_reservacion']    = '';
        $data['hora_inicial']         = '';
        $data['hora_final']           = '';
        $data['comentario']           = '';

        if($reserva_id!=0){
            $reserva = Reservation::where('id',$reserva_id)->first();
            $data['fecha_reservacion'] = $reserva->fecha_reservacion;
            $data['user_id']           = $reserva->user_id;
            $data['reserva_id']        = $reserva->id;
            $data['fecha_reservacion'] = $reserva->fecha_reservacion;
            $data['hora_inicial']      = $this->utilities->convertFormat12Hour($reserva->hora_inicial);
            $data['hora_final']        = $this->utilities->convertFormat12Hour($reserva->hora_final);
            $data['comentario']        = $reserva->comentario;

        }

        return view('Reservation',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fecha_reservacion = $request->fecha_reservacion;
        $hora_inicial = $request->hora_inicial;
        $hora_final = $request->hora_final;
        $key_css = $request->key_css;

        $user_asign = $request->user_id;
        if($user_asign!=0){
            $userId = $user_asign;
        }else{
            $userId = Auth::id();
        }
        
        $equipment = Equipment::where('key_css',$key_css)->first();

        if($user_asign!=0){
            $vreserva_id = $request->vreserva_id;
            if(count($equipment)==0){
                $this->messageBag->add('key_css', 'Debe seleccionar un equipo');
                return redirect('reservation/create/'.$vreserva_id)->withErrors($this->messageBag);
            }
        }
       
       
        /*generar folio*/
        $getReservation = Reservation::orderBy('id','desc')->first();
        if(isset($getReservation->id)){

            $reservation_id_serial = $getReservation->id + 1;
        }else{
            $reservation_id_serial = 1;
        }
        $folio= substr(md5(time().$reservation_id_serial),0,5);

        if($user_asign!=0){
            $reserva_id  = $request->vreserva_id;
            $reservation = Reservation::where('id',$reserva_id)->first();
            $reservation->comentario = $request->comentario;
        }else{
            $reservation = new Reservation();
        }
        $reservation->user_id= $userId;
        $reservation->equipment_id= $equipment->id;
        $reservation->fecha_reservacion= $fecha_reservacion;
        $reservation->hora_inicial= $hora_inicial;
        $reservation->hora_final= $hora_final;
        $reservation->folio=$folio;
        
        $user = User::where('id',$userId)->first();

        $isAdmin = $user->hasRole('admin');   // true
        if ($isAdmin){
            $reservation->status='1';
            $contenido1="$user->name su reserva ha sido realizada";
            $contenido2 = "";
        }else{
            $contenido1= "$user->name su reserva fue realizada en espera de ser aprobada por algún administrador ";
            $contenido2= "Cuando el administrador apruebe su reservacion recibira un correo";
        }

        if($user_asign!=0){
            $reservation->update();
            
        }else{
            $reservation->save();
        }
        

        /*mandar push*/

        $valores_reserva=[];
        $reservaciones = Reservation::where('status','0')
            -> orderBy('hora_inicial', 'asc')->get();
        foreach ($reservaciones as $reservacion){
            $equipment = Equipment::where('id',$reservacion->equipment_id)->first();
            $valores_reserva[]= array('id'=>$reservacion->id,'equipo'=>$equipment->numero_equipo);
        }

        $dataPush = array('message'=>'1','data'=>$valores_reserva);

        $options = array(
            'cluster' => 'us2',
            'encrypted' => true,
        );
        $pusher = new Pusher('350f997669c4bed22625','6e1651f4e3553b5cb883','394046',$options);
        $pusher->trigger('my-channel', 'my-event', $dataPush);


        flash('La reservacion ha sido guardada')->success();


        $logo= 'http://ignite31.herokuapp.com/img/logo50.png';
        $nombre_cliente = "juan sosa azcorra" ;

        $data = array('logo'=>$logo,'contenido1'=>$contenido1,'contenido2'=>$contenido2,  'subject'=>'Cotizacion'
        );
        $correo_cliente = "manuelsansoresg@gmail.com";
        //$this->mailer->to($correo_cliente)->send(new SendMail($data));
        
        return redirect('/');

    }

    public function getPendientes()
    {
        $id=[];
        $equipo=[];
        $valores=[];
        $reservaciones = Reservation::where('status','0')
                    -> orderBy('hora_inicial', 'asc')->get();
        foreach ($reservaciones as $reservacion){
            $id[]=$reservacion->id;
            $equipment = Equipment::where('id',$reservacion->equipment_id)->first();
            $equipo[] =$equipment->numero_equipo;
            $valores[]= array('id'=>$reservacion->id, 'comentario'=> $reservacion->comentario , 'folio'=> $reservacion->folio ,  'equipo'=>$equipment->numero_equipo);
        }
        return response($valores);
    }

    public function statusReservation($reservation_id,$status,$comment='')
    {
        $reservation = Reservation::where('id',$reservation_id)->first();
        $reservation->status=$status;
        $reservation->comentario=$comment;
        $reservation->update();
        flash('La reservacion ha sido actualizada')->important();
        if ($comment==""){
            return redirect(route('home'));
        }else{
            /*enviar correo*/
            $logo= 'http://ignite31.herokuapp.com/img/logo50.png';
            switch ($status){
                case '3':
                    $contenido1= "has cancelado la reservacion";
                    $contenido2="Motivo: $comment";
                    break;
                case'4':
                    $contenido1= "La reservacion ha sido rechazada";
                    $contenido2="Motivo: $comment";
                    break;
                case'1':
                    $contenido1= "La reservacion ha sido agendada";
                    $contenido2="";
                    break;
            }
            $data_email = array('logo'=>$logo,'contenido1'=>$contenido1,'contenido2'=>$contenido2,  'subject'=>'Cotizacion'
            );
            $correo_cliente = "manuelsansoresg@gmail.com";
            //$this->mailer->to($correo_cliente)->send(new SendMail($data_email));
            $data= array('ok'=>true);
            return response($data);
        }


    }


    public function getStyles()
    {
        $equipment = Equipment::all();
        $keys = array();
        $button_circle = array();
        foreach ($equipment as $value) {
            $keys[]=$value->key_css;
            $button_circle[]=$value->circle_number;
        }
        return response(array('keys'=>$keys,'button_circle'=>$button_circle));
    }

    public function getStyleByKey($key_css)
    {
        # code...
        $equipment = Equipment::all();
        $key = [];
        $class = [];
        foreach ($equipment as $value) {
            $key[]= $value->key_css;
            $get_class = $value->class;
            $class[] = explode(',',$get_class);
        }
        #obtener el equipo que se selecciono para tener los estilos 
        $equipment_selected = Equipment::where('key_css',$key_css)->first();
        $key_selected = $equipment_selected->key_css;
        $class_selected = explode(',',$equipment_selected->class);
        $data=array('key_css'=>$key,'clase'=>$class, 'key_selected'=>$key_selected,'class_selected'=>$class_selected);
        return response($data);
    }

    public function getHours($fecha)
    {
        $day_of_week = date('w', strtotime($fecha));
        $getHours = CatalogHour::where('dias','like',"%$day_of_week%")->first();
        $total = count($getHours);
        $horario = explode(',',$getHours->horario);
        $nHorario = array();

        $hora_final = date('h:ia');
        //dd($hora_final);
       // echo $hora_final;
        $hora_final=date('H:i:s',strtotime("$hora_final - 30 minutes"));
        //dd($hora_final);
        $rango = $this->calculaRango($horario[0],$hora_final);
        
        foreach ($rango as $value_rango) {
            $key = array_search($value_rango,$horario,TRUE);
            try{
                unset($horario[$key]);
            }catch(Exception $e){
            }
            $cont_rango=0;
        }
        foreach ($horario as $value_horario) {
            $nHorario[]= $value_horario;
        }
        $data = array('total'=>$total,'horario'=>$nHorario);
        return response($data);

    }

    public function getEquipos($fecha,$hora_inicial,$hora_final)
    {
        $key_equipment= array();
        $css_equipment = array();
        $keys = array();
        $class_selected = array();
        $disponibles = array();

        $hora_inicial=date('H:i:s',strtotime("$hora_inicial + 30 minutes"));
        $rangos = $this->calculaRango($hora_inicial,$hora_final);

        if($hora_inicial!='0' && $hora_final!='0'){
            $hora_inicial = date('H:i:s',strtotime($this->utilities->convertFormat24Hour($hora_inicial)));
            $hora_final = date('H:i:s',strtotime($this->utilities->convertFormat24Hour($hora_final)));
            $equipmentsAll = Equipment::all();

            foreach ($equipmentsAll as $value) {
                $key_equipment[] = $value->key_css;
                $css_equipment [$value->key_css] = $value->circle_number;
            }
            //DB::enableQueryLog();
            
            $reservations = Reservation::where('fecha_reservacion',$fecha)->get();
            $equipments_id = [];
            foreach ($reservations  as  $reservation) {
                $rangos_sql = $this->calculaRango($reservation->hora_inicial,$reservation->hora_final);
                foreach ($rangos    as $rango) {
                    foreach ($rangos_sql as $rango_sql) {
                        if($rango_sql==$rango){
                            $key_equipment_id = array_search($reservation->equipment_id,$equipments_id,TRUE);
                            if($key_equipment_id===false){
                                $equipments_id[] = $reservation->equipment_id;
                            }
                        }
                    }
                }
            }
            foreach ($equipments_id as $equipment_id) {
                $equipments = Equipment::where('id',$equipment_id)->first();
                $keys[] = $equipments->key_css;
                $class_selected[] = explode(',',$equipments->class);
                $key = array_search($equipments->key_css,$key_equipment,TRUE);
                try{
                    unset($key_equipment[$key]);
                }catch(Exception $e){
                }
            }
          
           
            
            foreach ($key_equipment as $row_equipment){
                $disponibles[] = $row_equipment;
            }
        }

        //dd($hora_final);
        /*obtener lista de equipos*/

        //dd($css_equipment);


        return response(array('ocupados'=>$keys,'class_selected'=>$class_selected,'disponibles'=>$disponibles,
                        'css_equipment'=>$css_equipment
            ));
    }

    public function getDisponible_bk($fecha,$key_css)
    {
        $equipment = Equipment::where('key_css',$key_css)->first();
        $equipment_id = $equipment->id;

        $reservations = Reservation::where('fecha_reservacion',$fecha)
                        ->where('equipment_id',$equipment_id)

                        ->where('fecha_reservacion',$fecha)->get();
        $hora_inicial= [];
        $hora_final= [];
        $rango = [];
        foreach ($reservations as $reservation) {
            $hora_inicial[] = $this->utilities->convertFormat12Hour($reservation->hora_inicial);
            $hora_final[]   = $this->utilities->convertFormat12Hour($reservation->hora_final);
            $rango[] = ($this->calculaRango($reservation->hora_inicial,$reservation->hora_final));
        }
        //dd($rango);
        $total = count($reservations);
        $day_of_week = date('w', strtotime($fecha));
        
        $getHours = CatalogHour::where('dias','like',"%$day_of_week%")->first();
        $horario = explode(',',$getHours->horario);

        $cont_rango=0;
        foreach ($rango as $value_rango) {
            $total_rango =  count($value_rango);
            foreach ($value_rango as $value_hora) {
                $cont_rango= $cont_rango+1;
                if($cont_rango<$total_rango){
                    $key = array_search($value_hora,$horario,TRUE);
                    try{
                        unset($horario[$key]);
                    }catch(Exception $e){
                    }
                }
            }
            $cont_rango=0;
        }

        $data = array('hora_inicial'=>$hora_inicial,'hora_final'=>$hora_final, 'total'=>$total,'horario'=>$horario,
                    'rango'=>$rango
                    );
        return response($data);
    }

    public function getHourFinal($fecha,$hora_final)
    {
        $day_of_week = date('w', strtotime($fecha));
        $getHours = CatalogHour::where('dias','like',"%$day_of_week%")->first();
        $horario = explode(',',$getHours->horario);
        $horario_new = array();
        foreach ($horario as $value) {

            $inicial = date('H:i:s',strtotime($value));
            $final = date('H:i:s',strtotime($hora_final));

            if($inicial>$final){
                $horario_new[]=$value;
            }
        }
        $data = array('horario'=>$horario_new);
        return response($data);
        /* foreach ($rangos as $rango) {

            try{
                $key = array_search($rango,$horario,TRUE);
                $horario_new[]=$horario[$key];
            }catch(Exception $e){
            }

        }
        */

    }

    public function getHourFinal_bk($fecha,$key_css,$hora_final)
    {

        $equipment = Equipment::where('key_css',$key_css)->first();
        $equipment_id = $equipment->id;
        $day_of_week = date('w', strtotime($fecha));

        $getHours = CatalogHour::where('dias','like',"%$day_of_week%")->first();
        $horario = explode(',',$getHours->horario);

        $reservation= Reservation::where('hora_inicial','>',$hora_final)->first();
        $total_reservation = count($reservation);
        $horario_new = [];
        if ($total_reservation>0){
            $hora_inicial = $this->utilities->convertFormat12Hour($reservation->hora_inicial);

            $rangos = $this->calculaRango($hora_final,$hora_inicial);
            $total_rango = count($rangos);
           // dd($rangos);
            foreach ($rangos as $rango) {

                try{
                    $key = array_search($rango,$horario,TRUE);
                    $horario_new[]=$horario[$key];
                }catch(Exception $e){
                }

            }

            try{
               unset($horario_new[0]);
            }catch(Exception $e){
            }

        }else{
            #no se encontro reserva proxima
            foreach ($horario as $value_rango) {
                $inicial = date('H:i:s',strtotime($value_rango));
                $final = date('H:i:s',strtotime($hora_final));
                if($inicial>=$final){
                    try{
                        $key = array_search($value_rango,$horario,TRUE);
                        $horario_new[]=$horario[$key];
                    }catch(Exception $e){
                    }
                }
            }
            try{
                unset($horario_new[0]);
            }catch(Exception $e){
            }
        }
        $data = array('horario'=>$horario_new);
        return response($data);
    }

    public function calculaRango($inicial,$final,$bfinal=false)
    {
        $rango = [];
        $inicial = date('H:i:s',strtotime($inicial));
        $final = date('H:i:s',strtotime($final));
        $rango[]=$this->utilities->convertFormat12Hour($inicial);

        if ($bfinal==false){
            while ($inicial < $final) {
                $inicial=date('H:i:s',strtotime("$inicial + 30 minutes"));
                $rango[] = $this->utilities->convertFormat12Hour($inicial);
            }

        }else{
            
            while ($inicial <= $final) {
                $inicial=date('H:i:s',strtotime("$inicial + 30 minutes"));
                $rango[] = $this->utilities->convertFormat12Hour($inicial);
            }
        }
       return $rango;
    }

    /**
     * Undocumented function
     * @param int $asiento  
     * @return void
     */
    public function getDataReservations($fecha)
    {
        
        $asiento =2; 
        $reservations = Reservation::where('fecha_reservacion',$fecha)
                        ->where('equipment_id',$asiento)
                        ->orderBy('hora_inicial', 'asc')
                        ->get();
        $hour_ini_selected = [];
        $hour_fin_selected = [];
        $hour_selected = [];
        $equipmentSelect = [];
        $cont = 0;
        
        foreach ($reservations as $reservation) {
            $cont= $cont+2;
            $hour_selected[] = $this->utilities->convertFormat12Hour($reservation->hora_inicial);
            $hour_selected[] = $this->utilities->convertFormat12Hour($reservation->hora_final);
            $hour_ini_selected[] = $this->utilities->convertFormat12Hour($reservation->hora_inicial);
            $hour_fin_selected[] = $this->utilities->convertFormat12Hour($reservation->hora_final);
            $equipmentSelect[] = $reservation->equipment_id;
        }
        $name_day =  jddayofweek ( cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")) , 1 ); 
        $day_of_week = date('N', strtotime($name_day));
        $getHours = CatalogHour::where('dias','like',"%$day_of_week%")->first();
        $horario = explode(',', $getHours->horario);
        $nval = [];

        $data = array('hour_ini_selected'=>$hour_ini_selected,'hour_fin_selected'=>$hour_fin_selected,
                    'hours'=>$horario,'total'=>$cont,'equipmentSelect'=>$equipmentSelect
                    );
       
        return response($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function Reservadas($fecha="")
    {
        if($fecha==""){
            $reservation = Reservation::orderBy('id','desc')
                ->where('status','2')
                ->paginate(15);
        }else{
            $reservation = Reservation::orderBy('id','desc')
                ->where('status','2')
                ->where('fecha_reservacion',$fecha)
                ->paginate(15);
        }

        $data = array('reservations'=>$reservation,'fecha'=>$fecha);
        return view('Reservaciones',$data);
    }
    public function testCorreo()
    {
        $logo= 'http://ignite31.herokuapp.com/img/logo50.png';
        $nombre_cliente = "juan sosa azcorra" ;
        $correo_cliente= 'manuelsansoresg@gmail.com';
        $link_cotizacion = "http://ignite31.herokuapp.com/pdf/e8d40.pdf";
        $data = array('logo'=>$logo,'contenido1'=>'asdsd','contenido2'=>'contenido2',  'subject'=>'Cotizacion'
        );

        return view('EmailNotification',$data);

    }
}
