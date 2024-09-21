<?php

namespace App\Services;

use App\Http\Validators\AuthValidator;
use App\Interfaces\Auth\AuthServiceInterface;
use App\Interfaces\User\UserServiceInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{


    private $authValidator, $userService, $verificationService;


    public function __construct(
        AuthValidator $authValidator,
        UserServiceInterface $userService,
        VerificationService $verificationService
    )
    {
        $this->authValidator = $authValidator;
        $this->userService = $userService;
        $this->verificationService = $verificationService;
    }

    public function signup()
    {
        $validated = $this->authValidator->validated($this->authValidator->signup());
        if($validated){
           // $validatedData = $validated->getData()->data;
            $user = $this->userService->createUser();
            // if($this->verificationService->sendSmsVerificationCode($user->phone)){
                return $user;
            // }
//              return $this->response($user);
        }
        return null;
    }

    public function signin()
    {
        $validated = $this->authValidator->validated($this->authValidator->signin());
        $validatedData = (array) $validated->getData()->data;
        if($user = $this->userService->getUserByPhone($validatedData['phone'])){
            if (Hash::check($validatedData['password'], $user->password)) {
                //           $token = JWTAuth::fromUser($user);
                // $token = JWTAuth::fromUser($user, ['exp' => time() + (60 * 60)]);
                $token = auth()->login($user);
                //  $this->setToken($token);
                $user->token = $token;
                return $user;
            }
            
// //           $token = JWTAuth::fromUser($user);
//           // $token = JWTAuth::fromUser($user, ['exp' => time() + (60 * 60)]);
//             $token = auth()->login($user);
//           //  $this->setToken($token);
//           $user->token = $token;
//             return $user;

        }
        return null;
    }

    public function refreshToken()
    {
    //     $user = auth()->user();
    //     // auth()->invalidate($token);
    //     $token = auth()->refresh();
    //   // auth()->setToken($token);
    //     $user->token = $token;
    //     return $user;
    
        $expired_token = JWTAuth::getToken();
        $refreshed_token = JWTAuth::refresh($expired_token);
        return $refreshed_token;

    }

    public function signout(){
        auth()->logout();
        return true;
    }
}
