<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SiteDoctors */

$this->title = 'Həkim | Əlavə et';
$this->params['breadcrumbs'][] = ['label' => 'Site Doctors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-doctors-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
