<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

final class Cart
{
    /** @param CartItem[] $items */
    public function __construct(
        public readonly string $uuid,
        public readonly Customer $customer,
        public readonly string $paymentMethod,
        public array $items
    ) {
    }
}
