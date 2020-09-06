<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteConsultationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-consultation-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'doctor_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'question') ?>

    <?php // echo $form->field($model, 'q_datetime') ?>

    <?php // echo $form->field($model, 'answer') ?>

    <?php // echo $form->field($model, 'a_datetime') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'a_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
