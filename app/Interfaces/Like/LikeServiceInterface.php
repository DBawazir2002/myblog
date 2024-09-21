<?php

namespace App\Interfaces\Like;

interface LikeServiceInterface
{
    public function getLikedPostsByUser($pages = 10);

    public function getLikedUsersByPost($pages = 10,string $post_id);

    public function addLike(string $post_id);

    public function removeLike(string $post_id);
}
