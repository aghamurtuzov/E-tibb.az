<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'frontend\assets\JqueryAsset',
        'frontend\assets\YiiAsset',
        'frontend\assets\ResetAsset',
        'frontend\assets\BootstrapAsset',
    ];

    public function __construct()
    {
        $this->js = [
//            'assets/js/jquery.min.js',
            'assets/js/bootstrap.min.js',
            'assets/js/slick.js',
            'assets/js/numscroller-1.0.js',
            'assets/js/main.js?v='.time(),
            'assets/js/bootstrap-datepicker-v1.js',
            'assets/js/moment.min.js',
            'assets/js/daterangepicker.min.js',
            'assets/js/mask.js',

            'assets/js/bootstrap-select.min.js',
        ];

        $this->css = [
                'assets/css/bootstrap.min.css',
                'assets/css/bootstrap-select.min.css',
                'assets/css/font-awesome.min.css',
                'assets/css/slick-theme.css',
                'assets/css/slick-theme.css',
                'assets/css/slick.css',
                'assets/css/lightbox.css',
                'assets/css/datepicker.css',
                'assets/css/daterangepicker.min.css',
                'assets/css/style.css?v='.time(),
            ];

        $this->publishOptions = [
            'forceCopy'=>true,
        ];
    }
}
