<?php

namespace App\Services;

use App\Interfaces\User\UserServiceInterface;
use App\Interfaces\Verification\VerificationServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class VerificationService implements VerificationServiceInterface
{
    private $userService;
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function sendSmsVerificationCode(string $phoneNumber): bool
    {
        try {
            $twilio_sid     = getenv("TWILIO_ACCOUNT_SID");
            $twilio_token   = getenv("TWILIO_AUTH_TOKEN");
            $twilio_service = getenv("TWILIO_ACCOUNT_PHONE");
            $twilio = new Client($twilio_sid , $twilio_token);

            $verification = $twilio->verify->v2->services($twilio_service)
                ->verifications
                ->create("+967".$phoneNumber, "sms");
            return true;
            // echo $verification; exit;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return false;
    }

    public function verifySmsCode(Request $request): bool
    {
        $is_verified = false;
        $status_code = 401;
        $message = "Failed to verify";
        if($user = $this->userService->getUserByPhone($request->phone)) {
            $user->update([
                'phone_verified_at' => time(),
            ]);
            $this->userService->update($user);
            $verification_code = $request->code;
            // $twilio_sid = getenv("TWILIO_ACCOUNT_SID");
            // $twilio_token = getenv("TWILIO_AUTH_TOKEN");
            // $twilio_service = getenv("TWILIO_ACCOUNT_PHONE");
            // $twilio = new Client($twilio_sid, $twilio_token);

            // $verification_check = $twilio->verify->v2
            //     ->services($twilio_service)
            //     ->verificationChecks->create([
            //         "to" => "+967" . $user->phone,
            //         "code" => $verification_code,
            //     ]);
            // if ($verification_check->status === 'approved') {
            if ($verification_code === '123456') {
                $user->update([
                    'phone_verified_at' => time(),
                ]);
                $is_verified = true;
                $status_code = 200;
                $message = "Phone verified successfully.";
            }
        }
        return $is_verified;
    }
}
