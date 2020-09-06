<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteConsultation */

$this->title = 'Sualı redaktə et: ' . $model->question;
$this->params['breadcrumbs'][] = ['label' => 'Suallar', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-consultation-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
