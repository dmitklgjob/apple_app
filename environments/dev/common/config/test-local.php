<?php

use yii\db\Connection;

return [
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'pgsql:host=postgres;dbname=apple_test',
            'username' => 'apple',
            'password' => 'apple',
            'charset' => 'utf8',
        ],
    ],
];
