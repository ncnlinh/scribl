<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Facebook;
use Facebook\FacebookResponse;
use Facebook\FacebookRequest;
use Socialite;
use Session;

// FacebookSession::setDefaultApplication(env('FACEBOOK_APP_ID'), env('FACEBOOK_APP_SECRET'));

class FacebookController extends Controller
{
    private $fb;
    // private $user;
    private $session;

    public function __construct() {
      $this->middleware('auth', ['only' => 'app']);
      $this->fb = new Facebook\Facebook([
        'app_id' => env('FACEBOOK_APP_ID'),
        'app_secret' => env('FACEBOOK_APP_SECRET'),
        'default_graph_version' => 'v2.4',
      ]);
      $this->session = Session::get('FACEBOOK_SESSION_TOKEN');
    }

    

    public function post(Request $request) {
      try {
        $response = $this->fb->get('/me/permissions', $this->session);
      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        return $e->getMessage();
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        return 'Facebook SDK returned an error: ' . $e->getMessage();
      }

      $permissions = $response->getGraphEdge();
      $publishActionsApproved = false;
      foreach ($permissions as $permissionNode) {
        if (strcmp($permissionNode->getField('permission'), 'publish_actions') == 0) {
          if (strcmp($permissionNode->getField('status'), 'granted') == 0) {
            $publishActionsApproved = true;
            break;
          } else {
            break;
          }
        }
      }

      if (!($publishActionsApproved)) {
        return 'require_publish_actions';
      }

      
      list(, $data) = explode(',', $request->input('data'));
      file_put_contents('/tmp/image.png', base64_decode($data));
      /* POST THE THING */
      $data = [
        'message' => $request->input('message') . "\n Made by Scribbl",
        'data' => $this->fb->fileToUpload('/tmp/image.png'),
        // 'url' => ,
      ];
      
      try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $this->fb->post('/me/photos', $data, $this->session);
      } catch(Facebook\Exceptions\FacebookResponseException $e) {
        return $e->getMessage();
      } catch(Facebook\Exceptions\FacebookSDKException $e) {
        return 'Facebook SDK returned an error: ' . $e->getMessage();
      }

      $graphNode = $response->getGraphNode();
      return 'Photo ID: ' . $graphNode['id'];
    }
}
