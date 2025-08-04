<?php

namespace tests\unit\services;

use app\services\LinkCacheService;
use PHPUnit\Framework\TestCase;
use yii\caching\CacheInterface;

class LinkCacheServiceTest extends TestCase
{
    /**
     * Проверяет, что метод cacheLink() сохраняет ссылку в кеш,
     * а getOriginalUrl() — успешно её извлекает.
     */
    public function testCacheLinkAndRetrieve()
    {
        $mockCache = $this->createMock(CacheInterface::class);

        $shortCode = 'abc123';
        $originalUrl = 'https://example.com';

        // Ожидаем, что set() будет вызван с конкретными параметрами
        $mockCache->expects($this->once())
            ->method('set')
            ->with("link:$shortCode", $originalUrl, 3600);

        // Когда get вызывается, он должен вернуть сохранённый URL
        $mockCache->method('get')->willReturn($originalUrl);

        $service = new LinkCacheService($mockCache);

        // Сохраняем ссылку в кеш
        $service->cacheLink($shortCode, $originalUrl);

        // Получаем ссылку из кеша
        $result = $service->getOriginalUrl($shortCode);

        // Проверяем, что получено то же значение
        $this->assertEquals($originalUrl, $result);
    }

    /**
     * Проверяет, что метод getOriginalUrl() возвращает null,
     * если ссылка в кеше отсутствует.
     */
    public function testGetOriginalUrlReturnsNullIfNotFound()
    {
        $mockCache = $this->createMock(CacheInterface::class);

        // Симулируем отсутствие значения в кеше
        $mockCache->method('get')->willReturn(false);

        $service = new LinkCacheService($mockCache);

        // Ожидаем, что результат будет null
        $result = $service->getOriginalUrl('notfound');

        $this->assertNull($result);
    }
}
