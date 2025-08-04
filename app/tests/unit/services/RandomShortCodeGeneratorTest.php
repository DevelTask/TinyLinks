<?php

namespace tests\unit\services;

use app\services\RandomShortCodeGenerator;
use PHPUnit\Framework\TestCase;

class RandomShortCodeGeneratorTest extends TestCase
{
    /**
     * Проверяет, что метод generate() возвращает строку длиной 5 символов.
     */
    public function testGenerateReturnsCorrectLength()
    {
        $generator = new RandomShortCodeGenerator();
        $code = $generator->generate();

        $this->assertIsString($code, 'Результат должен быть строкой');
        $this->assertSame(5, strlen($code), 'Длина строки должна быть 5 символов');
    }

    /**
     * Проверяет, что строка состоит только из латинских букв верхнего и нижнего регистра.
     */
    public function testGenerateReturnsOnlyAllowedChars()
    {
        $generator = new RandomShortCodeGenerator();
        $code = $generator->generate();

        $this->assertMatchesRegularExpression('/^[a-zA-Z]{5}$/', $code, 'Код должен содержать только латинские буквы');
    }

    /**
     * Проверяет, что при многократном вызове generate() возвращаются разные значения.
     * Это важно для случайности и уникальности.
     */
    public function testGenerateReturnsDifferentValues()
    {
        $generator = new RandomShortCodeGenerator();
        $codes = [];

        // Генерируем 10 кодов
        for ($i = 0; $i < 10; $i++) {
            $codes[] = $generator->generate();
        }

        // Проверяем, что хотя бы одна пара отличается (в массиве больше одной уникальной строки)
        $this->assertGreaterThan(1, count(array_unique($codes)), 'Ожидается, что хотя бы два кода будут разными');
    }
}
