<?php

namespace app\services;

use app\models\Link;
use app\repositories\LinkRepository;

/**
 * Сервис для создания коротких ссылок.
 */
class LinkService
{
    private ShortCodeProviderInterface $provider;
    private LinkCacheService $cache;
    private LinkRepository $repository;
    private string $baseUrl;

    /**
     * @param ShortCodeProviderInterface $provider
     * @param LinkCacheService $cache
     * @param LinkRepository $repository
     * @param string $baseUrl
     */
    public function __construct(
        ShortCodeProviderInterface $provider,
        LinkCacheService $cache,
        LinkRepository $repository,
        string $baseUrl
    ) {
        $this->provider = $provider;
        $this->cache = $cache;
        $this->repository = $repository;
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    /**
     * Создает короткую ссылку.
     *
     * @param string $originalUrl
     * @return string Полная короткая ссылка
     */
    public function createShortLink(string $originalUrl): string
    {
        // Проверяем, есть ли уже такая ссылка
        $existing = $this->repository->findByOriginalUrl($originalUrl);

        if ($existing !== null) {
            return "{$this->baseUrl}/{$existing->short_code}";
        }

        // Генерируем новый уникальный код
        $code = $this->provider->getUniqueShortCode();

        $model = new Link([
            'original_url' => $originalUrl,
            'short_code' => $code,
        ]);

        $this->repository->save($model);

        // Кешируем
        $this->cache->cacheLink($code, $originalUrl);

        return "{$this->baseUrl}/{$code}";
    }
}
