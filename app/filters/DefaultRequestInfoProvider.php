<?php
declare(strict_types=1);

namespace app\filters;

use Yii;

/**
 * Класс DefaultRequestInfoProvider предоставляет информацию о клиенте (IP и User-Agent).
 * Используется, например, для антиспам-фильтров, ограничения по частоте запросов (rate limiting) и аналитики.
 */
final class DefaultRequestInfoProvider implements RequestInfoProviderInterface
{
    /**
     * Возвращает уникальный ключ клиента, основанный на IP-адресе и User-Agent.
     * Это позволяет идентифицировать клиента, не прибегая к сессиям или кукам.
     */
    public function getClientKey(): string
    {
        $ip = $this->getClientIp() ?? 'unknown';
        $userAgent = substr($this->getUserAgent() ?? '', 0, 50); // ограничение на случай длинных строк
        return md5($ip . $userAgent); // возвращаем хэш как идентификатор
    }

    /**
     * Получает IP-адрес клиента из запроса.
     */
    public function getClientIp(): ?string
    {
        return Yii::$app->request->userIP;
    }

    /**
     * Получает User-Agent клиента.
     */
    public function getUserAgent(): ?string
    {
        return Yii::$app->request->userAgent;
    }
}
