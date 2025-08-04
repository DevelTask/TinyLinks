<?php

namespace app\services;

interface BotDetectorInterface
{
    public function isBot(string $userAgent): bool;
}
