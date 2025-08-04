<?php

declare(strict_types=1);

use app\dto\RateLimitConfig;
use app\filters\DefaultRequestInfoProvider;
use app\filters\RequestInfoProviderInterface;
use app\services\LinkCacheService;
use app\services\RandomShortCodeGenerator;
use app\services\RateLimitResponder;
use app\services\ShortCodeGeneratorInterface;
use app\services\ShortCodePoolProvider;
use app\services\ShortCodeProviderInterface;
use behaviors\GlobalThrottleBehavior;
use yii\caching\CacheInterface;
use yii\log\PsrLogger;
use yii\redis\Cache;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dbRead = require __DIR__ . '/db-read.php';


$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'LIRAF5WPERiYziww7ZapZGKyH48_E_-G',
        ],
        'cache' => [
            'class' => Cache::class,
            'redis' => [
                'hostname' => 'redis',
                'port' => 6379,
                'database' => 0,
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'dbRead' => $dbRead,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'POST api/link/shorten' => 'api/link/shorten',
                '<short:[a-zA-Z]{5}>' => 'site/redirect',
                'test-queue' => 'site/test-queue',
            ],
        ],
        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
        ],

    ],
    'params' => $params,

    'container' => require __DIR__ . '/container.php',

    'as globalThrottle' => [
        'class' => app\behaviors\GlobalThrottleBehavior::class,
    ],


];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
