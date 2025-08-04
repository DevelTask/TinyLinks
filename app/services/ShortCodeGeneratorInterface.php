<?php

namespace app\services;

/**
 * Интерфейс генератора коротких кодов для сокращённых ссылок.
 * Позволяет абстрагировать механизм генерации кода, чтобы легко заменять реализацию.
 */
interface ShortCodeGeneratorInterface
{
    /**
     * Генерирует уникальный короткий код.
     *
     * @return string Сгенерированный код (например, "aZbXQ")
     */
    public function generate(): string;
}
