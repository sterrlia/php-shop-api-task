<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Application;

use Raketa\BackendTestTask\Controller\ExceptionMessagesEnum;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Infrastructure\Repository\CartRepository;
use Raketa\BackendTestTask\Infrastructure\Repository\ProductRepository;
use Ramsey\Uuid\Uuid;

final readonly class CartService
{
    public function __construct(
        private CartRepository $cartRepository,
        private ProductRepository $productRepository
    ) {}

    public function addToCart(string $sessionId, string $productUuid, int $quantity): Cart
    {
        $product = $this->productRepository->getByUuid($productUuid)
            ?? throw ExceptionMessagesEnum::ProductNotFound->exception();

        $cart = $this->cartRepository->getCart($sessionId)
            ?? throw ExceptionMessagesEnum::CartNotFound->exception();

        $cart->items[] = new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $quantity,
        );

        $this->cartRepository->saveCart($sessionId, $cart);

        return $cart;
    }

    public function getCart(string $sessionId): ?Cart
    {
        return $this->cartRepository->getCart($sessionId);
    }
}
