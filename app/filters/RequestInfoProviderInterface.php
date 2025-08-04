<?php
declare(strict_types=1);

namespace app\filters;

/**
 * Интерфейс для получения уникального идентификатора клиента
 */
interface RequestInfoProviderInterface
{
    /**
     * Возвращает уникальный ключ для ограничения запросов.
     * Может быть основан на IP, User-Agent, токене и т.д.
     */
    public function getClientKey(): string;

    /**
     * Возвращает IP-адрес клиента (для логирования/аналитики)
     */
    public function getClientIp(): ?string;

    /**
     * Возвращает User-Agent клиента (для логирования/аналитики)
     */
    public function getUserAgent(): ?string;
}
