<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FieldAgentModel;
use Illuminate\Support\Facades\Hash;

class ApiLoginController extends Controller
{
    // Login API for FieldAgent
    public function fieldagentlogin(Request $request)
    {
    try {
        $request->validate([
            'number' => 'required',
            'password' => 'required',
        ]);

        $agent = FieldAgentModel::where('number', $request->number)->first();

        if (! $agent) {
            return response()->json([
                'status'  => false,
                'message' => 'Field agent not found',
            ], 404);
        }

        if (! Hash::check($request->password, $agent->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Password incorrect',
            ], 401);
        }

        $token = $agent->createToken('fieldagent-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'token'   => $token,
            'data'    => $agent,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status'  => false,
            'message' => 'Server Error',
            'error'   => $e->getMessage(),
        ], 500);
    }
    }


    // Get logged-in FieldAgent's profile
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'data'   => $request->user(),
        ]);
    }

    // Logout (revoke current token)
    public function fieldagentlogout(Request $request)
    {
    $request->user()->currentAccessToken()->delete(); // deletes the token
    return response()->json([
        'status'  => true,
        'message' => 'Logged out successfully',
    ]);
    }
}
