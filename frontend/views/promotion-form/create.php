<?php
use \yii\bootstrap\ActiveForm;
use backend\components\Functions;
$this->title = 'Kompaniyalar';
$photo = !empty($model->photo) ? Functions::getUploadUrl().'promotions'.'/small/'.$model->photo : '';

?>
<style>
    .date-picker-wrapper{
        z-index: 10 !important;
    }
    .form-group{
        margin: 0 !important;
    }
</style>
<section class="stocks-add donate-inner about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="index.html">Ana səhifə </a></li>
                        <li class="active">Qanver</li>
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
                                        <div class="col-md-12">
                                            <h3>Aksiya əlavə et</h3>
                                            <p>Aksiya əlavə etmək üçün ödəniş tələb olunur.</p>
                                        </div>
                                    </div>
                                    <div class="form">
                                        <?php
                                        $form = ActiveForm::begin([
                                            'enableClientScript' => false,
                                            'options'=>[
                                                'enctype' => 'multipart/form-data',
                                            ]]);
                                        ?>
                                        <div class="form-list row text-center">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label>
                                                        <?= $form->field($model, 'headline')->textInput(['class'=>'form-control', 'placeholder'=>'Aksiya başlığı *' ])->label(false) ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="input-group small-group">
                                                    <label>
                                                        <?= $form->field($model, 'price')->textInput(['type'=>'number','class'=>'form-control', 'placeholder'=>'Məhsul qiyməti *' ])->label(false) ?>
                                                    </label>
                                                </div>
                                                <div class="input-group small-group">
                                                    <label>
                                                        <?= $form->field($model, 'discount')->textInput(['type'=>'number','class'=>'form-control', 'placeholder'=>'Endirim faizi'])->label(false) ?>
                                                    </label>
                                                </div>
                                                <div class="input-group medium-group">
                                                    <label>
                                                        <?= $form->field($model, 'date')->textInput(['class'=>'form-control','id'=>'multi_datepic', 'placeholder'=>'Aksiya müddəti *'])->label(false) ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="stocks-phoneuser">
                                                    <div class="input-group medium-group">
                                                        <label>
                                                            <?= $form->field($model, 'phones[]')->textInput(['class'=>'form-control', 'placeholder'=>'Telefon nömrələri *'])->label(false) ?>
                                                        </label>
                                                    </div>
                                                    <div class="input-group medium-group">
                                                        <label>
                                                            <?= $form->field($model, 'phones[]')->textInput(['class'=>'form-control', 'placeholder'=>'Telefon nömrələri'])->label(false) ?>
                                                        </label>
                                                    </div>
                                                    <div class="add-mobile">
                                                        <button type="button" class="transparent"><span><i class="fa fa-plus-circle" aria-hidden="true"></i> Əlavə et</span></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label>
                                                        <?= $form->field($model, 'address')->textInput(['class'=>'form-control', 'placeholder'=>'Ünvan *'])->label(false) ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="input-border">
                                                    <?= $form->field($model, 'content')->textarea(['class'=>'form-control','id'=>"message",'placeholder'=>'Ətraflı *'])->label(false) ?>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="donate-file">
                                                    <div class="upload relative">
                                                        <span>Şəkil əlavə et *</span>
                                                        <label class="custom-file-upload">
                                                            Yüklə
                                                        </label>
                                                        <?= $form->field($model, 'photo')->fileInput(['class'=>'',"id"=>"file-upload"])->label(false)?>
                                                    </div>
                                                </div>
                                                <div class="donate-file">
                                                    <div class="pay-text">
                                                        <span>Ödəniş edilməyib.</span>
                                                        <h3>15 AZN</h3>
                                                    </div>
                                                    <button type="submit" class="btn-effect">Ödəniş et</button>
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
                        <div class="advert block-back">
                            <h3>Burda reklam
                                yeri</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>