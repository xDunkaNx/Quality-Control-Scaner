<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIntegrationToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.integrations.token');

        if (empty($expected)) {
            abort(500, 'El token de integración no está configurado.');
        }

        $provided = $request->bearerToken()
            ?? $request->header('X-Integration-Token')
            ?? $request->query('token');

        if (!$provided || !hash_equals($expected, $provided)) {
            abort(401, 'Token de integración inválido.');
        }

        return $next($request);
    }
}

