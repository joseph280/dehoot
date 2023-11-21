<?php

namespace Tests\Mocks;

class AtomicNoDataResponse extends AtomicMock
{
    public static function getResponse(): array
    {
        return [
            'success' => true,
            'data' => [],
            'query_time' => 1653671496269,
        ];
    }
}
