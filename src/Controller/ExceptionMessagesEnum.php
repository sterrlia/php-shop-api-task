<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

enum ExceptionMessagesEnum: string
{
    case CartNotFound = 'cart-not-found';
    case ProductNotFound = 'product-not-found';
    case Unauthenticated = 'unauthenticated';

    public function exception(?\Throwable $previous = null): \HttpException
    {
        $code = match ($this) {
            ExceptionMessagesEnum::Unauthenticated => 403,
            ExceptionMessagesEnum::CartNotFound,
            ExceptionMessagesEnum::ProductNotFound => 404,
        };

        return new \HttpException($this->value, $code, previous: $previous);
    }
}
