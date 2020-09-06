<?php
return [
    'id' => 'etibb-az-id',
    'name' => 'E-tibb.az',
    'language' => 'az-Az',
    'sourceLanguage' => 'az-Az',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
