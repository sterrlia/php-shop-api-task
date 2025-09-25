<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

enum ExceptionMessagesEnum: string {
    case CartNotFound = 'cart-not-found';
    case ProductNotFound = 'product-not-found';
    case Unauthenticated = 'unauthenticated';
}

