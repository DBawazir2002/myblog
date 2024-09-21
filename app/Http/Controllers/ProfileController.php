<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\Auth\AuthServiceInterface;
use App\Interfaces\Profile\ProfileServiceInterface;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Profiles",
 *     description="Manage user profiles"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     in="header",
 *     securityScheme="bearer_token",
 *     scheme="bearer"
 * )
 */
class ProfileController extends Controller
{
    use ResponseHelper;
    private $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

    /**
     * @OA\Get(
     *     path="/api/profiles",
     *     tags={"Profiles"},
     *     @OA\Parameter(
     *         name="pages",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
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
    public function index(Request $request){
        $pages = ($request->input('pages') ) ? $request->input('pages') : 10;
        $user = auth()->user();
        return $this->response($this->profileService->getProfiles($pages,$user->id));
    }

//
//    public function show(Request $request, string $id){
//       $id = $id ?? auth()->user()->profile()->id;
//        return $this->response($this->profileService->getProfileById($id));
//        //return $this->response($id);
//
//    }

    /**
     * @OA\Get(
     *     path="/api/profiles/{username}",
     *      tags={"Profiles"},
     *     @OA\Parameter(
     *         name="username",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
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
    public function show(Request $request, $username){
        $username = $username ?? auth()->user()->profile()->username;
        return $this->response($this->profileService->getProfileByUserName($username));
       // return $this->response($username);
    }

    /**
     * @OA\Post(
     *     path="/api/profiles",
     *      tags={"Profiles"},
     *     summary="Create a new profile",
     *     tags={"Profiles"},
     *     security={{"bearer_token":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="johndoe"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profile created successfully")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $user_id = (isset($request->user_id)) ? $request->user_id : $user = auth()->user()->id;
        return $this->response($this->profileService->createProfile($user_id));
    }

    /**
     * @OA\Put(
     *     path="/api/profiles",
     *      tags={"Profiles"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer"
     *                 )
     *             )
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
    public function update(Request $request)
    {
        $user = $request->user_id ?? auth()->user();
       return $this->response($this->profileService->updateProfile($user->id));
      //  return $this->profileService->updateProfile($user->id);
    }
}
