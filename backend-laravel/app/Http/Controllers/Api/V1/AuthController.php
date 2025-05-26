<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Services\V1\AuthService;

class AuthController extends Controller
{
    
    protected $authService;

    public function __construct(AuthService $authService)
    {
        // Calls automatically
        $this->authService = $authService;

    }


    public function register(Request $request)
    {
        
        $user = $this->authService->register($request->all());

        // $token = $user->createToken($request->name);

        return response()->json([
            'message' => 'New user registered successfully.',
            'user' => $user,
            // 'token' => $token->plainTextToken
        ], 201);


    }

    
    public function login(Request $request)
    {
        
        $result = $this->authService->login($request->only('email', 'password'));

        if (!$result) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        

        return response()->json($result, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
         $this->authService->logout($request);

        return response()->json(['message' => 'You are Logged out']);
    }
}
