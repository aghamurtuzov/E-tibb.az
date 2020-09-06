<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class JqueryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'assets/js/jquery.min.js',
//        'assets/js/jquery-2.1.1.min.js',
    ];
    public $depends = [
    ];
}
