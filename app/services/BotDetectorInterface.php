<?php

namespace app\services;

/**
 * Интерфейс BotDetectorInterface описывает контракт для сервиса,
 * который определяет, является ли пользовательский агент ботом.
 */
interface BotDetectorInterface
{
    /**
     * Проверяет, является ли переданный User-Agent ботом.
     *
     * @param string $userAgent строка заголовка User-Agent запроса
     * @return bool true, если это бот; false — если обычный пользователь
     */
    public function isBot(string $userAgent): bool;
}
