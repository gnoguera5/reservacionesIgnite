<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class LoginFacebookController extends Controller
{
   public function login($userIDFb,$name,$status,$email)
   {
        $search_user = $this->searchUser($email,$userIDFb);

        if(count($search_user)==0){
            $user = new User();
            $user->name= $name;
            $user->email = $email;
            $user->password = bcrypt('abc123');
            $user->status = $status;
            $user->type = '1';
            $user->userIDFb= $userIDFb;
            $user->save();
        }

        $search_user = $this->searchUser($email,$userIDFb);
        $auth = $this->authenticate($email,'abc123');
        if($auth){
            $id = Auth::user()->id;
            session(['name'=>$search_user->name]);
        }

        return response(array('status'=>'ok'));
   }

   public function searchUser($email,$userIDFb)
   {
        $search_user = User::where('email',$email)
                        ->where('userIDFb',$userIDFb)
                        ->first();  

        return $search_user;
   }

   public function authenticate($email,$password)
   {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            
        return true;
        }else{
            return false;
        }
   }
}
