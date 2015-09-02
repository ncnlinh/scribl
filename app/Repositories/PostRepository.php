<?php
namespace App\Repositories;
use App\Post;

class PostRepository {
    public function findByTagOrCreate($postData) {
        return Post::firstOrCreate($postData);
    }

    public function findByTag($tag) {
        return Post::where('tag','=',$tag)->get()[0];
    }
}