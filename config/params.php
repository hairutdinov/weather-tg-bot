<?php

return [
    'adminEmail'     => 'admin@example.com',
    'senderEmail'    => 'noreply@example.com',
    'senderName'     => 'Example.com mailer',
    'telegram'       => [
        'token'       => $_ENV['TG_API_TOKEN'],
        'baseUrl'     => 'https://api.telegram.org/bot',
        'webhookUrl'  => $_ENV['TG_WEBHOOK_URL'],
        'secretToken' => $_ENV['TG_SECRET_TOKEN'],
        'adminChatId' => $_ENV['TG_ADMIN_CHAT_ID']
    ],
    'yandexweather' => [
        'token'    => $_ENV['YANDEXWEATHER_API_TOKEN'],
        'baseUrl'  => 'https://api.weather.yandex.ru/v2/',
        'forecast' => 'forecast'
    ],
    'weatherstack' => [
        'token'    => $_ENV['WEATHERSTACK_API_TOKEN'],
        'baseUrl'  => 'http://api.weatherstack.com',
        'current' => 'current'
    ]
];
