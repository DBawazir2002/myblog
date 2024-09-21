<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use \Illuminate\Support\Facades\Request;
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function($router){
        
        $router->get('/', function () use ($router) {
        //    return $router->app->version();
            return ;
        });
        
        // Authentication
        $router->post('/signup',['uses' => 'AuthController@signup']);
        $router->post('/signin',['uses' => 'AuthController@signin']);
        
        $router->post('/refresh', [
            'middleware' => 'jwt.refresh',
            'uses' => 'AuthController@refresh'
            ]);
        
        // validation phone
        $router->post('/phone/verify',['uses' => 'VerificationController@verifySMSCode']);
        
        $router->group(['middleware' => ['jwt', 'verified'] ], function () use ($router) {
            $router->get('/check-time-token', function () {
                return response()->json(auth()->check());
            });
        
            $router->post('/signout', ['uses' => 'AuthController@signout']);

            // profiles
            $router->get('/profiles',['uses' => 'ProfileController@index']);
            $router->post('/profiles',['uses' => 'ProfileController@store']);
            $router->get('/profiles/{username}',['uses' => 'ProfileController@show']);
           // $router->get('/profiles/{id}',['uses' => 'ProfileController@show']);
           // $router->post('/profiless',['uses' => 'ProfileController@update']);
            $router->put('/profiles',['uses' => 'ProfileController@update']);
        
            // posts
            $router->get('/posts',['uses' => 'PostController@index']);
            $router->post('/posts',['uses' => 'PostController@store']);
            $router->get('/posts/{title}',['uses' => 'PostController@show']);
             $router->post('/postss/{id}',['uses' => 'PostController@update']);
            $router->put('/posts/{id}',['uses' => 'PostController@update']);
            $router->delete('/posts/{id}',['uses' => 'PostController@destroy']);
        
            //likes
            $router->get('/likes',['uses' => 'LikeController@index']);
            $router->post('/likes',['uses' => 'LikeController@store']);
            $router->delete('/likes/{post_id}',['uses' => 'LikeController@destroy']);
        
            //favorites
            $router->get('/favorites',['uses' => 'FavoriteController@index']);
            $router->post('/favorites',['uses' => 'FavoriteController@store']);
            $router->delete('/favorites/{id}',['uses' => 'FavoriteController@destroy']);
        
            //follows
            $router->get('/follows',['uses' => 'FollowController@index']);
            $router->post('/follows', ['uses' => 'FollowController@follow']);
            $router->delete('/follows/{id}', ['uses' => 'FollowController@unfollow']);
        
        
        
        });
        
        
        
        $router->group([
        
            // 'middleware' => 'jwt.refresh',
            'prefix' => 'auth'
        
        ], function ($router) {
        
            $router->post('login', 'ExampleController@login');
            $router->post('logout', 'ExampleController@logout');
            $router->post('refresh', 'ExampleController@refresh');
            $router->post('me', 'ExampleController@me');
        
        });
        // $router->get('/symlink', function () {
        //     $target = base_path('storage/app/public');
        //     $link = base_path('public/storage');
        
        //     if (is_link($link)) {
        //         unlink($link);
        //     }
        
        //     symlink($target, $link);
        //     echo "Done";
        //       });
});