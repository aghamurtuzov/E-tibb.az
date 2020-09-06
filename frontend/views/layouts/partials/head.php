<?php

use yii\helpers\Url;
use yii\helpers\Html;

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= \yii\helpers\Html::csrfMetaTags() ?>
    <link rel="apple-touch-icon" sizes="180x180" href="<?=Yii::$app->params["site.url"]?>assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?=Yii::$app->params["site.url"]?>assets/img/favicon/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="256x256"  href="<?=Yii::$app->params["site.url"]?>assets/img/favicon/android-chrome-256x256.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=Yii::$app->params["site.url"]?>assets/img/favicon/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=Yii::$app->params["site.url"]?>assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" href="<?=Yii::$app->params["site.url"]?>assets/img/favicon/favicon.ico">

    <link rel="manifest" href="<?=Yii::$app->params["site.url"]?>assets/img/favicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?=Yii::$app->params["site.url"]?>assets/img/favicon//mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <?php

//    $this->registerMetaTag(Yii::$app->params['og_title'], 'og_title');
//    $this->registerMetaTag(Yii::$app->params['og_description'], 'og_description');
//    $this->registerMetaTag(Yii::$app->params['og_url'], 'og_url');
//    $this->registerMetaTag(Yii::$app->params['og_image'], 'og_image');
//    $this->registerMetaTag(Yii::$app->params['og_type'], 'og_type');
//    $this->registerMetaTag(Yii::$app->params['og_image_width'], 'og_image_width');
//    $this->registerMetaTag(Yii::$app->params['og_image_height'], 'og_image_height');

    if(isset(Yii::$app->params['header.tags']) && !empty(Yii::$app->params['header.tags']))
    {
        echo Yii::$app->params['header.tags'];
    }
    ?>

    <?php $this->head() ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-135630111-4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-135630111-4');
    </script>
</head>