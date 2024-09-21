<?php

namespace App\Services;

use App\Interfaces\Follow\FollowServiceInterface;
use App\Interfaces\User\UserServiceInterface;

class FollowService implements FollowServiceInterface
{

    private $userService;
    public function __construct(UserServiceInterface $userService){
        $this->userService = $userService;
    }

    public function getFollowers($pages = 10)
    {
        $user = auth()->user();
        $followers = $user->followers()->paginate($pages);

        return $followers;
    }

    public function getFollowing($pages = 10)
    {
        $user = auth()->user();
        $following = $user->follows()->paginate($pages);

        return $following;
    }

    public function follow(string $user_id)
    {
        if ($user = $this->userService->getUserById($user_id)) {
            if (auth()->user()->id != $user->id) {
                auth()->user()->follow($user);
                return true;
            }
        }
        return false;
    }

    public function unfollow(string $user_id){
        if ($user = $this->userService->getUserById($user_id)) {
                auth()->user()->unfollow($user);
                return true;
            }
        return false;
    }
}
