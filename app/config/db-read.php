<?php

return [
    'class' => yii\db\Connection::class,
    'dsn' => sprintf(
        'mysql:host=%s;dbname=%s',
        $_ENV['DB_READ_HOST'] ?: 'localhost',
        $_ENV['DB_READ_NAME'] ?: 'yii2basic'
    ),
    'username' => $_ENV['DB_READ_USER'] ?: 'root',
    'password' => $_ENV['DB_READ_PASS'] ?: '',
    'charset' => $_ENV['DB_READ_CHARSET'] ?: 'utf8',
];
