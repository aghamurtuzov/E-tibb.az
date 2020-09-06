<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteEnterprisesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-enterprises-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'photo') ?>

    <?= $form->field($model, 'expires') ?>

    <?php // echo $form->field($model, 'promotion') ?>

    <?php // echo $form->field($model, 'feature') ?>

    <?php // echo $form->field($model, 'rating') ?>

    <?php // echo $form->field($model, 'about') ?>

    <?php // echo $form->field($model, 'services_prices') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
