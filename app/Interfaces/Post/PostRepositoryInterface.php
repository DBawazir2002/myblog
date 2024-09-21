<?php

namespace App\Interfaces\Post;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function getAll($pages = 10, string $user_id = null);
    public function getById(string $id);
    public function getByTitle(string $title);

    public function getByUserId($pages = 10,string $user_id);

    public function create(array $data);

    public function update(Post $post, array $data);

    public function delete(Post $post);

}
