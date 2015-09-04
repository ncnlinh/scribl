<?php namespace App;

interface AuthenticateUserListener {
  
  public function userHasLoggedIn($user);
  public function userHasLoggedOut();
}