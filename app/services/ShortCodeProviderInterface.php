<?php

namespace app\services;

interface ShortCodeProviderInterface
{
    public function getUniqueShortCode(): string;
}
