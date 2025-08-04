<?php

namespace tests\unit\services;

use app\services\LinkCacheService;
use PHPUnit\Framework\TestCase;
use yii\caching\CacheInterface;

class LinkCacheServiceTest extends TestCase
{
    public function testCacheLinkAndRetrieve()
    {
        $mockCache = $this->createMock(CacheInterface::class);

        $shortCode = 'abc123';
        $originalUrl = 'https://example.com';

        // Проверяем, что set вызывается с нужными аргументами
        $mockCache->expects($this->once())
            ->method('set')
            ->with("link:$shortCode", $originalUrl, 3600);

        $mockCache->method('get')->willReturn($originalUrl);

        $service = new LinkCacheService($mockCache);
        $service->cacheLink($shortCode, $originalUrl);

        $result = $service->getOriginalUrl($shortCode);
        $this->assertEquals($originalUrl, $result);
    }

    public function testGetOriginalUrlReturnsNullIfNotFound()
    {
        $mockCache = $this->createMock(CacheInterface::class);

        $mockCache->method('get')->willReturn(false);

        $service = new LinkCacheService($mockCache);
        $result = $service->getOriginalUrl('notfound');

        $this->assertNull($result);
    }
}
