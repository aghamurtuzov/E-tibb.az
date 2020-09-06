<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Şifrəmi unutdum';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="user-reset-password donate-inner about stocks">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?= Yii::$app->params['site.url'] ?>">Ana səhifə </a></li>
                        <li><a href="#">Şifrəmi unutdum</a></li>
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
                                <div class="col-md-12">
                                    <?php
                                    if (Yii::$app->session->hasFlash('success-pass-reset')) {
                                        $msg = Yii::$app->session->getFlash('success-pass-reset');
                                        echo "<div class=\"alert alert-success\" role=\"alert\">{$msg}</div>";
                                    }
                                    if (Yii::$app->session->hasFlash('error-pass-reset')) {
                                        $msg = Yii::$app->session->getFlash('error-pass-reset');
                                        echo "<div class=\"alert alert-danger\" role=\"alert\">{$msg}</div>";
                                    };
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>ŞƏXSİ MƏLUMATLARIM</h3>
                                    <div class="alert alert-info" role="alert" style="margin-bottom: 0;">
                                        Qeydiyyatdan keçdiyiniz telefon nömrənizi daxil edin.<br>
                                        Şifrə yenilənməsi ilə bağlı link telefon nömrənizə göndəriləcək.
                                    </div>
                                </div>
                            </div>
                            <div class="form">
                                <div class="form-list text-center">
                                    <?php $form = ActiveForm::begin(['enableClientScript' => false, 'id' => 'request-password-reset-form']); ?>
                                    <div class="row flex-between">
                                        <div class="col-xs-12">
                                            <div class="input-group">
                                                <?= $form->field($model, 'phone_number')->textInput(['type' => 'number', 'autofocus' => true, 'placeholder' => 'Telefon nömrəsi'])->label(false); ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 text-left">
                                            <?= Html::submitButton('Göndər', ['class' => 'btn btn-success']) ?>
                                        </div>
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
</section>
