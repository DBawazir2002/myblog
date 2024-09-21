<?php
namespace App\Providers;

use App\Interfaces\Auth\AuthServiceInterface;
use App\Interfaces\Favorite\FavoriteServiceInterface;
use App\Interfaces\Follow\FollowServiceInterface;
use App\Interfaces\Like\LikeServiceInterface;
use App\Interfaces\Post\PostRepositoryInterface;
use App\Interfaces\Post\PostServiceInterface;
use App\Interfaces\Profile\ProfileRepositoryInterface;
use App\Interfaces\Profile\ProfileServiceInterface;
use App\Interfaces\User\UserRepositoryInterface;
use App\Interfaces\User\UserServiceInterface;
use App\Interfaces\Verification\VerificationServiceInterface;

use App\Repositories\PostRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\UserRepository;

use App\Services\AuthService;
use App\Services\FavoriteService;
use App\Services\FollowService;
use App\Services\LikeService;
use App\Services\PostService;
use App\Services\ProfileService;
use App\Services\UserService;
use App\Services\VerificationService;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        $this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);

        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);

        $this->app->bind(LikeServiceInterface::class, LikeService::class);

        $this->app->bind(FavoriteServiceInterface::class, FavoriteService::class);

        $this->app->bind(FollowServiceInterface::class, FollowService::class);

        $this->app->bind(VerificationServiceInterface::class, VerificationService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
