<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Şifrə dəyiş';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="donate-inner about stocks">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?=Yii::$app->params['site.url']?>">Ana səhifə </a></li>
                        <li><a href="#">Şifrəni yenilə</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12 donate-right">
                <div class="row">
                    <div class="col-md-12">
                        <div class="block-back">
                            <div class="row">
                                <div class="col-md-12 col-12">
                                    <?php
                                    if(Yii::$app->session->hasFlash('error-pass-reset')){
                                        $msg = Yii::$app->session->getFlash('error-pass-reset');
                                        echo "<div class=\"alert alert-danger\" role=\"alert\">{$msg}</div>";
                                    };
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Yeni şifrəni daxil edin:</h3>
                                </div>
                            </div>
                            <div class="form">
                                <div class="form-list text-center">
                                    <div class="row">
                                        <?php $form = ActiveForm::begin(['enableClientScript' => false,'id' => 'reset-password-form']); ?>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label>
                                                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label(false) ?>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="text-align: left;">
                                            <?= Html::submitButton('Təsdiqlə', ['class' => 'btn btn-success']) ?>
                                        </div>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
