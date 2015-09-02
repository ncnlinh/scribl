<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use Facebook;
use Illuminate\Support\Facades\Auth;
use Storage;
use Socialite;
use Session;
use App\PostManager;

class PostController extends Controller
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


  /**
   * Handle post AJAX request
   *
   * @param PostManager $postManager
   * @param Request $request
   * @return int|mixed|string
   */
  public function post(PostManager $postManager, Request $request) {
    try {
      $response = $this->fb->get('/me/permissions', $this->session);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      return response()->json([
          'success'=>false,
          'error'=>
              [
                  'code'=>Response::HTTP_INTERNAL_SERVER_ERROR,
                  'facebookErrorType'=>'graph_api',
                  'facebookErrorCode'=>$e->getCode(),
                  'message'=>$e->getMessage()
              ]
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      return response()->json([
          'success'=>false,
          'error'=>
              [
                  'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                  'facebookErrorType'=>'sdk',
                  'facebookErrorCode'=>$e->getCode(),
                  'message'=>$e->getMessage()
              ]
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
      return response()->json([
          'success'=>false,
          'error'=>
              [
                  'error'=>Response::HTTP_METHOD_NOT_ALLOWED,
                  'message'=>'need_authorization_publish_actions'
              ]
      ], Response::HTTP_METHOD_NOT_ALLOWED);
    }


    list(, $data) = explode(',', $request->input('data'));
    $tag = $this->saveFile($postManager, $data);
    $url = $this->generatePostLink($tag);
    $data = [
      'message' => $request->input('message') . "\n Made by Scribbl \n" . $url,
      'data' => $this->fb->fileToUpload('/tmp/image.png'),
    ];

    try {
      // Returns a `Facebook\FacebookResponse` object
      $response = $this->fb->post('/me/photos', $data, $this->session);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
      return response()->json([
          'success'=>false,
          'error'=>
              [
                  'code'=>Response::HTTP_INTERNAL_SERVER_ERROR,
                  'facebookErrorType'=>'graph_api',
                  'facebookErrorCode'=>$e->getCode(),
                  'message'=>$e->getMessage()
              ]
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
      return response()->json([
          'success'=>false,
          'error'=>
              [
                  'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                  'facebookErrorType'=>'sdk',
                  'facebookErrorCode'=>$e->getCode(),
                  'message'=>$e->getMessage()
              ]
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    $graphNode = $response->getGraphNode();
    $userId = Auth::user()->id;
    $post = $postManager->post([
      'user_id' => $userId,
      'fb_id' => $graphNode['id'],
      'url' => $url,
      'tag' => $tag,
    ]);
    return response()->json([
        'success'=>true,
        'data' => [
            'fbId' => $graphNode['id'],
            'url' => $url,
            'tag'=> $tag
        ]
    ]);
  }

  public function getPostLink(PostManager $postManager, Request $request) {
    $post = $postManager->get($request->input('tag'));
    if ($post) {
      return response()->json(['success'=>true, 'data'=>['postURL' => $this->generatePostLink($post)]]);
    } else {
      return response()->json(['success'=>false, 'error'=>['code'=>Response::HTTP_NOT_FOUND]]);
    }
  }

  public function get(PostManager $postManager, $id) {
    return $postManager->get($id);
  }

  private function saveFile(PostManager $postManager, $data) {
    file_put_contents('/tmp/image.png', base64_decode($data));
    $tag = uniqid();
    $userId = Auth::user()->id;
    Storage::disk('s3')->put(env('APP_ENV').'/img/'.$userId.'/'.$tag.'/image.png', base64_decode($data));
    return $tag;

  }

  private function generatePostLink($tag) {
    return env('APP_URL') . '/scribbl/'.  $tag;
  }

}
