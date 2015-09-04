<?php 
namespace App\Repositories;
use App\User;

class UserRepository {
  public function findByUsernameOrCreate($userData)
  {
    return User::firstOrCreate([
      'username'  =>  $userData->nickname,
      'email'     =>  $userData->email,
      'avatar'    =>  $userData->avatar,
      'fbid'      =>  $userData->id,
      'name'      =>  $userData->name
    ]);
  }
}