<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => 'app-backend',
    'layout' => 'adminlte-main',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log', 'geoData'],
    'modules' => [
        'cities' => [
            'class' => 'backend\modules\cities\Module',
        ],
        'company' => [
            'class' => 'backend\modules\company\Module',
        ],
        'content' => [
            'class' => 'backend\modules\content\Module',
        ],
        'countries' => [
            'class' => 'backend\modules\countries\Module',
        ],
        'setting' => [
            'class' => 'backend\modules\setting\Module',
        ],
        'translate' => [
            'class' => 'backend\modules\translate\Module',
        ],
        'user' => [
            'class' => 'backend\modules\user\Module',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'geoData' => [
            'class'             => 'phpnt\geoData\GeoData',         // путь к классу
            'addToCookie'       => true,                            // сохранить в куки
            'addToSession'      => true,                            // сохранить в сессии
            'setTimezoneApp'    => true,                            // установить timezone в formatter (для вывода)
            'cookieDuration'    => 2592000                          // время хранения в сессии
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\Identity',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['ru', 'en', 'fr', 'ch'],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'pattern' => '',
                    'route' => 'site/index',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<controller>/<action>/<id:\d+>',
                    'route' => '<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<controller>/<action>',
                    'route' => '<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<module>/<controller>/<action>/<id:\d+>',
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<module>/<controller>/<action>',
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => ''
                ],
            ]
        ],
    ],
    'params' => $params,
];
