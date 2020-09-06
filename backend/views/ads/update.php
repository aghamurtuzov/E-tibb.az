<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteAds */

$this->title = 'Dəyiş: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Dəyiş';
?>
<div class="site-ads-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
