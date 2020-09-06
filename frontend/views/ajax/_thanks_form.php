<?php
use \yii\widgets\ActiveForm;
use \yii\helpers\Html;
?>
<?php
if($save==false) {

    $comment = $model;

    $template = ['template' => "{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div>"];
    $hiddenTemplate = ['template' => "{input}"];

    $form = ActiveForm::begin([
        'action' => ['ajax/thanks'],
        'enableClientScript' => false,
        'options'=>[
            'id' => "thanks_form_modal",
            'data-type-id' => $comment->type
        ]
    ]);
    ?>

    <div class="row">
        <div class="col-md col-12">
            <div class="form-group custom-group">
                <?= $form->field($comment, 'name',$template)->textInput(['placeholder'=>'Ad, Soyad']) ?>
            </div>
        </div>
        <div class="col-md col-12">
            <div class="form-group custom-group">
                <?= $form->field($comment, 'email',$template)->textInput(['placeholder'=>'Elektron poçt']) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md col-12">
            <div class="form-group custom-group">
                <?= $form->field($comment, 'comment',$template)->textarea(['placeholder'=>'Mətni daxil edin']) ?>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <?= $form->field($comment, 'connect_id',$hiddenTemplate)->hiddenInput() ?>
    <?= $form->field($comment, 'type',$hiddenTemplate)->hiddenInput() ?>

    <?php ActiveForm::end();

}else{
    echo "true";
}

?>