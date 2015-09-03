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
        return $this->posts->findByTag($tag);

    }

}