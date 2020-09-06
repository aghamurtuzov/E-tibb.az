<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteEnterprises */

$this->title = 'Obyekt | Düzəliş et';
$this->params['breadcrumbs'][] = ['label' => 'Site Enterprises', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="site-enterprises-update">

    <?= $this->render('_form', [
        'model' => $model,
        'customPath' => $customPath
    ]) ?>

</div>
