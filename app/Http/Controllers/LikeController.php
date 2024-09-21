<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\Like\LikeServiceInterface;
use App\Interfaces\Post\PostServiceInterface;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Likes",
 *     description="Manage Likes Posts"
 * )
 */
class LikeController extends Controller
{
    use ResponseHelper;
    private $likeService, $postService;
    public function __construct(LikeServiceInterface $likeService, PostServiceInterface $postService){
        $this->likeService = $likeService;
        $this->postService = $postService;
    }

    /**
     * @OA\Get(
     *     path="/api/likes",
     *     summary="Get liked posts or liked posts liked by the user",
     *     tags={"Likes"},
     *     security={{"bearer_token":{}}},
     *     @OA\Parameter(
     *         name="post_id",
     *         in="query",
     *         schema={
     *             "type": "integer"
     *         },
     *         description="Get liked users by post ID"
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
                return $this->likeService->getLikedUsersByPost($pages,$post_id);
            }

        return $this->response($this->likeService->getLikedPostsByUser($pages));
    }

    /**
     * @OA\Post(
     *     path="/api/likes",
     *     summary="Like a post",
     *     tags={"Likes"},
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
            return $this->response($this->likeService->addLike($post->id));
        }
        return $this->error('Not Found',404);
    }

    /**
     * @OA\Delete(
     *     path="/api/likes/{id}",
     *     summary="Unlike a post",
     *     tags={"Likes"},
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
    public function destroy(Request $request, string $post_id) {
        if($post = $this->postService->getPostById($post_id)){
            if($post->user_id == auth()->user()->id){
                return $this->response($this->likeService->removeLike($post->id));
            }
        }
        return $this->error();
    }
}
