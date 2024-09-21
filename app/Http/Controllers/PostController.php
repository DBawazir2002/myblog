<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\Post\PostServiceInterface;
use App\Interfaces\Profile\ProfileServiceInterface;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
//use OpenApi\Attributes as OA;
use OpenApi\Annotations as OA;


/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Follow and UnFollow users"
 * )
 */
class PostController extends Controller
{
    use ResponseHelper;
    private $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Get a list of posts",
     *     security={{"bearerAuth":{}}},
     *      tags={"Posts"},
     *     @OA\Response(
     *         response="200",
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="id",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="title",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="content",
     *                         type="string"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request){
        $pages = ($request->input('pages') ) ? $request->input('pages') : 10;
       // $user = auth()->user();
        return $this->response($this->postService->getAllPosts($pages));
    }

//    public function show(Request $request, string $id){
//        $id = $id ?? auth()->user()->profile()->id;
//        return $this->response($this->postService->getProfileById($id));
//        //return $this->response($id);
//
//    }


    /**
     * @OA\Get(
     *     path="/api/posts/{title}",
     *     summary="Get a post by title",
     *      tags={"Posts"},
     *     @OA\Parameter(
     *         name="title",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful response",
     *     ),
     * )
     */
    public function show(Request $request, $title){
        return $this->response($this->postService->getPostByTitle($title));
        // return $this->response($username);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Create a new post",
     *     tags={"Posts"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="title",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="content",
     *                 type="string"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Successful response",
     *     ),
     * )
     */
    public function store(Request $request)
    {
       // $user_id = auth()->user()->id;
        return $this->response($this->postService->createPost(),'success',201);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update a post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful response",
     *     ),
     * )
     */

    public function update(Request $request)
    {
        $user = auth()->user();
        if($post = $this->postService->getPostById($request->id)){
             return $this->response(
            $this->postService->updatePost($post));
        }
        return $this->error();
       
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Delete a post",
     *     tags={"Posts"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Successful response"
     *     ),
     * )
     */
    public function destroy(Request $request){
        if($post = $this->postService->getPostById($request->id)){ return $this->response($this->postService->deletePost($post),204);
        }
        return $this->error();
    }


}
