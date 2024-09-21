<?php

namespace App\Interfaces\Verification;

use Illuminate\Http\Request;

interface VerificationServiceInterface
{
    public function sendSmsVerificationCode(string $phoneNumber): bool;
    public function verifySmsCode(Request $request): bool;
}
