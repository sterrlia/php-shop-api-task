<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Raketa\BackendTestTask\Infrastructure\Log\LogDataBag;
use Raketa\BackendTestTask\Infrastructure\Util\JsonUtil;

readonly class AbstractController
{
    // если не используем symfony
    // :TODO: можно реализовать enum для статус или проверять через phpstan
    // @param mixed $data
    protected function json(array|object $data, StatusCodeEnum $status = StatusCodeEnum::Ok): JsonResponse
    {
        $content = JsonUtil::encode($data);
        $response = new JsonResponse();
        $response->getBody()->write($content);

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus($status->value)
        ;
    }

    /**
     * @throws \HttpException
     */
    protected function getSessionId(): string
    {
        $sessionId = session_id() ?: throw new \HttpException(ExceptionMessagesEnum::Unauthenticated->value, 403);
        LogDataBag::merge(['sessionId' => $sessionId]);

        return $sessionId;
    }
}
