<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;

class AuditLogMiddleware
{
    public function handle($request, Closure $next) {
        $response = $next($request);
        if (in_array($request->method(), ['POST']) && ($request->is('api/ativos') || $request->is('api/transferencia'))) {
            AuditLog::create([
                'user_id' => optional($request->user())->id,
                'method' => $request->method(),
                'path' => $request->path(),
                'payload' => $request->except(['password']),
            ]);
        }
        return $response;
    }
}

