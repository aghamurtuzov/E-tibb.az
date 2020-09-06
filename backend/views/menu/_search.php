<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteMenusSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-menus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'parent') ?>

    <?= $form->field($model, 'menu_order') ?>

    <?= $form->field($model, 'link') ?>

    <?php // echo $form->field($model, 'target') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'hidden') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>