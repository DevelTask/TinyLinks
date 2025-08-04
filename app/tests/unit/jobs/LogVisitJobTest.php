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

/**
 * Тест на успешную обработку задания логирования визита.
 */
final class LogVisitJobTest extends TestCase
{
    public function testJobProcessesVisitSuccessfully(): void
    {
        // Входные данные
        $shortCode = 'abcDE';
        $userAgent = 'Test UA';
        $ip = '127.0.0.1';

        // Мокаем ссылку (возвращается из репозитория)
        $link = $this->createMock(Link::class);
        $link->id = 1;

        // Мокаем создаваемый объект визита
        $visit = $this->createMock(LinkVisit::class);

        // Мокаем детектор ботов, ожидаем вызов isBot() и возврат false
        $botDetector = $this->createMock(BotDetectorInterface::class);
        $botDetector->expects($this->once())
            ->method('isBot')
            ->with($userAgent)
            ->willReturn(false);

        // Мокаем репозиторий ссылок
        $linkRepo = $this->createMock(LinkRepository::class);
        $linkRepo->expects($this->once())
            ->method('findByShortCode')
            ->with($shortCode)
            ->willReturn($link);

        // Мокаем репозиторий визитов: create() и save() должны быть вызваны
        $visitRepo = $this->createMock(LinkVisitRepository::class);
        $visitRepo->expects($this->once())
            ->method('create')
            ->with($this->arrayHasKey('link_id')) // проверка, что ключ 'link_id' есть в данных
            ->willReturn($visit);

        $visitRepo->expects($this->once())
            ->method('save')
            ->with($visit);

        // Создаём задачу с внедрёнными зависимостями
        $job = new LogVisitJob([
            'shortCode' => $shortCode,
            'userAgent' => $userAgent,
            'ip' => $ip,
            'botDetector' => $botDetector,
            'linkRepository' => $linkRepo,
            'visitRepository' => $visitRepo,
        ]);

        // Выполняем задачу (очередь мокнута, но не используется внутри)
        $job->execute($this->createMock(\yii\queue\Queue::class));
    }
}
