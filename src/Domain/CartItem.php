<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

final readonly class CartItem
{
    public function __construct(
        public string $uuid,
        public string $productUuid,
        public float $price,
        public int $quantity
    ) {
    }
}
