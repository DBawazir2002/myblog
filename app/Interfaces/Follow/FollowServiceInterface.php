<?php

namespace App\Interfaces\Follow;

interface FollowServiceInterface
{
    public function getFollowers($pages = 10);

    public function getFollowing($pages = 10);

    public function follow(string $user_id);

    public function unfollow(string $user_id);
}
