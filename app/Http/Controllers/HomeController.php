<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //trigger('my-channel', 'my-event', $data);
       
        
        $userId = Auth::id();
        $user = User::where('id',$userId)->first();
        $isAdmin = $user->hasRole('admin');   // true
        $reservation = Reservation::orderBy('id','desc')
                        ->where('user_id',$userId)->get();

        if ($isAdmin){
            $reservationAdmin = Reservation::orderBy('hora_inicial','asc')
                ->paginate(10);
            $data = array('reservations'=>$reservationAdmin);

            return view('HomeAdmin',$data);
        }else{
            return view('home',array('reservations'=>$reservation));
        }

    }
}
