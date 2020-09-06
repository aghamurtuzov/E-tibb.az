<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteComments */

$this->title = 'Redakte et ';
$this->params['breadcrumbs'][] = ['label' => 'Rəylər', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Redakte Et';
?>
<div class="site-comments-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
