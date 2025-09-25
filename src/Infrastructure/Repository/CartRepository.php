<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Infrastructure\Repository;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\Cache\CacheClient;

final readonly class CartRepository
{
    public function __construct(
        private CacheClient $cacheClient
    ) {
    }

    public function saveCart(string $sessionId, Cart $cart): void
    {
        $this->cacheClient->setObject($sessionId, $cart);
    }

    public function getCart(string $sessionId): ?Cart
    {
        return $this->cacheClient->getObject($sessionId, Cart::class);
    }
}
