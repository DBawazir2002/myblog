<?php

namespace App\Interfaces\User;

use App\Models\User;

interface UserServiceInterface
{
    public function getAllUsers();

    public function getUserById(string $id);

    public function getUserByPhone(string $phone);

    public function createUser();

    public function updateUser(string $id);

}
