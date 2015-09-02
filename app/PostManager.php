<?php
namespace App;

use App\Repositories\PostRepository;
use Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostManager{

    private $posts;

    public function __construct(PostRepository $posts){
        $this->posts = $posts;
    }

    public function post($postData) {
        $post = $this->posts->findByTagOrCreate($postData);
        return $post;
    }

    public function get($tag) {
        $post = $this->posts->findByTag($tag);
        if ($post) {
          $image = base64_encode(Storage::disk('s3')->get(env('APP_ENV') . '/img/' . $post['user_id'] . '/' . $tag . '/image.png'));
          return redirect('/')->with('post', $post)->with('image', $image);
        }
        return view('errors/404');
    }

}