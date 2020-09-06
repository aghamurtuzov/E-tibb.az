<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SiteEnterprises */

$this->title = 'Obyekt | Əlavə et';
$this->params['breadcrumbs'][] = ['label' => 'Site Enterprises', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-enterprises-create">

    <?= $this->render('_form', [
        'model' => $model,
        'customPath' => $customPath
    ]) ?>

</div>
