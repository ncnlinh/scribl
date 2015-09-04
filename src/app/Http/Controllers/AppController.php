<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostManager;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Input;

class AppController extends Controller
{
    public function __construct() {
    }


    public function app() {
      $tag = Input::get('tag');

      if (Auth::check())
        if (isset($tag)) {
          $posts = Post::where('tag', '=', $tag)->get();
          if (sizeof($posts->toArray()) == 0) {
            return redirect('/');
          } else {
            return view('app', ['jsonurl' => $posts[0]['json_url']]);
          }
        } else {
          return view('app');
        }
      return view('welcome');
    }

    public function welcome() {
      return view('welcome');
    }
}
  