<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Util;

class JsonUtil
{
    /** @param mixed[] $data */
    public static function encode(object|array $data): string
    {
        $encoded = json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);

        return $encoded;
    }

    /**
     * @return mixed[]
     *
     * @throws \JsonException
     */
    public static function decode(string $data): array
    {
        /** @var mixed[] $decoded */
        $decoded = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        return $decoded;
    }
}

