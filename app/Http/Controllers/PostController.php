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
   *
   * Handle post AJAX POST request
   *
   * @param PostManager $postManager
   * @param Request $request
   * @return mixed
   */
  public function post(PostManager $postManager, Request $request)
  {
    try {
      $response = $this->fb->get('/me/permissions', $this->session);
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
      return response()->json([
        'success' => false,
        'error' =>
          [
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'facebookErrorType' => 'graph_api',
            'facebookErrorCode' => $e->getCode(),
            'message' => $e->getMessage()
          ]
      ]);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
      return response()->json([
        'success' => false,
        'error' =>
          [
            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'facebookErrorType' => 'sdk',
            'facebookErrorCode' => $e->getCode(),
            'message' => $e->getMessage()
          ]
      ]);
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
        'success' => false,
        'error' =>
          [
            'code' => Response::HTTP_METHOD_NOT_ALLOWED,
            'message' => 'need_authorization_publish_actions'
          ]
      ]);
    }


    list(, $data) = explode(',', $request->input('data'));
    list(, $gif) = explode(',', $request->input('gif'));
    $savedFile = $this->saveFile($postManager, $data, $gif);
    $tag = $savedFile['tag'];
    $url = $this->generatePostLink($tag);
    if ($request->input('postToFacebook') == "true") {
      // Upload image to facebook
      $data = [
        'message' => $request->input('message') . "\n Made by Scribbl \n" . $url,
        'data' => $this->fb->fileToUpload('/tmp/image.png'),
      ];

      try {
        // Returns a `Facebook\FacebookResponse` object
        $response = $this->fb->post('/me/photos', $data, $this->session);
      } catch (Facebook\Exceptions\FacebookResponseException $e) {
        return response()->json([
          'success' => false,
          'error' =>
            [
              'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
              'facebookErrorType' => 'graph_api',
              'facebookErrorCode' => $e->getCode(),
              'message' => $e->getMessage()
            ]
        ]);
      } catch (Facebook\Exceptions\FacebookSDKException $e) {
        return response()->json([
          'success' => false,
          'error' =>
            [
              'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
              'facebookErrorType' => 'sdk',
              'facebookErrorCode' => $e->getCode(),
              'message' => $e->getMessage()
            ]
        ]);
      }

      $graphNode = $response->getGraphNode();
      $userId = Auth::user()->id;
      $postManager->post([
        'user_id' => $userId,
        'fb_id' => $graphNode['id'],
        'url' => $url,
        'tag' => $tag,
        'png_url' => $savedFile['pngURL'],
        'gif_url' => $savedFile['gifURL']
      ]);
      return response()->json([
        'success' => true,
        'data' => [
          'fbId' => $graphNode['id'],
          'url' => $url,
          'tag' => $tag,
          'pngUrl' => $savedFile['pngURL'],
          'gifUrl' => $savedFile['gifURL']
        ]
      ]);
    } else {
      $userId = Auth::user()->id;
      $postManager->post([
        'user_id' => $userId,
        'fb_id' => null,
        'url' => $url,
        'tag' => $tag,
        'png_url' => $savedFile['pngURL'],
        'gif_url' => $savedFile['gifURL']
      ]);
      return response()->json([
        'success' => true,
        'data' => [
          'fbId' => null,
          'url' => $url,
          'tag' => $tag,
          'pngUrl' => $savedFile['pngURL'],
          'gifUrl' => $savedFile['gifURL']
        ]
      ]);
    }
  }

  public function get(PostManager $postManager, $id) {
    $post = $postManager->get($id);
    if ($post) {
      $image = base64_encode(Storage::disk('s3')->get(env('APP_ENV') . '/img/' . $post['user_id'] . '/' . $post->tag . '/image.png'));
      return view('app', ['post'=> $post,'image'=> $image]);
    }
    return view('errors/404');
  }

  private function saveFile(PostManager $postManager, $data, $gif) {
    file_put_contents('/tmp/image.png', base64_decode($data));
    $tag = uniqid();
    $userId = Auth::user()->id;
    $storagePathPrefix = "https://s3-ap-southeast-1.amazonaws.com/cs3216-2015-assg1-scribl/";
    $imgPathSuffix = env('APP_ENV').'/img/'.$userId.'/'.$tag.'/image.png';
    $gifPathSuffix = env('APP_ENV').'/img/'.$userId.'/'.$tag.'/image.gif';
    Storage::disk('s3')->put($imgPathSuffix, base64_decode($data));
    Storage::disk('s3')->put($gifPathSuffix, base64_decode($gif));
    return [
      'tag' => $tag,
      'pngURL' => $storagePathPrefix . $imgPathSuffix,
      'gifURL' => $storagePathPrefix . $gifPathSuffix,
    ]
      ;

  }

  private function generatePostLink($tag) {
    return env('APP_URL') . '/scribbl/'.  $tag;
  }

}
