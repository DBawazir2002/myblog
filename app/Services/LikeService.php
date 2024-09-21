<?php

namespace App\Services;

use App\Interfaces\Like\LikeServiceInterface;
use App\Interfaces\Post\PostServiceInterface;

class LikeService implements LikeServiceInterface
{
    private $postService;
    public function __construct(PostServiceInterface $postService){
        $this->postService = $postService;
    }

    public function getLikedPostsByUser($pages = 10)
    {
        $user = auth()->user();
        $posts = $user->likes()->paginate($pages);
        return $posts;
    }

    public function getLikedUsersByPost($pages = 10,string $post_id)
    {
        $users = null;
       if( $post = $this->postService->getPostById($post_id)){
           $users = $post->likes()->paginate($pages);
       }
       return $users;
    }

    public function addLike(string $post_id)
    {
        $user = auth()->user();
        $is_liked = false;
        if ($user->likes()->where('post_id', $post_id)->get()->count() === 0) {
            $user->likes()->attach($post_id);
            $is_liked = true;
        }
        return $is_liked;
    }

    public function removeLike(string $post_id)
    {
        $user = auth()->user();
        $is_unliked = false;
        if ($user->likes()->where('post_id', $post_id)->get()->count() != 0) {
            $user->likes()->detach($post_id);
            $is_unliked = true;
        }
        return $is_unliked;
    }
}
