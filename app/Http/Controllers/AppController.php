<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class AppController extends Controller
{
    public function __construct() {
      $this->middleware('auth', ['only' => 'app']);
    }
    public function app() {
      return view('app');
    }
    public function welcome() {
      return 'Hi guest. '.link_to('login', 'Login with Facebook');
    }
}
