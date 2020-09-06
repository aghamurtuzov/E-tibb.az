<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteDoctors */

$this->title = 'Həkim | Düzəliş et';
$this->params['breadcrumbs'][] = ['label' => 'Site Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-doctors-update">

    <?= $this->render('_form', [
        'model' => $model,
        'customPath' => $customPath
    ]) ?>

</div>
