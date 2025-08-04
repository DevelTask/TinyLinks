<?php

namespace app\services;

class RandomShortCodeGenerator implements ShortCodeGeneratorInterface
{
    private const ALLOWED_CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private const CODE_LENGTH = 5;

    public function generate(): string
    {
        $result = '';
        $maxIndex = strlen(self::ALLOWED_CHARS) - 1;

        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            $result .= self::ALLOWED_CHARS[random_int(0, $maxIndex)];
        }

        return $result;
    }
}