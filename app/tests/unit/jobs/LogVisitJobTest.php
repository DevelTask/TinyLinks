<?php

declare(strict_types=1);

namespace tests\unit\jobs;

use PHPUnit\Framework\TestCase;
use app\jobs\LogVisitJob;
use app\models\Link;
use app\models\LinkVisit;
use app\services\BotDetectorInterface;
use app\repositories\LinkRepository;
use app\repositories\LinkVisitRepository;

final class LogVisitJobTest extends TestCase
{
    public function testJobProcessesVisitSuccessfully(): void
    {
        $shortCode = 'abcDE';
        $userAgent = 'Test UA';
        $ip = '127.0.0.1';

        $link = $this->createMock(Link::class);
        $link->id = 1;

        $visit = $this->createMock(LinkVisit::class);

        $botDetector = $this->createMock(BotDetectorInterface::class);
        $botDetector->expects($this->once())
            ->method('isBot')
            ->with($userAgent)
            ->willReturn(false);

        $linkRepo = $this->createMock(LinkRepository::class);
        $linkRepo->expects($this->once())
            ->method('findByShortCode')
            ->with($shortCode)
            ->willReturn($link);

        $visitRepo = $this->createMock(LinkVisitRepository::class);
        $visitRepo->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('link_id'))
            ->willReturn($visit);

        $visitRepo->expects($this->once())
            ->method('save')
            ->with($visit);

        $job = new LogVisitJob([
            'shortCode' => $shortCode,
            'userAgent' => $userAgent,
            'ip' => $ip,
            'botDetector' => $botDetector,
            'linkRepository' => $linkRepo,
            'visitRepository' => $visitRepo,
        ]);

        $job->execute($this->createMock(\yii\queue\Queue::class));
    }
}
