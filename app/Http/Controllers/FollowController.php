<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\User\UserServiceInterface;
use App\Services\FollowService;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Follows",
 *     description="Follow and UnFollow users"
 * )
 */
class FollowController extends Controller
{
    use ResponseHelper;
    private $followService, $userService;
    public function __construct(FollowService $followService, UserServiceInterface $userService){
        $this->followService = $followService;
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/follows",
     *      tags={"Follows"},
     *     summary="Get followers or following users",
     *     @OA\Parameter(
     *         name="pages",
     *         in="query",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="followers",
     *         in="query",
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function index(Request $request){
        $pages = ($request->input('pages') ) ? $request->input('pages') : 10;
        $users = ($request->input('followers') ) ? $request->input('followers') : null;
       if(isset($users)){
           return $this->response($this->followService->getFollowers($pages));
        }
       return $this->response($this->followService->getFollowing($pages));
    }

    /**
     * @OA\Post(
     *     path="/api/follows",
     *       tags={"Follows"},
     *     summary="Follow a user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user_id",
     *                 type="integer"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User followed successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Failed to follow user"
     *     )
     * )
     */
    public function follow(Request $request){
        $followed = false;
        if($user = $this->userService->getUserById($request->user_id)){
            $followed = $this->followService->follow($user->id);
        }
        if($followed){
            return $this->response(null,'User followed successfully');
        }

        return $this->response(null,'Failed to follow user', 400);
    }

    /**
     * @OA\Delete(
     *     path="/api/follows/{id}",
     *       tags={"Follows"},
     *     summary="Unfollow a user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="User unfollowed successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Failed to unfollow user"
     *     )
     * )
     */
    public function unfollow($id){
        $unfollowed = false;
        if($user = $this->userService->getUserById($id)){
            $unfollowed = $this->followService->unfollow($user->id);
        }

        if($unfollowed){
            return $this->response(null,'User unfollowed successfully');
        }

        return $this->response(null,'Failed to unfollow user', 400);
    }
}
