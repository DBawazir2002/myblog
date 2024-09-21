<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\Favorite\FavoriteServiceInterface;
use App\Interfaces\Like\LikeServiceInterface;
use App\Interfaces\Post\PostServiceInterface;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Favorites",
 *     description="Manage Favorite Posts"
 * )
 */
class FavoriteController extends Controller
{
    use ResponseHelper;
    private $favoriteService, $postService;
    public function __construct(FavoriteServiceInterface $favoriteService, PostServiceInterface $postService){
        $this->favoriteService = $favoriteService;
        $this->postService = $postService;
    }

    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     summary="Get favorite posts to auth user or the users who made it favorite",
     *     tags={"Favorites"},
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="post_id",
     *         in="query",
     *         schema={
     *             "type": "integer"
     *         },
     *         description="Get favorite users by post ID"
     *     ),
     *     @OA\Parameter(
     *         name="pages",
     *         in="query",
     *         schema={
     *             "type": "integer"
     *         },
     *         description="Number of results per page"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function index(Request $request){

        $post_id = ($request->input('post_id') ) ? $request->input('post_id') : null;
        $pages = ($request->input('pages') ) ? $request->input('pages') : 10;

        if($post_id){
            return $this->favoriteService->getFavoriteUsersByPost($pages,$post_id);
        }

        return $this->response($this->favoriteService->getFavoritePostsByUser($pages));
    }

    /**
     * @OA\Post(
     *     path="/api/favorites",
     *     summary="Add a post to favorites",
     *     tags={"Favorites"},
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function store(Request $request) {
        if($post = $this->postService->getPostById($request->post_id)){
            return $this->response($this->favoriteService->addFavorite($post->id));
        }
        return $this->error('Not Found',404);
    }

    /**
     * @OA\Delete(
     *     path="/api/favorites/{id}",
     *     summary="Remove a post from favorites",
     *     tags={"Favorites"},
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function destroy(Request $request, string $id) {
        if($post = $this->postService->getPostById($id)){
            if($post->user_id == auth()->user()->id){
                return $this->response($this->favoriteService->removeFavorite($post->id));
            }
        }
        return $this->error();
    }
}
