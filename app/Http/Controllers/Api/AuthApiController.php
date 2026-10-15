<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);

        $type = $request->segment(2);                        // admin | staff
        $model = $type === 'admin' ? Admin::class : Staff::class;
        $user = $model::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }
        if ($user instanceof Staff && ! $user->isEligible()) {
            return response()->json(['message' => 'Your account is inactive or not verified.'], 403);
        }

        return response()->json([
            'token' => $user->createToken($type . '-token', [$type])->plainTextToken,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out.']);
    }
}
