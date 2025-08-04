<?php

use app\repositories\LinkRepository;
use app\repositories\LinkVisitRepository;
use app\services\BotDetectorInterface;
use app\services\LinkService;
use app\services\RemoteBotDetector;
use yii\caching\CacheInterface;
use app\dto\RateLimitConfig;
use app\services\RandomShortCodeGenerator;
use app\services\LinkCacheService;
use app\services\ShortCodePoolProvider;
use app\services\ShortCodeProviderInterface;
use app\services\ShortCodeGeneratorInterface;
use app\services\RateLimitResponder;
use app\filters\DefaultRequestInfoProvider;
use app\filters\RequestInfoProviderInterface;

return [
    'definitions' => [
        CacheInterface::class => fn() => Yii::$app->cache,

        RateLimitConfig::class => fn() => RateLimitConfig::fromParams(Yii::$app->params['rateLimit'] ?? []),

        RequestInfoProviderInterface::class => DefaultRequestInfoProvider::class,

        RateLimitResponder::class => fn($c) => new RateLimitResponder(
            Yii::$app->response,
            Yii::$app->log->getLogger(),
            $c->get(RequestInfoProviderInterface::class)
        ),

        ShortCodeGeneratorInterface::class => RandomShortCodeGenerator::class,
        ShortCodeProviderInterface::class => ShortCodePoolProvider::class,

        LinkCacheService::class => fn($c) => new LinkCacheService(
            $c->get(CacheInterface::class)
        ),

        LinkService::class => fn($c) => new LinkService(
            $c->get(ShortCodeProviderInterface::class),
            $c->get(LinkCacheService::class),
            $c->get(\app\repositories\LinkRepository::class),
            Yii::$app->params['appBaseUrl']
        ),


        BotDetectorInterface::class => RemoteBotDetector::class,
        LinkRepository::class => LinkRepository::class,
        LinkVisitRepository::class => LinkVisitRepository::class,
    ],
];