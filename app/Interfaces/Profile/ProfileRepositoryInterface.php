<?php

namespace App\Interfaces\Profile;

interface ProfileRepositoryInterface
{
    public function getAll($pages = 10, string $user_id = null);
    public function getById(string $id);
    public function getByUserName(string $username);

    public function getByUserId(string $user_id);

    public function create(array $data);
    public function update(string $user_id, array $data);
}
