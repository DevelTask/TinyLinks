<?php

namespace app\services;

use Yii;
use app\models\Link;

/**
 * Сервис для управления пулом заранее сгенерированных коротких кодов.
 * Позволяет получать уникальные короткие коды с минимальной задержкой.
 */
class ShortCodePoolProvider implements ShortCodeProviderInterface
{
    private ShortCodeGeneratorInterface $generator;

    // Ключ для хранения пула кодов в кеше
    private string $cacheKey = 'short_code_pool';

    // Количество кодов, которые будут сгенерированы за раз при пополнении пула
    private int $batchSize = 50;

    public function __construct(ShortCodeGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Получает уникальный короткий код.
     * При необходимости автоматически пополняет пул.
     *
     * @return string
     */
    public function getUniqueShortCode(): string
    {
        // Получаем текущий пул из кеша
        $pool = Yii::$app->cache->get($this->cacheKey) ?: [];

        // Перебираем коды из пула, пока не найдем уникальный
        while (!empty($pool)) {
            $code = array_shift($pool);

            // Проверяем на уникальность
            if (!$this->codeExists($code)) {
                // Обновляем пул в кеше без использованного кода
                Yii::$app->cache->set($this->cacheKey, $pool);
                return $code;
            }
        }

        // Пул пуст или все коды заняты — пополняем и повторяем
        $this->fillPool();
        return $this->getUniqueShortCode();
    }

    /**
     * Генерирует новую партию кодов и сохраняет их в кеш.
     */
    private function fillPool(): void
    {
        $newCodes = [];

        for ($i = 0; $i < $this->batchSize; $i++) {
            $newCodes[] = $this->generator->generate();
        }

        Yii::$app->cache->set($this->cacheKey, $newCodes);
    }

    /**
     * Проверяет, существует ли уже такой короткий код в базе.
     *
     * @param string $code
     * @return bool
     */
    private function codeExists(string $code): bool
    {
        return Link::find()->where(['short_code' => $code])->exists();
    }
}
