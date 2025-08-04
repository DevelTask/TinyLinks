<?php
/*define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
require __DIR__ .'/../vendor/autoload.php';*/

// Указываем, что работаем в тестовом окружении
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

// Подключаем автозагрузчик Composer
require __DIR__ . '/../vendor/autoload.php';

// Подключаем Yii
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Загружаем конфигурацию тестов
$config = require __DIR__ . '/../config/test.php';

// Создаём и конфигурируем приложение
new yii\web\Application($config);