<?php

namespace Domain\Shared\Traits;

trait AsAction
{
    /**
     * @return static
     */
    public static function make()
    {
        return app(static::class);
    }

    /**
     * @see static::handle()
     *
     * @param  mixed ...$arguments
     *
     * @return mixed
     */
    public static function execute(...$arguments)
    {
        return static::make()->handle(...$arguments);
    }
}
