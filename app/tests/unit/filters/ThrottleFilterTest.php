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

final class ThrottleFilterTest extends TestCase
{
    private ArrayCache $cache;
    private RequestInfoProviderInterface $requestInfo;
    private RateLimitResponder $responder;
    private RateLimitConfig $config;

    protected function setUp(): void
    {

        parent::setUp();
        $this->cache = new ArrayCache();
        $this->requestInfo = $this->createMock(RequestInfoProviderInterface::class);
        $this->requestInfo->method('getClientKey')->willReturn('client123');
        $this->responder = $this->createMock(RateLimitResponder::class);
        $this->config = new RateLimitConfig(limit: 2, period: 60);
    }

    public function testAllowsRequestsUntilLimit(): void
    {
        $filter = new ThrottleFilter(
            $this->cache,
            $this->requestInfo,
            $this->responder,
            $this->config
        );

        $action = $this->createMock(Action::class);

        $this->assertTrue($filter->beforeAction($action));
        $this->assertTrue($filter->beforeAction($action));
    }

    public function testTriggersResponderOnLimitExceeded(): void
    {
        $this->responder->expects($this->once())->method('tooManyRequests');

        $filter = new ThrottleFilter(
            $this->cache,
            $this->requestInfo,
            $this->responder,
            $this->config
        );

        $action = $this->createMock(Action::class);

        $filter->beforeAction($action);
        $filter->beforeAction($action);
        $filter->beforeAction($action); // превышение лимита
    }
}
