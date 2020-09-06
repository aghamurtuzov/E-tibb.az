<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SitePackagesTypes */

$this->title = 'Redaktə Et |' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Site Packages Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-packages-types-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
