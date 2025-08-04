<?php

return [
    'class' => yii\db\Connection::class,
    'dsn' => sprintf(
        'mysql:host=%s;dbname=%s',
        $_ENV['DB_WRITE_HOST'] ?: 'localhost',
        $_ENV['DB_WRITE_NAME'] ?: 'yii2basic'
    ),
    'username' => $_ENV['DB_WRITE_USER'] ?: 'root',
    'password' => $_ENV['DB_WRITE_PASS'] ?: '',
    'charset' => $_ENV['DB_WRITE_CHARSET'] ?: 'utf8',
];
