<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\SiteAds */

$this->title = 'Update Site Ads: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Site Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-ads-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
