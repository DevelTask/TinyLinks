<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',

    //rateLimit
    'rateLimit' => [
        'limit' => $_ENV['RATE_LIMIT'] ?: 10,
        'period' => $_ENV['RATE_PERIOD'] ?: 60,
    ],

    'appBaseUrl' => $_ENV['APP_BASE_URL'] ?: 'http://localhost:8080',
];