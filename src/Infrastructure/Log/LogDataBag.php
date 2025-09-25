<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Log;

final class LogDataBag
{
    /**
     * @var mixed[] $logData
    */
    private static array $logData = [];

    /** @param array<scalar|null> $data */
    public static function merge(array $data): void
    {
        self::$logData = array_merge($data);
    }
}
