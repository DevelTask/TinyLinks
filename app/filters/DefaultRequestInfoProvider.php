<?php
declare(strict_types=1);

namespace app\filters;

use Yii;

final class DefaultRequestInfoProvider implements RequestInfoProviderInterface
{
    public function getClientKey(): string
    {
        $ip = $this->getClientIp() ?? 'unknown';
        $userAgent = substr($this->getUserAgent() ?? '', 0, 50);
        return md5($ip . $userAgent);
    }

    public function getClientIp(): ?string
    {
        return Yii::$app->request->userIP;
    }

    public function getUserAgent(): ?string
    {
        return Yii::$app->request->userAgent;
    }
}
