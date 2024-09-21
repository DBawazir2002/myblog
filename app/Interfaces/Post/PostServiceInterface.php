<?php

namespace App\Interfaces\Post;

use App\Models\Post;

interface PostServiceInterface
{
    public function getAllPosts($pages = 10, string $user_id = null);
    public function getPostById(string $id);
    public function getPostByTitle(string $title);

    public function getPostsByUserId($pages = 10,string $user_id);

    public function getPostByUserId(string $user_id);

    public function createPost();

    public function updatePost(Post $post);

    public function deletePost(Post $post);
}
