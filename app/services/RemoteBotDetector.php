<?php

namespace app\services;

use Yii;
use yii\httpclient\Client;

/**
 * Сервис для определения, является ли пользовательский агент ботом.
 * Использует внешний API для проверки, с кешированием результата.
 */
class RemoteBotDetector implements BotDetectorInterface
{
    // URL внешнего API, который определяет, является ли user-agent ботом
    private const API_URL = 'http://qnits.net/api/checkUserAgent?userAgent=';

    /**
     * Проверяет, является ли указанный user-agent ботом.
     *
     * @param string $userAgent
     * @return bool true если бот, иначе false
     */
    public function isBot(string $userAgent): bool
    {
        $cacheKey = 'bot:' . md5($userAgent);
        $cache = Yii::$app->cache;

        // Если результат уже есть в кеше — возвращаем его
        if ($cache->exists($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $client = new Client();

        try {
            // Отправляем GET-запрос на внешний API
            $response = $client->get(self::API_URL . urlencode($userAgent))->send();

            // Если ответ успешен и в нем есть поле isBot — кешируем и возвращаем
            if ($response->isOk && isset($response->data['isBot'])) {
                $isBot = (bool)$response->data['isBot'];
                $cache->set($cacheKey, $isBot, 3600 * 24); // кеш на 24 часа
                return $isBot;
            }
        } catch (\Throwable $e) {
            // В случае ошибки логируем, но по умолчанию считаем, что это не бот
            Yii::error("Bot check failed: " . $e->getMessage(), __METHOD__);
        }

        // Без подтверждения от API — считаем user-agent не ботом
        return false;
    }
}
