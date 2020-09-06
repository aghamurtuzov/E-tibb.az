<?php



use yii\helpers\Html;

use yii\widgets\ActiveForm;



/* @var $this yii\web\View */

/* @var $model backend\models\SiteAdsSearch */

/* @var $form yii\widgets\ActiveForm */

?>



<div class="site-ads-search">



    <?php $form = ActiveForm::begin([

        'action' => ['index'],

        'method' => 'get',


    ]); ?>



    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'text') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'premium_expiry') ?>

    <?php // echo $form->field($model, 'premium_type') ?>

    <?php // echo $form->field($model, 'rating_value') ?>

    <?php // echo $form->field($model, 'review_count') ?>

    <?php // echo $form->field($model, 'is_blood') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>


    <div class="form-group">

        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>

        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>

    </div>



    <?php ActiveForm::end(); ?>



</div>
