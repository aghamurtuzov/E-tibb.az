<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class YiiAsset extends AssetBundle
{
    public $sourcePath = '@yii/assets';
    public $js = [
//        'yii.js',
    ];
    public $depends = [
        'frontend\assets\JqueryAsset'
    ];
}
