<?php

namespace app\services;

/**
 * Класс RandomShortCodeGenerator реализует интерфейс генерации коротких ссылок.
 * Генерирует случайную строку фиксированной длины, состоящую из латинских букв.
 */
class RandomShortCodeGenerator implements ShortCodeGeneratorInterface
{
    // Допустимые символы для кода (a-z, A-Z)
    private const ALLOWED_CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // Длина кода по умолчанию
    private const CODE_LENGTH = 5;

    /**
     * Генерирует случайный короткий код из допустимых символов.
     *
     * @return string Случайная строка длиной CODE_LENGTH
     * @throws \Exception если генерация случайного числа не удалась
     */
    public function generate(): string
    {
        $result = '';
        $maxIndex = strlen(self::ALLOWED_CHARS) - 1;

        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            // random_int используется вместо rand для криптостойкой генерации
            $result .= self::ALLOWED_CHARS[random_int(0, $maxIndex)];
        }

        return $result;
    }
}
