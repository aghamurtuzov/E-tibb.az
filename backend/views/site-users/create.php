<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SiteUsers */

$this->title = 'Create Site Users';
$this->params['breadcrumbs'][] = ['label' => 'Site Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
