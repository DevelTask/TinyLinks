<?php
namespace app\filters;

use yii\base\ActionFilter;
use yii\caching\CacheInterface;
use app\dto\RateLimitConfig;
use app\services\RateLimitResponder;

class ThrottleFilter extends ActionFilter
{
    private CacheInterface $cache;
    private RequestInfoProviderInterface $requestInfoProvider;
    private RateLimitResponder $responder;
    private RateLimitConfig $config;

    public string $keyPrefix = 'throttle_';

    public function __construct(
        CacheInterface $cache,
        RequestInfoProviderInterface $requestInfoProvider,
        RateLimitResponder $responder,
        RateLimitConfig $config,
        array $params = []
    ) {
        $this->cache = $cache;
        $this->requestInfoProvider = $requestInfoProvider;
        $this->responder = $responder;
        $this->config = $config;
        parent::__construct($params);
    }

    public function beforeAction($action)
    {
        $key = $this->keyPrefix . $this->requestInfoProvider->getClientKey();
        $count = (int)$this->cache->get($key);

        if ($count >= $this->config->limit) {
            $this->responder->tooManyRequests($this->config->period);
        }

        $this->cache->set($key, $count + 1, $this->config->period);
        return parent::beforeAction($action);
    }
}
