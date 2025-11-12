<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\LocationHistory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerWithFirebase(\Illuminate\Http\Request $request, \Kreait\Firebase\Contract\Auth $firebase)
    {
        $data = $request->validate([
            'age' => 'required|integer|min:0',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'name' => 'nullable|string',
        ]);

        $idToken = $request->bearerToken() ?? $request->input('idToken');
        if (!$idToken) {
            return response()->json(['message' => 'Missing ID token'], 401);
        }

        try {
            $verified = $firebase->verifyIdToken($idToken);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid ID token'], 401);
        }

        $claims = $verified->claims();
        $uid = $claims->get('sub');
        if (!$uid) {
            return response()->json(['message' => 'Token without UID'], 401);
        }

        $email = $claims->get('email');
        $name = $data['name'] ?? $claims->get('name') ?? ($email ?: 'Usuário');

        $user = User::firstOrCreate(
            ['firebase_uid' => $uid],
            [
                'name' => $name,
                'email' => $email,
                'password' => null,
                'age' => $data['age'],
                'current_lat' => $data['lat'],
                'current_lng' => $data['lng'],
            ]
        );

        $user->age = $data['age'];
        $user->current_lat = $data['lat'];
        $user->current_lng = $data['lng'];
        if (!$user->name && $name) $user->name = $name;
        if (!$user->email && $email) $user->email = $email;
        $user->save();

        LocationHistory::create([
            'user_id' => $user->id,
            'lat' => $data['lat'],
            'lng' => $data['lng'],
        ]);

        return response()->json(['user' => $user], 201);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'age' => $data['age'],
            'email' => $data['email'],
            'password' => $data['password'],
            'current_lat' => $data['lat'],
            'current_lng' => $data['lng'],
        ]);

        LocationHistory::create([
            'user_id' => $user->id,
            'lat' => $data['lat'],
            'lng' => $data['lng'],
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }
        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['token' => $token]);
    }
}

