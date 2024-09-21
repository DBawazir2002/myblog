<?php

namespace App\Interfaces\User;

use App\Models\User;

interface UserRepositoryInterface
{
    public function getAll();
    public function getById(string $id);

//    public function getByUserName(string $email);

    public function getByPhone(string $phone);

    public function create(array $data);
    public function update(User $user, array $data);
}
