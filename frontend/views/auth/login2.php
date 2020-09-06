<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;

?>
<?php
$form = ActiveForm::begin(['enableClientScript' => false,
    'action' => ['auth/login2'],

    'options'=>[
        'enctype' => 'multipart/form-data',
        'id' => "login_form_modal",
    ]]);
?>
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
<?php
ActiveForm::end();