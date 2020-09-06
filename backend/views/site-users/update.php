<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteUsers */

$this->title = 'Redaktə et: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Site Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-users-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
