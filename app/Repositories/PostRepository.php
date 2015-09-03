<?php
namespace App\Repositories;
use App\Post;

class PostRepository {
    public function findByTagOrCreate($postData) {
        return Post::firstOrCreate($postData);
    }

    public function findByTag($tag) {
        $posts = Post::where('tag','=',$tag)->get();
        if (count($posts->toArray()) == 1)
            return Post::where('tag','=',$tag)->get()[0];
        else return null;
    }
}