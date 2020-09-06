<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteComments */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Site Comments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-comments-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'connect_id',
            'name',
            'email:email',
            'comment:ntext',
            'datetime',
            'positive',
            'status',
            'type',
        ],
    ]) ?>

</div>
