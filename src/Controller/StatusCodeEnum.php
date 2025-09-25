<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

enum StatusCodeEnum: int {
    case NotFound = 404;
    case BadRequest = 400;
    case Ok = 200;
}

