<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\SiteAds */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Site Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="site-ads-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if (Yii::$app->session->hasFlash("success")) {
        echo '<br /><div class="alert alert-success">' . Yii::$app->session->getFlash("success") . '</div>';
    }
    ?>

    <?php
    if (Yii::$app->session->hasFlash("error")) {
        echo '<br /><div class="alert alert-danger">' . Yii::$app->session->getFlash("error") . '</div>';
    }
    ?>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Nəğd ödə', ['pay-cash', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Onlayn ödə', ['pay-online', 'id' => $model->id, 'premium_type' => $model->type], ['class' => 'btn btn-warning']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title:ntext',
            'slug:ntext',
            'text',
            'image:ntext',
            'type',
            'premium_expiry',
            'rating_value',
            'review_count',
            'is_blood',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
