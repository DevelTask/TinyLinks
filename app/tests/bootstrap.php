<?php
declare(strict_types=1);

// Подключаем автозагрузчик Composer
require __DIR__ . '/../vendor/autoload.php';

// Подключаем сам Yii
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

use yii\web\Application;

// Минимальная конфигурация тестового приложения
$config = [
    'id' => 'test-app',
    'basePath' => dirname(__DIR__),
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'components' => [
        'request' => [
            'cookieValidationKey' => 'test-key',
        ],
        'response' => [
            'class' => yii\web\Response::class,
        ],
    ],
];

// Создаём тестовое Yii-приложение
new Application($config);