<?php

namespace App\Services;

use App\Interfaces\Favorite\FavoriteServiceInterface;
use App\Interfaces\Post\PostServiceInterface;

class FavoriteService implements FavoriteServiceInterface
{
    private $postService;
    public function __construct(PostServiceInterface $postService){
        $this->postService = $postService;
    }

    public function getFavoritePostsByUser($pages = 10)
    {
        $user = auth()->user();
        $posts = $user->favorites()->paginate($pages);
        return $posts;
    }

    public function getFavoriteUsersByPost($pages = 10,string $post_id)
    {
        $users = null;
        if( $post = $this->postService->getPostById($post_id)){
            $users = $post->favorites()->paginate($pages);
        }
        return $users;
    }

    public function addFavorite(string $post_id)
    {
        $user = auth()->user();
        $is_favorite = false;
        if ($user->favorites()->where('post_id', $post_id)->get()->count() === 0) {
            $user->favorites()->attach($post_id);
            $is_favorite = true;
        }
        return $is_favorite;
    }

    public function removeFavorite(string $post_id)
    {
        $user = auth()->user();
        $is_removed = false;
        if ($user->favorites()->where('post_id', $post_id)->get()->count() != 0) {
            $user->favorites()->detach($post_id);
            $is_removed = true;
        }
        return $is_removed;
    }
}
