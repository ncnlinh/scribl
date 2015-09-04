<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AuthenticateUser;
use App\AuthenticateUserListener;

class AuthController extends Controller implements AuthenticateUserListener{
    public function login(AuthenticateUser $authenticateUser, Request $request) {
      return $authenticateUser->login($request->has('code'), $this);
    }
    public function logout(AuthenticateUser $authenticateUser) {
      return $authenticateUser->logout($this);
    }
    public function userHasLoggedIn($user){
      return redirect()->back();
    }
    public function userHasLoggedOut(){
      return redirect('/');
    }
}
