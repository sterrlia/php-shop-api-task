<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\ProductService;
use Raketa\BackendTestTask\Domain\Entity\Product;
use Raketa\BackendTestTask\Infrastructure\Util\JsonUtil;

final readonly class ProductController extends AbstractController
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function get(RequestInterface $request): ResponseInterface
    {
        $requestContent = $request->getBody()->getContents();
        // :TODO: можно использовать symfony validator
        // :TODO: можно реализовать symfony ValueResolver
        $rawRequest = JsonUtil::decode($requestContent);
        $products = $this->productService->getProducts($rawRequest['category']);

        $productsView = array_map(
            fn (Product $product) => [
                'id' => $product->getId(),
                'uuid' => $product->getUuid(),
                'category' => $product->getCategory(),
                'description' => $product->getDescription(),
                'thumbnail' => $product->getThumbnail(),
                'price' => $product->getPrice(),
            ],
            $products
        );

        return $this->json($productsView);
    }
}
