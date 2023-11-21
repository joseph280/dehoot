<?php

namespace Domain\Shared\Objects;

class Printer
{
    public static function makeExceptionMessage(array $args, ?string $header = null): string
    {
        $message = "${header} \n";

        foreach ($args as $key => $value) {
            if (! $value) {
                continue;
            }
            $message = $message . "${key}: ${value} -\n";
        }

        return $message;
    }
}
