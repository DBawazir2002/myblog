<?php

namespace App\Interfaces\Auth;

interface AuthServiceInterface
{
    public function signup();

    public function signin();

    public function refreshToken();

    public function signout();
}
