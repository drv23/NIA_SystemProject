<?php

namespace App\Services\V1;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): User
    {
        $img = $data['image'] ?? null;
        if($img === null){
            $imagePath = 'images/default.png';
        } else {
            $imagePath = $img->store('images', 'public');
        }
        
        return User::create([
            'fullname' => $data['fullname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'location_id' =>$data['location_id'],
            'role' => $data['role'],
            'image' => $imagePath,
            'password' => Hash::make($data['password']),
        ]);
    }

    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken($user->username);

        return [
            "message" => "You are successfully login!",
            'user' => $user,
            'token' => $token->plainTextToken,
        ];
    }

    public function logout(Request $request)
    {
         $user = $request->user();

         $user->currentAccessToken()->delete();

         return;
    }
}
