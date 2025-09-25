<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Cache;

use Raketa\BackendTestTask\Infrastructure\Util\JsonUtil;
use Raketa\BackendTestTask\Infrastructure\Util\ReflectionUtil;

// :TODO: add serializer integration
final readonly class CacheClient
{
    public function __construct(
        private \Redis $redis
    ) {}

    /**
     * @throws CacheClientException
     */
    public function getScalar(string $key): null|float|int|string
    {
        try {
            return $this->redis->get($key) ?: null;
        } catch (\RedisException $e) {
            throw new CacheClientException('Get failure', previous: $e);
        }
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T|null
     */
    public function getObject(string $key, string $class): object|null
    {
        /** @var ?string $data */
        $data = $this->getScalar($key);
        if ($data === null) {
            return null;
        }

        $array = JsonUtil::decode($data);

        return ReflectionUtil::mapArrayToClassRecursive($array, $class);
    }

    public function setObject(string $key, object $value, int $ttl = 24 * 60 * 60): void
    {
        $encoded = JsonUtil::encode($value);

        $this->setScalar($key, $encoded, $ttl);
    }

    /**
     * @param null|scalar $value
     *
     * @throws CacheClientException
     */
    public function setScalar(string $key, null|float|int|string $value, int $ttl = 24 * 60 * 60): void
    {
        try {
            $this->redis->setex($key, $ttl, $value)
               ?: throw new CacheClientException('Set failure');
        } catch (\RedisException $e) {
            throw new CacheClientException('Set error', previous: $e);
        }
    }

    public function has(string $key): bool
    {
        return $this->redis->exists($key);
    }
}
