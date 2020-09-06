<?php



$params = array_merge(

    require __DIR__ . '/../../common/config/params.php',

    require __DIR__ . '/../../common/config/params-local.php',

    require __DIR__ . '/params.php',

    require __DIR__ . '/params-local.php'

);



return [

    'id' => 'etibb-backend',

    'basePath' => dirname(__DIR__),

    'controllerNamespace' => 'backend\controllers',

    'bootstrap' => ['log'],

    'modules' => [],

    'homeUrl' => '/admin',

    'timezone'=>'Asia/Baku',

    'components' => [

        'request' => [

            'baseUrl' => '/admin',

            'csrfParam' => '_csrf-etibb-admin',

        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        'user' => [
            'identityClass' => 'backend\models\AdminUsers',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identityetibb', 'httpOnly' => true],
            //'identityCookie' => ['name' => '_wwwetibbaz', 'httpOnly' => true],
        ],

        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'wwwetibbaz',
            //'name' => 'wwwetibbaz',
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

        'assetManager' => [

            'appendTimestamp' => true,

            'bundles' => [

                'edgardmessias\assets\nprogress\NProgressAsset' => [

                    'configuration' => [

                        'minimum' => 0.08,

                        'showSpinner' => true,

                    ],

                    'page_loading' => true,

                    'pjax_events' => true,

                    'jquery_ajax_events' => false,

                ],

            ],

        ],

        'urlManager' => [

            'enablePrettyUrl' => true,

            'showScriptName' => false,

            'normalizer' => [

                'class' => 'yii\web\UrlNormalizer'

            ],

            'rules' => [

                '<alias:\w+>' => 'site/<alias>',

                /*'login' => 'site/login',*/

                '<controller:\w+>/<id:\d+>' => '<controller>/view',

                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',

                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ]

        ],

        'image' => [

            'class' => 'yii\image\ImageDriver',

            'driver' => 'GD',  //GD or Imagick

        ]

    ],

    'params' => $params,

];

