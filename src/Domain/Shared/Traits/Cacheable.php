<?php

namespace Domain\Shared\Traits;

use Illuminate\Support\Facades\Cache;

trait Cacheable
{
    public static function getFromCache(string $key, callable $closure, int $seconds = 5 * 60): mixed
    {
        return Cache::remember(
            $key,
            $seconds,
            fn () => $closure(),
        );
    }

    public static function clearCache(string $key): mixed
    {
        return Cache::forget($key);
    }
}
