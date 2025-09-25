<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Controller;

use Raketa\BackendTestTask\Infrastructure\Log\LogDataBag;
use Raketa\BackendTestTask\Infrastructure\Util\JsonUtil;

readonly class AbstractController
{
    // если не используем symfony
    /** @param mixed[]|object $data */
    protected function json(array|object $data): JsonResponse
    {
        $content = JsonUtil::encode($data);
        $response = new JsonResponse();
        $response->getBody()->write($content);

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200)
        ;
    }

    /**
     * @throws \HttpException
     */
    protected function getSessionId(): string
    {
        $sessionId = session_id() ?: throw ExceptionMessagesEnum::Unauthenticated->exception();
        LogDataBag::merge(['sessionId' => $sessionId]);

        return $sessionId;
    }
}
