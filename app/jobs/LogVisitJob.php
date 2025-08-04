<?php

namespace app\jobs;

use Yii;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\queue\JobInterface;
use app\services\BotDetectorInterface;
use app\repositories\LinkRepository;
use app\repositories\LinkVisitRepository;

/**
 * Очередь логирования визита по короткой ссылке.
 * Выполняется асинхронно через yii2-queue.
 */
class LogVisitJob extends BaseObject implements JobInterface
{
    // Передаётся при создании задания
    public string $shortCode;
    public string $userAgent;
    public string $ip;

    // Определение зависимостей — можно переопределить при необходимости (тестирование и т.п.)
    public $botDetector = BotDetectorInterface::class;
    public $linkRepository = LinkRepository::class;
    public $visitRepository = LinkVisitRepository::class;

    /**
     * Основной метод очереди. Вызывается воркером при исполнении задания.
     */
    public function execute($queue)
    {
        // Получаем зависимости из контейнера или из указанных значений
        $botDetector = Instance::ensure($this->botDetector, BotDetectorInterface::class);
        $linkRepo = Instance::ensure($this->linkRepository, LinkRepository::class);
        $visitRepo = Instance::ensure($this->visitRepository, LinkVisitRepository::class);

        // Пропускаем визит, если это бот
        if ($botDetector->isBot($this->userAgent)) {
            return;
        }

        // Находим ссылку по короткому коду
        $link = $linkRepo->findByShortCode($this->shortCode);
        if (!$link) {
            Yii::warning("Ссылка с кодом '{$this->shortCode}' не найдена.", __METHOD__);
            return;
        }

        // Генерируем объект визита
        $now = new \DateTimeImmutable();
        $visit = $visitRepo->create([
            'link_id' => $link->id,
            'visited_at' => $now->format('Y-m-d H:i:s'),
            'year' => (int)$now->format('Y'),
            'month' => (int)$now->format('n'),
            'user_agent' => $this->userAgent,
            'ip_address' => $this->ip,
        ]);

        // Пытаемся сохранить визит, логируем при ошибке
        try {
            $visitRepo->save($visit);
            Yii::info("Лог визита сохранён для link_id={$link->id}", __METHOD__);
        } catch (\Throwable $e) {
            Yii::error("Ошибка при сохранении визита: " . $e->getMessage(), __METHOD__);
        }
    }
}
