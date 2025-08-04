<?php
declare(strict_types=1);

namespace app\services;

use app\filters\RequestInfoProviderInterface;
use yii\web\Response;
use yii\web\TooManyRequestsHttpException;
use yii\log\Logger;

/**
 * Сервис ответа при превышении лимита запросов.
 */
class RateLimitResponder
{
    public function __construct(
        private readonly Response $response,
        private readonly Logger $logger,
        private readonly RequestInfoProviderInterface $requestInfo
    ) {}

    /**
     * Возвращает 429 Too Many Requests и логирует превышение.
     *
     * @throws TooManyRequestsHttpException
     */
    public function tooManyRequests(
        int $retryAfter,
        string $message = 'Слишком много запросов. Попробуйте позже.'
    ): void {
        $this->response->headers->set('Retry-After', $retryAfter);

        $this->logger->warning('Rate limit exceeded', [
            'retry_after' => $retryAfter,
            'client_key'  => $this->requestInfo->getClientKey(),
            'ip'          => $this->requestInfo->getClientIp(),
            'ua'          => $this->requestInfo->getUserAgent(),
        ]);

        $acceptHeader = (string)\Yii::$app->request->getHeaders()->get('Accept');
        if (str_contains($acceptHeader, 'application/json')) {
            $this->response->format = Response::FORMAT_JSON;
            $this->response->statusCode = 429;
            $this->response->data = [
                'error' => $message,
                'retry_after' => $retryAfter,
            ];
            $this->response->send();
            \Yii::$app->end();
        }

        throw new TooManyRequestsHttpException($message);
    }
}