<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Domain\Player\Repositories\PlayerRepository;

class PlayerVIPCachingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && ! app()->environment('testing')) {
            app(PlayerRepository::class)->getIsVIP($request->user());
        }

        return $next($request);
    }
}
