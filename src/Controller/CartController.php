<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Application\CartService;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Domain\Entity\Product;
use Raketa\BackendTestTask\Infrastructure\Log\LogDataBag;
use Raketa\BackendTestTask\Infrastructure\Repository\ProductRepository;
use Raketa\BackendTestTask\Infrastructure\Util\JsonUtil;

final readonly class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private ProductRepository $productRepository
    ) {
    }

    public function addToCart(RequestInterface $request): ResponseInterface
    {
        $requestContent = $request->getBody()->getContents();
        // :TODO: можно использовать symfony validator
        // :TODO: можно реализовать symfony ValueResolver
        $rawRequest = JsonUtil::decode($requestContent);
        /** @var string $productUuid */
        $productUuid = $rawRequest['productUuid'];
        /** @var int $quantity */
        $quantity = $rawRequest['quantity'];

        $modifiedCart = $this->cartService->addToCart(
            $this->getSessionId(),
            $productUuid,
            $quantity
        );

        return $this->json($this->getCartView($modifiedCart));
    }

    public function getCart(): ResponseInterface
    {
        $cart = $this->cartService->getCart($this->getSessionId())
            ?? throw ExceptionMessagesEnum::CartNotFound->exception();

        return $this->json($this->getCartView($cart));
    }

    /** @return mixed[] */
    private function getCartView(Cart $cart): array
    {
        $customer = $cart->customer;
        $data = [
            'uuid' => $cart->uuid,
            'customer' => [
                'id' => $customer->id,
                // :TODO: лучше отдельными полями передавать
                'name' => implode(' ', [
                    $customer->lastName,
                    $customer->firstName,
                    $customer->middleName,
                ]),
                'email' => $customer->email,
            ],
            'payment_method' => $cart->paymentMethod,
        ];

        $productUuids = array_map(
            fn (CartItem $item) => $item->productUuid,
            $cart->items
        );

        $products = $this->productRepository->fetchByUuids(...$productUuids);

        /** @var array<string, Product> $indexedProducts */
        $indexedProducts = array_reduce(
            $products,
            fn (array $acc, Product $product) => array_merge($acc, [$product->getUuid() => $product]),
            []
        );

        $total = 0;
        $data['items'] = [];
        foreach ($cart->items as $item) {
            $total += $item->price * $item->quantity;

            LogDataBag::merge(
                [
                    'itemUuid' => $item->uuid,
                    'outputProductUuid' => $item->productUuid,
                ]
            );
            $product = $indexedProducts[$item->productUuid]
                ?? throw new \RuntimeException("Product #{$item->productUuid} not found");

            $data['items'][] = [
                'uuid' => $item->uuid,
                'price' => $item->price,
                'total' => $total,
                'quantity' => $item->quantity,
                'product' => [
                    'id' => $product->getId(),
                    'uuid' => $product->getUuid(),
                    'name' => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    'price' => $product->getPrice(),
                ],
            ];
        }

        $data['total'] = $total;

        return $data;
    }
}
