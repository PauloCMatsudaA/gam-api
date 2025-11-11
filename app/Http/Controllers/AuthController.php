<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\LocationHistory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'age' => $data['age'],
            'email' => $data['email'],
            'password' => $data['password'],
            'current_lat' => $data['lat'],
            'current_lng' => $data['lng'],
        ]);
        // Hash password if not auto-hashed by casts
        if (!Hash::check($data['password'], $user->password)) {
            $user->password = Hash::make($data['password']);
            $user->save();
        }
        LocationHistory::create([
            'user_id' => $user->id,
            'lat' => $data['lat'],
            'lng' => $data['lng'],
        ]);
        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(LoginRequest $request) {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['token' => $token]);
    }
}

