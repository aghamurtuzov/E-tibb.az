<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SiteMenus */

$this->title = 'Aksiya | Əlavə et';
$this->params['breadcrumbs'][] = ['label' => 'Aqsiya', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-promotions-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
