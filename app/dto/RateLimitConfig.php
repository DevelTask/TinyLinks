<?php

declare(strict_types=1);

namespace app\dto;

/**
 * Конфигурация для ограничения запросов (Rate Limiting)
 *
 * @immutable
 */
final readonly class RateLimitConfig
{
    /**
     * @param positive-int $limit  Максимальное количество запросов
     * @param positive-int $period Период времени в секундах
     */
    public function __construct(
        public int $limit,
        public int $period,
    ) {
        if ($limit <= 0) {
            throw new \InvalidArgumentException('RateLimitConfig: limit must be positive integer');
        }

        if ($period <= 0) {
            throw new \InvalidArgumentException('RateLimitConfig: period must be positive integer');
        }
    }

    /**
     * Фабрика для конфигурации из params
     */
    public static function fromParams(array $params): self
    {
        if (!isset($params['limit'], $params['period'])) {
            throw new \InvalidArgumentException('RateLimitConfig: missing required parameters');
        }

        return new self((int)$params['limit'], (int)$params['period']);
    }
}