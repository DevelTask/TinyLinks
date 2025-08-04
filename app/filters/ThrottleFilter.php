<?php

namespace app\filters;

use yii\base\ActionFilter;
use yii\caching\CacheInterface;
use app\dto\RateLimitConfig;
use app\services\RateLimitResponder;

/**
 * Фильтр ограничения частоты запросов (rate limiting).
 * Применяется к действиям контроллера для защиты от перегрузки/злоупотреблений.
 */
class ThrottleFilter extends ActionFilter
{
    private CacheInterface $cache;
    private RequestInfoProviderInterface $requestInfoProvider;
    private RateLimitResponder $responder;
    private RateLimitConfig $config;

    // Префикс для ключей кэша, чтобы избежать конфликтов с другими кэшируемыми значениями
    public string $keyPrefix = 'throttle_';

    /**
     * Конструктор с внедрением зависимостей.
     *
     * @param CacheInterface $cache – кэш, в который записывается количество запросов
     * @param RequestInfoProviderInterface $requestInfoProvider – поставщик информации о клиенте (IP + User-Agent)
     * @param RateLimitResponder $responder – отвечает клиенту в случае превышения лимита
     * @param RateLimitConfig $config – конфигурация лимитов (кол-во, период)
     */
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

    /**
     * Выполняется перед каждым действием контроллера.
     * Считает количество запросов клиента и блокирует доступ при превышении лимита.
     */
    public function beforeAction($action)
    {
        // Получаем уникальный ключ клиента (например, md5 от IP + User-Agent)
        $key = $this->keyPrefix . $this->requestInfoProvider->getClientKey();

        // Получаем текущее количество запросов из кэша
        $count = (int)$this->cache->get($key);

        // Если лимит превышен — вызываем обработчик ответа с 429 Too Many Requests
        if ($count >= $this->config->limit) {
            $this->responder->tooManyRequests($this->config->period);
        }

        // Иначе увеличиваем счетчик и обновляем его с TTL
        $this->cache->set($key, $count + 1, $this->config->period);

        return parent::beforeAction($action);
    }
}
