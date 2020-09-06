<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\components\Functions;

?>
<style>
    section.donate-inner form div.form-list div.help-block {
         display: block !important;
        float: left;
        font-weight: bold;
        margin-top: 6px;
    }
</style>
<section class="donate-inner about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?=Yii::$app->params['site.url']?>">Ana səhifə </a></li>
                        <li class="active">Qan ver</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-9 donate-right">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="block-back">
                                    <div class="row">
                                        <div class="col-md-12 col-12">
                                        </div>
                                    </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3>Qan elanı ver</h3>
                                                <p>Elan vermək üçün ödəniş tələb olunmur.</p>
                                            </div>
                                        </div>
                                        <div class="form">
                                            <?PHP
                                            $form = ActiveForm::begin([
                                                'options' => [
                                                    'enctype' => 'multipart/form-data',
                                                    "class" => "doc-log",
                                                    'autocomplete'=>"off"
                                                ],
                                                'fieldConfig' => [
                                                    'template' => "<div class=\"form-group custom-group\">{label}{input}{error}{hint}</div>",
                                                    'labelOptions' => ['class' => ''],
                                                    'options' => [
                                                        'class' => 'form-group'
                                                    ],

                                                ],
                                            ]);
                                            $template = ['template' => "{input}{hint}<div class=\"help-block help-block-error\">{error}</div>"];

                                            ?>

                                            <style>
                                                section.donate-inner form div.form-list div.help-block
                                                {
                                                    display: none;
                                                }
                                            </style>
                                            <div class="form-list row text-center">
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <label>
                                                            <?= $form->field($model, 'user_info',$template)->textInput(['minlength' => '3', "class" => "form-control", "placeholder" => "Ad Soyad"])->label(false) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <label>
                                                            <?= $form->field($model, 'email',$template)->textInput([ "class" => "form-control", "placeholder" => "Email"])->label(false) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <label>
                                                            <?= $form->field($model, 'phone',$template)->textInput(["type"=>"number", "class" => "form-control", "placeholder" => "Mobil Nömrə"])->label(false) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <div class="input-group">
                                                        <label>
                                                            <?= $form->field($model, 'title',$template)->textInput(["class" => "form-control", "placeholder" => "Xəstəlik"])->label(false) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-6" style="/*margin-top: 10px*/">
                                                    <?= $form->field($model, 'blood_type',$template)->dropDownList($model->getBloodTypes(),['class'=>'form-control selectpicker mb-0','minimumResultsForSearch'=>"-1","select2"=>"select2", 'data-placeholder'=>"İş təcrübəniz"])->label(false); ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="input-border">
                                                        <?= $form->field($model, 'text',$template)->textarea(["class" => "form-control"])->label(false) ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="donate-file">
                                                        <button type="submit" class="btn-effect">Əlavə et</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php ActiveForm::end(); ?>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?= $this->render('/layouts/partials/ads_block'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>