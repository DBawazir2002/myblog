<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ResponseHelper
{
    private $token;

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function response($data = null, $message = null, $statusCode = 200, $headers = []): JsonResponse
    {
        // If the status code is 204 (No Content), return an empty response
        if ($statusCode === Response::HTTP_NO_CONTENT) {
            return response()->noContent($statusCode, $headers);
        }

        $response = [
            'data' => $data,
            'message' => $message,
        ];

        if ($this->token) {
            $response['token'] = $this->token;
            $response['token_type'] = 'bearer';
            $response['expires_in'] = auth()->factory()->getTTL() * 60;
            $this->token = null; // Reset the token
        }

        return response()->json($response, $statusCode, $headers);
    }

    public function error($message = 'Error occur...', $statusCode = 400, $errors = []): JsonResponse
    {

        return $this->response(null, $message, $statusCode);
    }
    
    // protected function respondWithToken($token)
    // {
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60
    //     ]);
    // }
}
