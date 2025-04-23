<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiry
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->currentAccessToken();

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Token not found.'
            ], 401);
        }

        $nowKolkata = Carbon::now('Asia/Kolkata');

        if ($token->expires_at && $nowKolkata->gt($token->expires_at)) {
            $token->delete(); // Optional: delete expired token
            return response()->json([
                'status' => false,
                'message' => 'Token expired. Please login again.',
            ], 401);
        }

        return $next($request);
    }
}
