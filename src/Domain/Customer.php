<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain;

final readonly class Customer
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $middleName,
        public string $email,
    ) {}
}
