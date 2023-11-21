<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Domain\Asset\Repositories\AssetsRepository;

class PlayerAssetsCachingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && ! app()->environment('testing')) {
            $assetsRepository = app(AssetsRepository::class);
            $assetsRepository->getPlayerAssets($request->user());
        }

        return $next($request);
    }
}
