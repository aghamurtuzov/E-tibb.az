<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;

$form = ActiveForm::begin(['enableClientScript' => false,
    'options'=>[
        'enctype' => 'multipart/form-data',
    ]]);
?>
<section class="user-setting-page donate-inner about stocks">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="/">Ana səhifə </a></li>
                        <li><a href="#">Şəxsi məlumatlarım </a></li>
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
                                <?php if(Yii::$app->session->hasFlash("success")){
                                    echo '<div class="alert alert-success">'.Yii::$app->session->getFlash("success").'</div>';
                                }?>
                                <?php
                                    if(count($errors)>0)
                                    {
                                        ?>
                                        <div class="alert alert-danger">
                                            <ul>
                                                <?php
                                                    foreach ($errors as $val)
                                                    {
                                                        ?>
                                                        <li><?=$val[0]?></li>
                                                        <?php
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h3>ŞƏXSİ MƏLUMATLARIM</h3>
                            </div>
                        </div>
                            <div class="form">
                                <div class="form-list text-center">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label>
                                                    <input type="text" name="UserSettingsForm[name]" class="form-control" placeholder="Ad, Soyad *" value="<?=$model->name?>">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label>
                                                    <input type="text" name="UserSettingsForm[phone_number]" class="form-control" placeholder="Mobil Telefon *" value="<?=$model->phone_number?>">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label>
                                                    <input type="text" name="UserSettingsForm[pass]" class="form-control" placeholder="Köhnə Şifrə">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label>
                                                    <input type="text" name="UserSettingsForm[newpass]" class="form-control" placeholder="Yeni Şifrə">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row flex-between">
<!--                                        <div class="col-xs-12">-->
<!--                                            <div class="donate-file">-->
<!--                                                <div class="upload relative">-->
<!--                                                    <span>Şəkil əlavə et</span>-->
<!--                                                    <label class="custom-file-upload">-->
<!--                                                        Yüklə-->
<!--                                                    </label>-->
<!--                                                    <input type="file" name="UserSettingsForm[photo]" class="form-control" placeholder="Şəkil">-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div class="col-xs-12 text-right">
                                            <button type="submit" class="btn btn-success">Təsdiqlə</button>
                                        </div>
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

<?php
ActiveForm::end();
?>
