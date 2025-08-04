<?php

namespace tests\unit\services;

use app\services\RandomShortCodeGenerator;
use PHPUnit\Framework\TestCase;

class RandomShortCodeGeneratorTest extends TestCase
{
    public function testGenerateReturnsCorrectLength()
    {
        $generator = new RandomShortCodeGenerator();
        $code = $generator->generate();

        $this->assertIsString($code);
        $this->assertSame(5, strlen($code));
    }

    public function testGenerateReturnsOnlyAllowedChars()
    {
        $generator = new RandomShortCodeGenerator();
        $code = $generator->generate();

        $this->assertMatchesRegularExpression('/^[a-zA-Z]{5}$/', $code);
    }

    public function testGenerateReturnsDifferentValues()
    {
        $generator = new RandomShortCodeGenerator();
        $codes = [];

        for ($i = 0; $i < 10; $i++) {
            $codes[] = $generator->generate();
        }

        // Проверим, что хотя бы одна пара значений отличается
        $this->assertGreaterThan(1, count(array_unique($codes)));
    }
}
