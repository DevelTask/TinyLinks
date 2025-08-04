<?php
declare(strict_types=1);

namespace app\behaviors;

use app\filters\ThrottleFilter;
use yii\base\ActionEvent;
use yii\base\Application;
use yii\base\Behavior;
use Yii;

/**
 * Глобальное поведение для применения ThrottleFilter
 * Работает для всех API-роутов (/api/*)
 */
final class GlobalThrottleBehavior extends Behavior
{
    private ThrottleFilter $filter;

    public function __construct(ThrottleFilter $filter, $config = [])
    {
        $this->filter = $filter;
        parent::__construct($config);
    }

    public function events(): array
    {
        return [
            Application::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function beforeAction(ActionEvent $event): bool
    {
        // Отключаем лимиты для Gii и Debug в DEV
        if (YII_ENV_DEV && str_starts_with($event->action->uniqueId, 'debug') || str_starts_with($event->action->uniqueId, 'gii')) {
            return true;
        }

        // Ограничиваем только API-роуты
        if (str_starts_with($event->action->uniqueId, 'api/')) {
            return $this->filter->beforeAction($event->action);
        }

        return true;
    }
}
