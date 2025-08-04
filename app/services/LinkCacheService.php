<?php

namespace app\services;

use yii\caching\CacheInterface;

/**
 * Сервис для кэширования соответствий между короткими и оригинальными URL.
 * Позволяет быстрее получать оригинальную ссылку по короткому коду, не обращаясь каждый раз к базе данных.
 */
class LinkCacheService
{
    private CacheInterface $cache;
    private int $ttl; // Время жизни кэша в секундах

    /**
     * @param CacheInterface $cache Компонент кэша (например, FileCache, Redis и т.д.)
     * @param int $ttl Время жизни кэша в секундах (по умолчанию 1 час)
     */
    public function __construct(CacheInterface $cache, int $ttl = 3600)
    {
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    /**
     * Сохраняет оригинальную ссылку в кэш по короткому коду.
     *
     * @param string $shortCode Короткий код ссылки
     * @param string $originalUrl Оригинальный URL
     */
    public function cacheLink(string $shortCode, string $originalUrl): void
    {
        $this->cache->set("link:{$shortCode}", $originalUrl, $this->ttl);
    }

    /**
     * Получает оригинальный URL по короткому коду из кэша.
     *
     * @param string $shortCode Короткий код ссылки
     * @return string|null Оригинальный URL или null, если не найден
     */
    public function getOriginalUrl(string $shortCode): ?string
    {
        return $this->cache->get("link:{$shortCode}") ?: null;
    }
}
