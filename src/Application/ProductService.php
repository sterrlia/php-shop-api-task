<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application;

use Raketa\BackendTestTask\Domain\Entity\Product;
use Raketa\BackendTestTask\Infrastructure\Repository\ProductRepository;

final readonly class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {}

    /** @return Product[] */
    public function getProducts(string $category): array
    {
        return $this->productRepository->getActiveByCategory($category);
    }
}
