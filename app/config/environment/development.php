<?php

return [
    'mail' => [
        'host' => getenv('SMTP_HOST'),
        'port' => getenv('SMTP_PORT'),
        'name' => getenv('SMTP_NAME'),
        'pass' => getenv('SMTP_PASS'),
        'secured' => getenv('SMTP_IS_SECURE') > 0,
        'from_email' => getenv('SMTP_FROM_MAIL'),
        'from_name' => getenv('SMTP_FROM_NAME')
    ],
    'amqp' => [
        'host' => getenv('AMQP_HOST'),
        'port' => getenv('AMQP_PORT'),
        'user' => getenv('AMQP_USER'),
        'pass' => getenv('AMQP_PASS'),
        'vhost' => getenv('AMQP_VHOST'),
        'queue' => getenv('AMQP_QUEUE'),
        'exchange' => getenv('AMQP_EXCHANGE')
    ]
];
