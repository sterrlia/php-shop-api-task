<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Util;

class JsonUtil
{
    /** @param mixed[] $data */
    public static function encode(object|array $data, bool $pretty = false): string
    {
        $flag = JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES;
        if ($pretty) {
            $flag |= JSON_PRETTY_PRINT;
        }
        /** @var string $encoded */
        $encoded = json_encode($data, $flag);

        return $encoded;
    }

    /**
     * @throws \JsonException
     */
    public static function decode(string $data): array 
    {
        return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }
}

