<?php

namespace app\services;

interface ShortCodeGeneratorInterface
{
    public function generate(): string;
}