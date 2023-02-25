<?php

namespace App\Http\Middleware;

use App\Models\MaintenanceSchedule;
use Closure;
use Illuminate\Support\Carbon;

class ApiKeyVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        $apiKey = $request->header('API-key');
        if (empty($apiKey)) {
            return response()->json([
                'error' => 'API key is missing.'
            ], 403);
        }

        if ($apiKey != 'HiJhvL$T27@1u^%u86g') {
            return response()->json([
                'error' => 'Invalid API key.'
            ], 401);
        }

        return $next($request);
    }
}
