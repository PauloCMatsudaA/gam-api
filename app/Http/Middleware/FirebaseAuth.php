<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class FirebaseAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            throw new UnauthorizedHttpException('Bearer', 'Missing bearer token');
        }

        try {
            /** @var \Kreait\Firebase\Auth $auth */
            $auth = app('firebase.auth');
            $verified = $auth->verifyIdToken($token);
        } catch (\Throwable $e) {
            throw new UnauthorizedHttpException('Bearer', 'Invalid ID token');
        }

        $uid = $verified->claims()->get('sub');

        if (!$uid) {
            throw new UnauthorizedHttpException('Bearer', 'Token without UID');
        }

        $email = $verified->claims()->get('email');
        $name = $verified->claims()->get('name') ?: ($email ?: 'Usuário');

        $user = User::firstOrCreate(
            ['firebase_uid' => $uid],
            [
                'name' => $name,
                'email' => $email,
                'password' => null
            ]
        );

        // Sem elevação de privilégios automática

        Auth::login($user);

        return $next($request);
    }
}
