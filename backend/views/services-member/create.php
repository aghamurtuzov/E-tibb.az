<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteServicesMember */

$this->title = 'Create Site Services Member';
$this->params['breadcrumbs'][] = ['label' => 'Site Services Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-services-member-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
