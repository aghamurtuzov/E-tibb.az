<?PHP

use yii\widgets\ActiveForm;
use backend\models\SiteMenus;
use yii\helpers\ArrayHelper;
use backend\components\Functions;
use backend\models\SiteSpecialists;
use yii\helpers\Html;
use backend\models\ViewDoctorSpecialist;

//$form = ActiveForm::begin([
//    'options'=>[
//        'enctype' => 'multipart/form-data',
//        "class" => "doc-log",
//    ],
//    'fieldConfig' => [
//        'template' => "<div class=\"form-group custom-group\">{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div></div>",
//        'labelOptions' => [ 'class' => '' ],
//        'options'=>[
//            'class'=>'form-group'
//        ],
//    ],
//]);
//$template        = ['template' => "{input}{hint}<div class=\"help-block help-block-error\">{error}</div>"];

?>
<style>
    /*
    .form-group {
        margin: 0 !important;
    }

    .help-block {
        margin: 0 !important;
        display: none;
    }
    */
</style>

<section class="signin-doctor login-doctor">
    <div class="container-fluid">
        <div class="row row_equal_height relative">
            <div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2 col-xs-12">
                <div class="login-user-body">
                    <?= $this->render("/layouts/partials/login-menu") ?>
                    <div class="login-user-inner">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="register_title">Qeydiyyatdan keç</h3>

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
                                    <div class="form-list">
                                        <div class="input-group">
                                            <span><i class="fa fa-user" aria-hidden="true"></i></span>
                                            <?= $form->field($model, 'name',$template)->textInput(["class" => "form-control", "placeholder" => "Ad, Soyad"])->label(false) ?>

                                        </div>
                                        <div class="select-part">
                                            <?PHP
//                                            $model->gender = $model->isNewRecord ? 1 : $model->gender;
                                            echo $form->field($model, 'gender', $template)->dropDownList(['1' => 'Kişi','0' => 'Qadın'], ['class' => 'form-control selectpicker', 'minimumResultsForSearch' => "-1", 'data-placeholder' => "Cinsiniz"]); ?>
                                        </div>

                                        <div class="input-group">
                                            <?= $form->field($model, 'files')->fileInput(['class'=>'form-control','accept' => 'image/*'])->label(false) ?>
                                        </div>

                                        <div class="input-group">
                                            <?= $form->field($model, 'birthday',$template)->widget(\yii\widgets\MaskedInput::className(), [
                                                'clientOptions' => ['alias' =>  'dd-mm-yyyy'],
                                                'options' => ["class" => "form-control datepicker_register", "placeholder" => "Doğum tarixi"]
                                            ])->label(false) ?>
                                        </div>
                                        <div class="select-part">
                                            <?= $form->field($model, 'specialists', $template)->dropDownList(ArrayHelper::map(SiteSpecialists::find()->orderBy('name')->all(), 'id', 'name'), ['title' => 'İxtisas seçimi', 'class' => 'form-control selectpicker', 'placeholder'=>'Select an item'])->label(false) ?>
                                        </div>

                                        <div class="input-group">
                                            <?= $form->field($model, 'email',$template)->textInput(['maxlength' => true, 'autocomplete'=>'off', "class" => "form-control", "placeholder" => "E-mail"])->label(false) ?>
                                        </div>
                                        <div class="input-group">
                                            <?PHP //$form->field($model, 'phone')->textInput(['maxlength' => true, "class" => "form-control", "placeholder" => "Mobil Telefon *"])->label(false)*/ ?>
                                            <?PHP
                                            echo $form->field($model, 'phone',$template)->textInput(['type' => 'number','maxlength' => true, "class" => "form-control", "placeholder" => "Mobil Telefon ( Nümunə: 0550001122 )"])->label(false);
//                                            echo $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
//                                                'mask' => '999999999999',
//                                                'options' => ["class" => "form-control", "placeholder" => "Mobil nömrə.Nümunə: 994550001122"]
//                                            ])->label(false);
                                            ?>
                                        </div>
                                        <div class="input-group">
                                            <?= $form->field($model, 'password',$template)->passwordInput(['class' => 'form-control', 'placeholder' => 'Şifrə'])->label(false); ?>
                                            <span><i class="fa fa-lock" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="input-group">
                                            <?= $form->field($model, 'repassword',$template)->passwordInput(['class' => 'form-control', 'placeholder' => 'Təkrar şifrə'])->label(false); ?>
                                            <span><i class="fa fa-lock" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="input-group" style="margin-bottom: 0;">
                                            <?PHP
                                            $label = '<div style="margin-left: 5px;display: inherit;"><a href="'.Yii::$app->params['site.url'].'qaydalar/hekim" target="_blank">Qaydaları</a> qəbul edirəm</div>';
                                            echo $form->field($model, 'agree_rules')->checkbox(['label'=>$label,'checked'=>false]); ?>
                                        </div>
                                    </div>


                                    <button type="submit" class="btn btn-effect">Qeydiyyatdan keç</button>
                                    <?php ActiveForm::end(); ?>
                                    <!--                                <div class="login-ask">-->
                                    <!--                                    <p class="left-item">Hesabınız var ?</p>-->
                                    <!--                                    <a href="" class="right-item">Hesabınıza daxil olun</a>-->
                                    <!--                                </div>-->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 hidden-sm hidden-xs bg-main">
                <img src="<?= Yii::$app->params["site.url"]; ?>assets/img/daxil_ol.jpg" alt="login-doctor"
                     class="img-responsive">
            </div>
        </div>
    </div>
</section>
