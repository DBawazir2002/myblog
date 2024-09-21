<?php

namespace App\Interfaces\Favorite;

interface FavoriteServiceInterface
{
    public function getFavoritePostsByUser($pages = 10);

    public function getFavoriteUsersByPost($pages = 10,string $post_id);

    public function addFavorite(string $post_id);

    public function removeFavorite(string $post_id);
}
