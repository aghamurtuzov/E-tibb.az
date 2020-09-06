<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SiteNews */

$this->title = 'Redaktə Et: | ' . $model->headline;
$this->params['breadcrumbs'][] = ['label' => 'Site News', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-news-update">
    <?= $this->render('_form', [
        'model' => $model,
        'customPath' => $customPath,
    ]) ?>

</div>
    