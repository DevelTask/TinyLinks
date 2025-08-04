<?php

namespace app\services;

use Yii;
use yii\httpclient\Client;

class RemoteBotDetector implements BotDetectorInterface
{
    private const API_URL = 'http://qnits.net/api/checkUserAgent?userAgent=';

    public function isBot(string $userAgent): bool
    {
        $cacheKey = 'bot:' . md5($userAgent);
        $cache = Yii::$app->cache;

        // Если уже есть в кеше — вернуть
        if ($cache->exists($cacheKey)) {
            return $cache->get($cacheKey);
        }

        $client = new Client();
        try {
            $response = $client->get(self::API_URL . urlencode($userAgent))->send();
            if ($response->isOk && isset($response->data['isBot'])) {
                $isBot = (bool)$response->data['isBot'];
                $cache->set($cacheKey, $isBot, 3600 * 24); // 24 часа
                return $isBot;
            }
        } catch (\Throwable $e) {
            Yii::error("Bot check failed: " . $e->getMessage(), __METHOD__);
        }

        // Если не удалось проверить — считаем не ботом
        return false;
    }
}
