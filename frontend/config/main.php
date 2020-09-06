<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl' => '',
    'timezone'=>'Asia/Baku',
    'language'=>'az-AZ',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'MyComponent' => [
            'class' => 'frontend\components\Seo'
        ],
        'request' => [
            'baseUrl' => '',
            'csrfParam' => '_csrf-frontend',
        ],
//        'devicedetect' => [
//            'class' => 'alexandernst\devicedetect\DeviceDetect'
//        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js'=>['assets/js/jquery.min.js']
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ]
            ]
        ],
        /*
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false, // localda true olur
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'noreply.etibb@gmail.com',
                'password' => '856yhir487y',
                'port' => '587',
                'encryption' => 'tls',
                'plugins' => [
                    [
                        'class' => 'Swift_Plugins_LoggerPlugin',
                        'constructArgs' => [new Swift_Plugins_Loggers_ArrayLogger], //thanks @germansokolov13
                        // it could also be any Swift_Plugins_Logger implementation (e.g., the EchoLogger)
                    ],
                ],
            ],
        ],
        */
//        'mailer' => [ esas
//            'class' => 'yii\swiftmailer\Mailer',
//            // send all mails to a file by default. You have to set
//            // 'useFileTransport' to false and configure a transport
//            // for the mailer to send real emails.
//            'viewPath' => '@common/mail',
//            'useFileTransport' => false,
//            'htmlLayout'=>false,
//            'textLayout'=>false,
//
//            'transport' => [
//                'class' => 'Swift_SmtpTransport',
//                'host' => 'smtp.yandex.ru',
//                'username' => 'no-reply@e-tibb.az',
//                'password' => 's98dfeu3',
//                'port' => '465',
//                'encryption' => 'ssl',
//            ],
//        ],
//        'user' => [
//            'loginUrl' => ['qeydiyyat'],
//            'identityClass' => 'common\models\User',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
//        ],
//        'user' => [
//            'loginUrl' => ['login/mogin'],
//            'identityClass' => 'frontend\models\LoginUserModel',
//            'enableAutoLogin' => true,
//            //'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
//            'identityCookie' => ['name' => '_wwwetibbaz', 'httpOnly' => true],
//        ],
        'user' => [
            'loginUrl' => ['qeydiyyat'],
            'identityClass' => 'frontend\models\User', //LoginUserModel
            'enableAutoLogin' => true,
            //'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'identityCookie' => ['name' => '_wwwetibbaz', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            //'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' =>[
//                [
//                    'pattern' => '/panel/',
//                    'route' => 'panel/test',
//                ],
                [
                    'pattern' => '/obyekt-qeydiyyat/<id:\d+>',
                    'route'   => 'auth-enterprise/register',
                ],
                [
                    'pattern' => '/obyekt-qeydiyyat-paketler/<id:\d+>',
                    'route'   => 'auth-enterprise/step2',
                ],
                [
                    'pattern' => '/sitemap.xml',
                    'route' => 'sitemap/index',
                ],
                [
                    'pattern' => '/sitemap-hekimler.xml',
                    'route' => 'sitemap/doctor-index',
                ],
                [
                    'pattern' => '/sitemap-obyekt-<slug:.*?>.xml',
                    'route' => 'sitemap/base-obyekt',
                ],
                [
                    'pattern' => '/sitemap-magaza-<slug:.*?>.xml',
                    'route' => 'sitemap/base-shop',
                ],
                [
                    'pattern' => '/sitemap-xeberler-<slug:.*?>.xml',
                    'route' => 'sitemap/base-news',
                ],
                [
                    'pattern' => '/sitemap-ixtisas-<slug:.*?>.xml',
                    'route' => 'sitemap/base-specialty',
                ],
                [
                    'pattern' => '/sitemap-xeberler.xml',
                    'route' => 'sitemap/news',
                ],
                [
                    'pattern' => '/sitemap-pages.xml',
                    'route' => 'sitemap/pages',
                ],
                [
                    'pattern' => '/sitemap-pages',
                    'route' => 'sitemap/pages',
                ],

//============================================================================================================================








                [
                    'pattern' => '/xeberler.rss',
                    'route' => 'rss/news',
                ],
                [
                    'pattern' => '/xeberler-<id:\d+>.rss',
                    'route' => 'rss/news',
                ],
                [
                    'pattern' => '/aksiyalar.rss',
                    'route' => 'rss/promotions',
                ],
                [
                    'pattern' => '/aksiyalar-<id:\d+>.rss',
                    'route' => 'rss/promotions',
                ],
                [
                    'pattern' => '/hekimler.rss',
                    'route' => 'rss/doctors',
                ],
                [
                    'pattern' => '/hekimler-<id:\d+>.rss',
                    'route' => 'rss/doctors',
                ],
                [
                    'pattern' => '/beledci.rss',
                    'route' => 'rss/guide',
                ],
                [
                    'pattern' => '/beledci-<id:\d+>.rss',
                    'route' => 'rss/guide',
                ],


//                [
/*                    'pattern' => '/sitemap-<cat:.*?>-<id:\d+>.xml',*/
//                    'route' => 'sitemap/doctors',
//                ],
                [
                    'pattern' => '/beledci/sitemap-<slug:.*?>-<id:\d+>.xml',
                    'route' => 'sitemap/guide',
                ],
                [
                    'pattern' => '/sitemap-aksiyalar.xml',
                    'route' => 'sitemap/promotions',
                ],
                [
                    'pattern' => '<slug:haqqimizda>',
                    'route' => 'static/index',
                ],
                [
                    'pattern' => '/tinymce',
                    'route' => 'static/tinymce',
                ],
                [
                    'pattern' => '/elaqe',
                    'route' => 'static/contact',
                ],
                [
                    'pattern' => '/qaydalar',
                    'route' => 'static/rules',
                ],
                [
                    'pattern' => '/qaydalar/<slug:.*?>',
                    'route' => 'static/rules',
                ],
                [
                    'pattern' => '/static/<slug:.*?>',
                    'route' => 'static/index',
                ],
                [
                    'pattern' => '/qanver/<slug:.*?>-<id:\d+>',
                    'route' => 'blood/index',
                ],
                [
                    'pattern' => '/hekimler',
                    'route' => 'doctor/index',
                ],
                [
                    'pattern' => '/premium-hekimler',
                    'route' => 'doctor/premium',
                ],
                [
                    'pattern' => '/xeberler',
                    'route' => 'news/index',
                ],
                [
                    'pattern' => '/xeberler/<slug:.*?>-<id:\d+>',
                    'route' => 'news/index',
                ],
                [
                    'pattern' => '/videolar/',
                    'route' => 'videos/index',
                ],
                [
                    'pattern' => '/aksiyalar',
                    'route' => 'promotion/index',
                ],
                [
                    'pattern' => '/beledci/<slug:.*?>-<id:\d+>',
                    'route' => 'enterprise/index',
                ],
                [
                    'pattern' => '/beledci-premium/<slug:.*?>-<id:\d+>',
                    'route' => 'enterprise/premium-enterprise',
                ],
//                [
/*                    'pattern' => '/n-<slug:.*?>-<id:\d+>',*/
//                    'route' => 'news/category',
//                ],
                [
                    'pattern' => "/xeber/<slug:.*?>-<id:\d+>",
                    'route' => 'news/single',
                ],
                [
                    'pattern' => "/xeber/<slug:.*?>-<id:\d+>/amp",
                    'route' => 'amp/single-news',
                ],
                [
                    'pattern' => '/aksiya/<slug:.*?>-<id:\d+>',
                    'route' => 'promotion/single',
                ],
                [
                    'pattern' => '/obyekt-axtar/',
                    'route' => 'enterprise/search',
                ],
                [
                    'pattern' => '/axtar/',
                    'route' => 'search/index',
                ],
                [
                    'pattern' => '/catlist/',
                    'route' => 'search/catlist',
                ],
                [
                    'pattern' => '/<cat:.*?>/<id:\d+>-<slug:.*?>/amp',
                    'route' => 'amp/single-enterprise',
                ],
                [
                    'pattern' => '/<cat:.*?>/<id:\d+>-<slug:.*?>/<doc:.*?>',
                    'route' => 'enterprise/about',
                ],
                [
                    'pattern' => '/<cat:.*?>/<id:\d+>-<slug:.*?>',
                    'route' => 'enterprise/about',
                ],
                [
                    'pattern' => '/<cat:.*?>/<slug:.*?>-<id:\d+>',
                    'route' => 'doctor/doctor-info',
                ],
                [
                    'pattern' => '/<cat:.*?>/<slug:.*?>-<id:\d+>/amp',
                    'route' => 'amp/single-doctor',
                ],
                [
                    'pattern' => '/<cat:.*?>/<slug:.*?>-<id:\d+>/<currentTab:.*?>',
                    'route' => 'doctor/doctor-info',
                ],
                [
                    'pattern' => '/<cat:.*?>-<id:\d+>',
                    'route' => 'doctor/doctors',
                ],
                [
                    'pattern' => '/<cat:.*?>/<id:\d+>-<slug:.*?>/amp',
                    'route' => 'amp/single-enterprise',
                ],
//                [
/*                    'pattern' => '/kateqoriya/<slug:.*?>',*/
//                    'route' => 'news/category',
//                ],
//                [
//                    'pattern' => '/qeydiyyat',
//                    'route'   => 'auth/register'
//                ],
//                [
//                    'pattern' => '/qeydiyyat2',
//                    'route'   => 'auth/register2'
//                ],
                [
                    'pattern' => '/istifadeci-qeydiyyat',
                    'route'   => 'auth/register3',
                ],
                [
                    'pattern' => '/hekim-qeydiyyat',
                    'route'   => 'auth-doctor/register',
                ],
                [
                    'pattern' => '/hekim-daxil-ol',
                    'route'   => 'auth-doctor/login',
                ],
                [
                    'pattern' => '/hekim-qeydiyyat-paketler',
                    'route'   => 'auth-doctor/step2',
                ],
                [
                    'pattern' => '/tenzimlemeler',
                    'route'   => 'profile/settings',
                ],
                [
                    'pattern' => '/parol-yenile',
                    'route'   => 'site/request-password-reset',
                ],
                [
                    'pattern' => '/parol-yenilenmesi',
                    'route'   => 'site/accept-password-reset',
                ],
                [
                    'pattern' => '/yeni-parol',
                    'route'   => 'site/reset-password',
                ],
                [
                    'pattern' => '/parol-yenilenmesi',
                    'route'   => 'site/success-password-reset',
                ],
//                [
//                    'pattern' => '/hekim-tenzimlemeler',
//                    'route'   => 'profile-doctor/settings',
//                ],
//                [
//                    'pattern' => '/obyekt-tenzimlemeler/',
//                    'route'   => 'profile-enterprise/settings',
//                ],
                // ok enterprise
//                [
//                    'pattern' => '/profil/xidmetler',
//                    'route' => 'profile-enterprise/services',
//                ],
//                [
//                    'pattern' => '/profil/mutexessisler',
//                    'route' => 'profile-enterprise/workers',
//                ],
//                [
//                    'pattern' => '/profil/mutexessis-redakte/<id:\d+>',
//                    'route'   => 'profile-enterprise/doctor-update',
//                ],
//                [
//                    'pattern' => '/profil/mutexessis-sil',
//                    'route'   => 'profile-enterprise/doctor-delete',
//                ],
//                [
//                    'pattern' => 'profil/sual-cavab',
//                    'route'   => 'profile-doctor/questions',
//                ],
//                [
//                    'pattern' => 'profil/sual-cavab/redakte',
//                    'route'   => 'profile-doctor/update-questions',
//                ],
//                [
//                    'pattern' => 'profil/sual-cavab/saxla',
//                    'route'   => 'profile-doctor/save-questions',
//                ],
//                [
//                    'pattern' => 'profil/sual-cavab/sil',
//                    'route'   => 'profile-doctor/delete-answer',
//                ],
//                [
//                    'pattern' => 'profil/sual-cavab/sual-sil',
//                    'route'   => 'profile-doctor/delete-questions',
//                ],

//                [
//                    'pattern' => '/profil/qebul-gunleri',
//                    'route' => 'profile-doctor/workdays',
//                ],
//                [
//                    'pattern' => '/profil/timelist',
//                    'route' => 'profile-doctor/time-expand',
//                ],
//                [
//                    'pattern' => '/profil/qebul-gunleri/sil',
//                    'route' => 'profile-doctor/delete-work-days',
//                ],
//                [
//                    'pattern' => '/profil/qebul-gunleri/redakte',
//                    'route'   => 'profile-doctor/update',
//                ],
//                [
//                    'pattern' => '/profil/randevu',
//                    'route' => 'profile-doctor/appoint',
//                ],
//                [
//                    'pattern' => '/profil/randevu-redakte',
//                    'route' => 'profile-doctor/accept-appoint',
//                ],
//                [
//                    'pattern' => '/profil/randevu-sil',
//                    'route' => 'profile-doctor/delete-appoint',
//                ],
                [
                    'pattern' => '/profil/aksiyalar',
                    'route' => 'promotion-form/index',
                ],
                [
                    'pattern' => '/profil/odenis',
                    'route' => 'promotion-form/payment',
                ],
                [
                    'pattern' => '/profil/aksiya-yarat',
                    'route' => 'promotion-form/create-promotion',
                ],
                [
                    'pattern' => '/profil/aksiyalar/redakte/<id:\d+>',
                    'route' => 'promotion-form/update-promotion',
                ],
                [
                    'pattern' => '/profil/aksiyalar/sil',
                    'route' => 'promotion-form/delete-promotion',
                ],
                [
                    'pattern' => '/profil/index',
                    'route' => 'profile/index',
                ],
                [
                    'pattern' => '/profil',
                    'route' => 'profile/index',
                ],
                [
                    'pattern' => '/qanver',
                    'route' => 'blood/blood',
                ],
                [
                    'pattern' => '/qan-elani-ver',
                    'route' => 'blood/add-blood',
                ],
                [
                    'pattern' => '/qanver/<slug:.*?>-<id:\d+>',
                    'route' => 'blood/index',
                ],
                [
                    'pattern' => '/cache-delete',
                    'route'   => 'cache/delete'
                ],
                [
                    'pattern' => '/doctor-video',
                    'route'   => 'video/doctor'
                ],
                [
                    'pattern' => '/client-video',
                    'route'   => 'video/client'
                ],
                [
                    'pattern' => '/sizə-tecili-qan-lazımdır',
                    'route'   => 'static/blood-about'
                ],
                [
                    'pattern' => '/donor-olmaq-isteyirsiniz',
                    'route'   => 'static/donor-about'
                ],
                [
                    'pattern' => '/donor-olmaq-ucun-ne-etmeli',
                    'route'   => 'static/donor-what-about'
                ],

//                [
//                    'pattern' => '/test/update-id',
//                    'route' => 'test/update-userid',
//                ],
//                [
//                    'pattern' => '/profil/',
//                    'route' => 'profile/index',
//                ],


                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD',  //GD or Imagick
        ]
    ],
    'params' => $params,
];
