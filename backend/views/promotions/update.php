<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteMenus */

$this->title = 'Redaktə Et: | ' . $model->headline;
$this->params['breadcrumbs'][] = ['label' => 'Aqsiya', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->headline, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-promotions-update">
    <?= $this->render('_form', [
        'model' => $model,
        'customPath' => $customPath,
    ]) ?>

</div>
