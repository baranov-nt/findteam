<?php
return [
    'language' => 'ru',
    'sourceLanguage' => 'ru',
    'timezone' => 'UTC',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['setting'],
    'components' => [
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 3,
            ],
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 4,
            ],
            'timeout' => 30,
            'cookieParams' => ['httponly' => true, 'lifetime' => 3600 * 4],
        ],
        'setting' => [
            'class' => 'common\components\SetSetting',
        ],
        'i18n' => [
            'class'      => \backend\modules\translate\components\I18N::className(),
            'languages' => ['en', 'fr', 'ch'],
            'format'     => 'db',
            'sourcePath' => [
                __DIR__ . '/../../frontend',
                __DIR__ . '/../../backend',
                __DIR__ . '/../../common',
                __DIR__ . '/../../console',
            ],
            'messagePath' => __DIR__  . '/../../messages',
            'translations' => [
                'app' => [
                    'class'           => yii\i18n\DbMessageSource::className(),
                    'enableCaching'   => true,
                    'cachingDuration' => 60 * 60 * 2, // cache on 2 hours
                ],
                '*' => [
                    'class'           => 'common\components\TranslateContent',
                    'enableCaching'   => true,
                    'cachingDuration' => 60 * 60 * 2, // cache on 2 hours
                ],
            ],
        ],
    ],
];
