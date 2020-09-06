<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SiteSpecialists */

$this->title = 'İxtisas | Əlavə et';
$this->params['breadcrumbs'][] = ['label' => 'Site Specialists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-specialists-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
