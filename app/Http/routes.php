<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'AppController@app');

Route::get('welcome', 'AppController@welcome');

Route::get('login', 'AuthController@login'); 

Route::get('logout', 'AuthController@logout');

Route::post('facebook/post', 'PostController@post');

Route::get('scribbl/{uid}/{id}', 'PostController@get');