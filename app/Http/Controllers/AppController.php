<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class AppController extends Controller
{
    public function __construct() {
    }
  
    public function app() {
      if (Auth::check())
        return view('app');
      return view('welcome');
    }

    public function welcome() {
      return view('welcome');
    }
}
  