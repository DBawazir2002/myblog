<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interfaces\User\UserServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Twilio\Rest\Client;

class VerificationController extends Controller
{
    private $userService;
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/api/phone/verify",
     *     summary="Verify SMS Code",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="string", example="123456"),
     *             @OA\Property(property="phone", type="string", example="7XXXXXXXX")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function verifySMSCode(Request $request){

        $is_verified = false;
        $status_code =401;
        $message = "Failed to verify";
        if($user = $this->userService->getUserByPhone($request->phone)) {

            // $twilio_sid = getenv("TWILIO_ACCOUNT_SID");
            // $twilio_token = getenv("TWILIO_AUTH_TOKEN");
            // $twilio_service = getenv("TWILIO_ACCOUNT_PHONE");
            // $twilio = new Client($twilio_sid, $twilio_token);

            // $verification_check = $twilio->verify->v2
            //     ->services($twilio_service)
            //     ->verificationChecks->create([
            //         "to" => "+967" . $request->phone,
            //         "code" => $request->code,
            //     ]);
            // if ($verification_check->status === 'approved') {
            if ($request->code === '123456') {
                $user->update([
                    'phone_verified_at' => Carbon::now(),
                ]);
                $is_verified = true;
                $status_code = 200;
                $message = "Phone verified successfully.";
            }
        }


        return response()->json([
            "status" => $is_verified,
            "message" => $message
        ],
            $status_code
        );
    }
}
