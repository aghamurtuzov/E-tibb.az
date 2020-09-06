<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteSpecialists */

$this->title = 'İxtisas | Düzəliş et';
$this->params['breadcrumbs'][] = ['label' => 'Site Specialists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-specialists-update">

    <?= $this->render('_form', [
        'model' => $model,
        'customPath' => $customPath
    ]) ?>

</div>
