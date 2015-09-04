<?php
namespace App;

use Illuminate\Contracts\Auth\Guard as Authenticator;
use Laravel\Socialite\Contracts\Factory as Socialite;
use App\Repositories\UserRepository;
use App\AuthenticateUserListener;
use Session;

class AuthenticateUser{

  private $users;
  private $socialite;
  private $auth;

  public function __construct(UserRepository $users, Socialite $socialite, Authenticator $auth){
    $this->users = $users;
    $this->socialite = $socialite;
    $this->auth = $auth;
  }

  public function login($hasCode, AuthenticateUserListener $listener){
    if (!$hasCode) return $this->getAuthorizationFirst();
    
    $user = $this->users->findByUsernameOrCreate($this->getFacebookUser());

    $this->auth->login($user, true);

    return $listener->userHasLoggedIn($user);

  }

  public function logout(AuthenticateUserListener $listener) {
    $this->auth->logout();
    return $listener->userHasLoggedOut();
  }

  private function getAuthorizationFirst(){
    return $this->socialite->driver('facebook')->redirect();
  }

  private function getFacebookUser() {
    $fbUser = $this->socialite->driver('facebook')->user();
    Session::set('FACEBOOK_SESSION_TOKEN', $fbUser->token);
    return $fbUser;
  }
}