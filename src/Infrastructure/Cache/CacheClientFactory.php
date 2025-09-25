<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Cache;

// т.к. подразумеватся что есть DI переделал в factory который будет собирать нужный клиент
final readonly class CacheClientFactory
{
    private const PERSISTENT_CONNECTION_ID = 'app_persistent_connection';

    public function __construct(
        private string $host,
        private int $port = 6379,
        private ?string $password = null,
        private ?int $dbindex = null
    ) {}

    private function build(): CacheClient
    {
        $redis = new \Redis();

        if (
            !$redis->isConnected()
                && $redis->ping('Pong')
        ) {
            $connectionResult = $redis->pconnect(
                host: $this->host,
                port: $this->port,
                persistent_id: self::PERSISTENT_CONNECTION_ID
            );

            $connectionResult && $redis->isConnected()
                ?: throw new \RuntimeException('Failed to connect to redis');
        }

        $redis->auth($this->password) ?: throw new \RuntimeException('Failed to set auth for redis');
        $redis->select($this->dbindex) ?: throw new \RuntimeException('Failed to select database');

        return new CacheClient($redis);
    }
}
