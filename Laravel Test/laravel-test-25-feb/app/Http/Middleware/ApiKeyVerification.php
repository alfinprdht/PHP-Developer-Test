<?php

namespace App\Http\Middleware;

use App\Models\MaintenanceSchedule;
use Closure;
use App\Models\ApiToken;

class ApiKeyVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('API-key');
        if (empty($apiKey)) {
            return response()->json([
                'error' => 'API key is missing.'
            ], 403);
        }

        $checkToken = ApiToken::where('code', $apiKey)
            ->whereRaw('expired_at >= now()');

        if (!$checkToken->exists()) {
            return response()->json([
                'error' => 'Invalid API key.'
            ], 401);
        }

        return $next($request);
    }
}
