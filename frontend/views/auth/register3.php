<?PHP

use yii\widgets\ActiveForm;
use backend\models\SiteMenus;
use yii\helpers\ArrayHelper;
use backend\components\Functions;
use backend\models\SiteSpecialists;
use yii\helpers\Html;
use backend\models\ViewDoctorSpecialist;

?>
<style>
    .help-block-error{
        font-weight: 400;
    }
</style>
<section class="signin-doctor login-doctor">
    <div class="container-fluid">
        <div class="row row_equal_height relative">
            <div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2 col-xs-12">
                <div class="login-user-body">
                    <div class="row">
                    <?= $this->render("/layouts/partials/login-menu") ?>
                    </div>
                    <div class="login-user-inner">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="register_title">Qeydiyyatdan keç</h3>
                                    <?php
                                    /*
                                    $errors = [];
                                    if (count($errors) > 0) {
                                        ?>
                                        <div class="alert alert-danger">
                                            <ul>
                                                <?php
                                                foreach ($errors as $key => $value) {
                                                    ?>
                                                    <li><?= $value[0] ?></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                    }
                                    */
                                    ?>
                                    <?PHP
                                    $form = ActiveForm::begin([
                                        'options' => [
                                            'enctype' => 'multipart/form-data',
                                            "class" => "doc-log",
                                            'autocomplete'=>'off'
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
                                           <?= $form->field($model, 'name',$template)->textInput(['class' => 'form-control', 'autocomplete'=>'off', 'placeholder' => 'Ad, Soyad'])->label(false) ?>
                                        </div>
                                        <div class="select-part">
                                            <?PHP
                                            $model->gender = $model->isNewRecord ? 1 : $model->gender;
                                            echo $form->field($model, 'gender', ['template' => "{input}\n{hint}"])->dropDownList(['0' => 'Qadın', '1' => 'Kişi'], ['class' => 'form-control selectpicker', 'minimumResultsForSearch' => "-1", 'data-placeholder' => "Cinsiniz"]); ?>
                                        </div>
                                        <div class="input-group">
                                            <label>
                                                <?= $form->field($model, 'phone_number',$template)->textInput(['type' => 'number','class'=>"form-control",'placeholder'=>'Mobil Telefon ( Nümunə: 0550001122 )'])->label(false) ?>
                                            </label>
                                        </div>
<!--                                        <div class="input-group">-->
<!--                                            --><?PHP
//                                            echo $form->field($model, 'phone')->textInput(['maxlength' => true, "class" => "form-control", "placeholder" => "Mobil Telefon ( Nümunə: 0550001122 )"])->label(false);
//                                            //                                            echo $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
//                                            //                                                'mask' => '999999999999',
//                                            //                                                'options' => ["class" => "form-control", "placeholder" => "Mobil nömrə.Nümunə: 994550001122"]
//                                            //                                            ])->label(false);
//                                            ?>
<!--                                        </div>-->
<!--                                        <div class="input-group">-->
<!--                                            --><?PHP ////$form->field($model, 'phone')->textInput(['maxlength' => true, "class" => "form-control", "placeholder" => "Mobil Telefon *"])->label(false)*/ ?>
<!--                                            --><?PHP //// $form->field($model, 'phone_number')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '999999999999', 'options' => ["class" => "form-control", "placeholder" => "Mobil nömrə.Nümunə: 994550001122"]])->label(false);*/ ?>
<!--                                        </div>-->
                                        <div class="input-group">
                                           <?= $form->field($model, 'email', $template)->textInput(['class' => 'form-control', 'placeholder' => 'E-mail'])->label(false) ?>
                                        </div>
                                        <div class="input-group">
                                            <?= $form->field($model, 'birthday')->widget(\yii\widgets\MaskedInput::className(), [
                                                'options' => ["class" => "form-control datepicker_register", "placeholder" => "Doğum tarixi"],
                                                'clientOptions' => ['alias' =>  'dd-mm-yyyy'],
                                            ])->label(false);
                                            ?>
                                            <?php
//                                            \yii\widgets\MaskedInput::widget([
//                                                'model' => $model,
//                                                'name'  => 'User[birthday]',
//                                                'options'=> ["placeholder" => "Mobil nömrə.Nümunə: 994550001122"],
//                                                'clientOptions' => ['class'=>'form-control','alias' =>  'date'],
//                                            ]);
                                            ?>
                                        </div>
                                        <div class="input-group">
                                           <?= $form->field($model, 'password', $template)->passwordInput(['class' => 'form-control', 'placeholder' => 'Şifrə'])->label(false) ?>
                                        </div>
                                        <div class="input-group">
                                           <?= $form->field($model, 'repassword', $template)->passwordInput(['class' => 'form-control', 'placeholder' => 'Təkrar şifrə'])->label(false) ?>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-effect">Qeydiyyatdan keç</button>
                                    <?php ActiveForm::end(); ?>
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
