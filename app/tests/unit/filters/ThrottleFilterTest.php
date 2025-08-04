<?php

declare(strict_types=1);

namespace tests\unit\filters;

use app\filters\ThrottleFilter;
use app\filters\RequestInfoProviderInterface;
use app\dto\RateLimitConfig;
use app\services\RateLimitResponder;
use yii\caching\ArrayCache;
use PHPUnit\Framework\TestCase;
use yii\base\Action;

/**
 * Unit-тесты для фильтра ThrottleFilter, ограничивающего частоту запросов (rate-limiting).
 */
final class ThrottleFilterTest extends TestCase
{
    private ArrayCache $cache;
    private RequestInfoProviderInterface $requestInfo;
    private RateLimitResponder $responder;
    private RateLimitConfig $config;

    /**
     * Подготовка окружения перед каждым тестом.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Используем in-memory cache для изоляции тестов
        $this->cache = new ArrayCache();

        // Мокаем поставщика информации о клиенте
        $this->requestInfo = $this->createMock(RequestInfoProviderInterface::class);
        $this->requestInfo->method('getClientKey')->willReturn('client123');

        // Мокаем сервис ответа при превышении лимита
        $this->responder = $this->createMock(RateLimitResponder::class);

        // Конфиг ограничения: 2 запроса на 60 секунд
        $this->config = new RateLimitConfig(limit: 2, period: 60);
    }

    /**
     * Проверяет, что фильтр разрешает запросы до достижения лимита.
     */
    public function testAllowsRequestsUntilLimit(): void
    {
        $filter = new ThrottleFilter(
            $this->cache,
            $this->requestInfo,
            $this->responder,
            $this->config
        );

        $action = $this->createMock(Action::class);

        // Первые два запроса должны пройти
        $this->assertTrue($filter->beforeAction($action));
        $this->assertTrue($filter->beforeAction($action));
    }

    /**
     * Проверяет, что при превышении лимита вызывается метод tooManyRequests().
     */
    public function testTriggersResponderOnLimitExceeded(): void
    {
        // Ожидаем вызов метода tooManyRequests только один раз
        $this->responder->expects($this->once())->method('tooManyRequests');

        $filter = new ThrottleFilter(
            $this->cache,
            $this->requestInfo,
            $this->responder,
            $this->config
        );

        $action = $this->createMock(Action::class);

        // Два допустимых запроса
        $filter->beforeAction($action);
        $filter->beforeAction($action);

        // Третий должен вызвать ограничение
        $filter->beforeAction($action);
    }
}
