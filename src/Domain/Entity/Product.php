<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Domain\Entity;

// :TODO: убрать final если используется doctrine orm
final readonly class Product
{
    public function __construct(
        // :TODO: можно оставить id или uuid если doctrine не будет использоваться, лучше id т.к. он лучше индексируется
        private int $id,
        private string $uuid,
        private bool $isActive,
        // :TODO: заменить на enum
        private string $category,
        private string $name,
        private string $description,
        private string $thumbnail,
        private float $price,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
