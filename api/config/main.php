<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'homeUrl' => '/api', // New code
    'timeZone' => 'Asia/Baku', // New code
    'modules' => [
        'front' => [
            'class' => 'api\modules\site\Module',
        ],
        'general' => [
            'class' => 'api\modules\general\Module',
        ],
        'doctor' => [
            'class' => 'api\modules\doctor\Module',
        ],
        'enterprise' => [
            'class' => 'api\modules\enterprise\Module',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest']
        ],
//        'authManager' => [
//            'class' => 'yii\rbac\PhpManager',
//            'defaultRoles' => ['guest'],
//            'itemFile' => '@common/modules/rbac/data/items.php',
//            'assignmentFile' => '@common/modules/rbac/data/assignments.php',
//            'ruleFile' => '@common/modules/rbac/data/rules.php',
//        ],
        /*
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'csrfParam' => '_csrf-api',
            'baseUrl' => '/api', // New code
        ],
        */
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'baseUrl' => '/api'
        ],
//        'user' => [
////            'identityClass' => 'api\models\Api',
////            'enableAutoLogin' => false, // true
////            'enableSession' => false,
////            'loginUrl' =>''
//            //'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
//            'identityClass' => 'api\models\AdminUsers',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-etibb-backend', 'httpOnly' => true],
////            'loginUrl' =>''
//        ],
        'user' => [
            'loginUrl' => ['site/notlogin'],
            'identityClass' => 'frontend\models\User', //LoginUserModel
            'enableAutoLogin' => true,
            //'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'identityCookie' => ['name' => '_wwwetibbaz', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'wwwetibbaz',
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
//      'errorHandler' => [
//          'errorAction' => 'site/index',
//      ],
        // New code
        'urlManager' => [
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                '<module:\w+>/<controller:\w+>/<action>/<id:\d+>' => '<module>/<controller>/<action>',
            ],
        ],
        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD',  //GD or Imagick
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'userCheck' => [
            'class' => 'api\components\UserHelper'
        ]
    ],

    'params' => $params,
];
