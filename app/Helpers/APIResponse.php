<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class APIResponse
{
    /**
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    public static function success($message = "Ok!", $data = [])
    {
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => $message
        ]);
    }

    /**
     * @param string $message
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public static function error($message = "Something went wrong!", $data = [], $code = 200)
    {
        return response()->json([
            'status' => false,
            'data' => $data,
            'message' => $message
        ], $code);
    }
}