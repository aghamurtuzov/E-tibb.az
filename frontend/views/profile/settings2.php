<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;

$form = ActiveForm::begin(['enableClientScript' => false,
    'options'=>[
        'enctype' => 'multipart/form-data',
    ]]);

$this->title = 'ŞƏXSİ MƏLUMATLARIM';
?>

<div class="t-card -h-top">
    <div class="row">
        <div class="col-md-12 col-12">
            <?php if(Yii::$app->session->hasFlash("success")){
                echo '<div class="alert alert-success">'.Yii::$app->session->getFlash("success").'</div>';
            }?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-12">
            <h2 class="ms-title margin-clear"><?= $this->title?></h2>
        </div>
    </div>
    <div class="row -h-top">
        <!--<div class="col-md-auto col-12">
            <div class="upload-image">
                <img src="assets/img/profile.png" class="object-fit d-block m-margin-auto" style="width: 220px;height: 220px;">
                <div class="icon-upload">
                    <label for="fileUpload"><img src="assets/img/icon/upload.png">
                        <input id="fileUpload" class="d-none" type="file"> </label>
                </div>
            </div>
        </div>-->
        <div class="col-md col-12">
            <form action="<?=Url::base();?>/profile/settings" method="POST" class="minimal-margin">
                <div class="form-group custom-group">
                    <?= $form->field($model, 'name')->textInput([]) ?>
                </div>
                <div class="form-group custom-group">
                    <?= $form->field($model, 'email')->textInput(['readonly'=> true]) ?>
                </div>
                <div class="form-group custom-group">
                    <?= $form->field($model, 'phone_number')->textInput([]) ?>
                </div>
                <hr />
                <div class="form-group custom-group">
                    <?= $form->field($model, 'pass')->passwordInput([]) ?>
                </div>
                <div class="form-group custom-group">
                    <?= $form->field($model, 'newpass')->passwordInput([]) ?>
                </div>
                <div class="form-group custom-group -h-top-h">
                    <button class="cb orange shadow w-m-100 inner-shadow">Yadda saxla</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
ActiveForm::end();
?>

