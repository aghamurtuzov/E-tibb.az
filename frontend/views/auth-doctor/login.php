<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;

?>
<section class="login-doctor">
    <div class="row relative">
        <div class="col-md-6">
            <div class="login-user-body">
                <div class="login-menu">
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="/"><img src="/assets/img/logo_img.png" alt="logo"></a>
                            </div>
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li class="active"><a href="#">Həkim paneli <span
                                                    class="sr-only">(current)</span></a></li>
                                    <li><a href="#">Klinika paneli</a></li>
                                    <li><a href="#">Aptek paneli</a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="login-user-inner">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-8">
                                <h3>Hesaba daxil ol</h3>
                                <?php
                                $form = ActiveForm::begin(['enableClientScript' => false,
                                    'options' => [
                                        'enctype' => 'multipart/form-data',
                                    ]]); ?>
                                <div class="form-list">
                                    <div class="input-group">
                                        <?= $form->field($model, 'email')->textInput(['class'=>'form-control','placeholder' => 'E-mail'])->label(false) ?>
                                        <span><i class="fa fa-user" aria-hidden="true"></i></span>
                                    </div>
                                    <div class="input-group">
                                        <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control','placeholder'=>'Şifrə'])->label(false) ?>
                                        <span><i class="fa fa-lock" aria-hidden="true"></i></span>
                                    </div>
                                </div>
                                <div class="forget-password">
                                    <label class="check-button">
                                        <span class="check-btn-text">Yadda saxla</span>
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="right-item"><a href="site/request-password-reset">Şifrəni unutmusunuz?</a></span>
                                </div>
                                <button type="submit" class="btn btn-effect">Daxil ol</button>
                                <?php
                                ActiveForm::end();
                                ?>
                                <div class="login-ask">
                                    <p class="left-item">Hesabınız yoxdur ?</p>
                                    <a href="/hekim-qeydiyyat" class="right-item">Qeydiyyatdan keç!</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <img src="/assets/img/daxil_ol.jpg" alt="login-doctor" class="/assets/img-responsive">
        </div>
    </div>
</section>

