

<?php

use yii\widgets\ActiveForm;

$template = ['template' => "{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div>"];

$form = ActiveForm::begin([
    'action' => ['auth/register2'],
    'enableClientValidation' => false,
    'options' => [
        'enctype' => 'multipart/form-data',
        "id" => "register_form_modal"
    ]]);

$user_types = $model->user_types;

?>
<h4>Növü</h4>
<?php
echo $form->field($model, 'type', $template)->dropDownList($user_types, ["class" => "form-control selectpicker", "minimumResultsForSearch" => "-1", "placeholder" => "Seçin"])->label(false);
?>

<h4>Ad, Soyad *</h4>
<div class="input-group">
    <label>
        <?= $form->field($model, 'name', $template)->textInput(['class' => 'form-control', 'placeholder' => 'Ad və Soyadınız'])->label(false) ?>
    </label>
</div>
<h4>Mobil telefon *</h4>
<div class="input-group">
    <label>
        <?= $form->field($model, 'phone_number', $template)->textInput(['class' => 'form-control', 'placeholder' => 'Mobil telefon nömrəniz'])->label(false) ?>
    </label>
</div>
<h4>E-mail *</h4>
<div class="input-group">
    <label>
        <?= $form->field($model, 'email', $template)->textInput(['class' => 'form-control', 'placeholder' => 'İşlək e-mail ünvanınızı qeyd edin'])->label(false) ?>
    </label>
</div>
<h4>Doğum günü</h4>
<div class="input-group">
    <label>
        <?=
        \yii\widgets\MaskedInput::widget([
            'model' => $model,
            'name' => 'User[birthday]',
            'clientOptions' => ['alias' =>  'date']
        ]);
        ?>
    </label>
</div>
<h4>Şifrə *</h4>
<div class="input-group">
    <label>
        <?= $form->field($model, 'password', $template)->passwordInput(['class' => 'form-control', 'placeholder' => 'Şifrə'])->label(false) ?>
    </label>
</div>
<h4>Təkrar Şifrə *</h4>
<div class="input-group">
    <label>
        <?= $form->field($model, 'repassword', $template)->passwordInput(['class' => 'form-control', 'placeholder' => 'Şifrə'])->label(false) ?>
    </label>
</div>
<?php
ActiveForm::end();
?>

