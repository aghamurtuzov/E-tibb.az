<?PHP
use \yii\widgets\ActiveForm;


//$hiddenTemplate = ['template' => "{input}"];

$form = ActiveForm::begin([
    'action' => ['ajax/consultation'],
    'enableClientScript' => false,
    'options'=>[
        'id' => "consultation_form_modal"
    ],
    'fieldConfig' => [
        'template' => "<div class=\"form-group custom-group\">{label}{input}{error}{hint}</div>",
        'labelOptions' => ['class' => ''],
        'options' => [
            'class' => 'form-group'
        ],

    ],
]);

$template = ['template' => "{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div>"];
$hiddenTemplate = ['template' => "{input}"]
?>
<div class="row">
    <div class="col-md-6 col-12">
        <div class="form-group custom-group">
            <?= $form->field($consultation, 'name',$template)->textInput(['placeholder'=>'Ad, Soyad'])->label(false) ?>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group custom-group">
            <?= $form->field($consultation, 'email',$template)->textInput(['placeholder'=>'Elektron poçt'])->label(false) ?>
        </div>
    </div>
    <div class="col-md-12 col-12">
        <div class="form-group custom-group">
            <?= $form->field($consultation, 'question',$template)->textarea(['placeholder'=>'Sualınızı daxil edin'])->label(false) ?>
        </div>
    </div>
</div>

<?= $form->field($consultation, 'info',$hiddenTemplate)->hiddenInput() ?>

<?PHP ActiveForm::end(); ?>

