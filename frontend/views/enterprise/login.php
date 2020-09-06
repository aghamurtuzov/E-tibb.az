<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<?PHP $form = ActiveForm::begin(['id'=>'login-form','enableAjaxValidation' => true]) ?>
<?php
var_dump($model->errors);
?>

<?= Html::tag('h1',Html::encode($this->title)) ?>

<?= $form->field($model,'username')->textInput(['placeholder' => 'İstifadəçi adı','autofocus'=>true])->label(false) ?>

<?= $form->field($model,'password')->passwordInput(['placeholder' => 'Şifrə'])->label(false) ?>

<?= $form->field($model, 'rememberMe')->checkbox(['label'=>'Məni xatırla','value'=>true]) ?>

<?= Html::submitButton('Daxil ol',['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>