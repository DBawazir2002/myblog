<?php

namespace App\Interfaces\Profile;

interface ProfileServiceInterface
{
    public function getProfiles($pages = 10);

    public function getProfileById(string $id);

    public function getProfileByUserName(string $username);

    public function getProfileByUserId(string $user_id);

    public function createProfile(string $user_id);

    public function updateProfile(string $user_id);

}
