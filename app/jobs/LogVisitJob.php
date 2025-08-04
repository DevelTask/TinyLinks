<?php

namespace app\jobs;

use Yii;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\queue\JobInterface;
use app\services\BotDetectorInterface;
use app\repositories\LinkRepository;
use app\repositories\LinkVisitRepository;

class LogVisitJob extends BaseObject implements JobInterface
{
    public string $shortCode;
    public string $userAgent;
    public string $ip;

    public $botDetector = BotDetectorInterface::class;
    public $linkRepository = LinkRepository::class;
    public $visitRepository = LinkVisitRepository::class;

    public function execute($queue)
    {
        $botDetector = Instance::ensure($this->botDetector, BotDetectorInterface::class);
        $linkRepo = Instance::ensure($this->linkRepository, LinkRepository::class);
        $visitRepo = Instance::ensure($this->visitRepository, LinkVisitRepository::class);

        if ($botDetector->isBot($this->userAgent)) {
            return;
        }

        $link = $linkRepo->findByShortCode($this->shortCode);
        if (!$link) {
            Yii::warning("Ссылка с кодом '{$this->shortCode}' не найдена.", __METHOD__);
            return;
        }

        $now = new \DateTimeImmutable();

        $visit = $visitRepo->create([
            'link_id' => $link->id,
            'visited_at' => $now->format('Y-m-d H:i:s'),
            'year' => (int)$now->format('Y'),
            'month' => (int)$now->format('n'),
            'user_agent' => $this->userAgent,
            'ip_address' => $this->ip,
        ]);

        try {
            $visitRepo->save($visit);
            Yii::info("Лог визита сохранён для link_id={$link->id}", __METHOD__);
        } catch (\Throwable $e) {
            Yii::error("Ошибка при сохранении визита: " . $e->getMessage(), __METHOD__);
        }
    }
}
