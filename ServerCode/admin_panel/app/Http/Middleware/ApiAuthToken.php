<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Api-Token');

        if (!$token) {
            return response()->json([
                'status' => 401,
                'errors' => __('api_msg.token_missing')
            ], 401);
        }

        if (!hash_equals(env('API_TOKEN'), $token)) {
            return response()->json([
                'status' => 401,
                'errors' => __('api_msg.invalid_token')
            ], 401);
        }

        return $next($request);
    }
}
