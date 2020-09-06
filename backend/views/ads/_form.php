<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\components\Functions;
/* @var $this yii\web\View */
/* @var $model backend\models\SiteAds */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="site-ads-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group custom-group">
                <?= $form->field($model, 'title')->textInput(['maxlength' => true, "class" => "form-control"]) ?>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group custom-group">
                <?= $form->field($model, 'text')->textarea(["class" => "form-control"]) ?>
            </div>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group custom-group">
                <?= $form->field($model, 'premium_type')->dropDownList($model->getPremiumTypes(),['class'=>'w-100 from-control','minimumResultsForSearch'=>"-1","select2"=>"select2"]); ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="form-group upload">
                <?= $form->field($model, 'image')->fileInput(['class' => 'form-control-file']) ?>
            </div>

        </div>
        <div class="col-md-4 col-sm-6 col-xs-12" style="display: flex;align-items: center">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="SiteAds[is_blood]" <?= $model->is_blood == 1 ? 'checked' : '' ?> value="1">
                    Qan axtarışı
                </label>
            </div>
        </div>
        <div class="col-md-4">
            <img class="img-responsive" src="<?=Functions::getUploadUrl().'/ads/small/'.$model->image?>">
        </div>

    </div>

    </div>
    <div class="form-group">
        <?= Html::submitButton('Yadda saxla', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
