<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
$form = ActiveForm::begin(['enableClientScript' => false,
    'options'=>[
        'enctype' => 'multipart/form-data',
    ]]);
?>
<div class="t-card -h-top">

    <form id="login_form" action="/etibb/auth/login">
        <p id="result_text"></p>
        <div class="form-group custom-group">
            <?= $form->field($model, 'email')->textInput(['placeholder'=>'Email']) ?>
        </div>
        <div class="form-group custom-group">
            <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Şifrə']) ?>
        </div>
        <div class="form-group custom-group">
            <label class="control control--checkbox float-left">
                <?= $form->field($model, 'rememberMe')->checkbox(['value'=>'1']) ?>
                <input type="checkbox">
                <div class="control__indicator"></div>
            </label>

            <a href="javascript:void(0);" class="float-right link">
                Şifrəni unutmusunuz?
            </a>
        </div>
        <div class="clearfix"></div>
        <div class="form-group custom-group -h-top">
            <?php
            echo Html::submitButton("Daxil ol", ['class' => 'cb cyan w-m-100 d-inline-block text-center','data-toggle' => "modal" ,"data-target" => "#notification"]);
            ?>
        </div>
    </form>
</div>

<?php
ActiveForm::end();
?>