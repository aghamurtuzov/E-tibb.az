<?PHP
use \yii\widgets\ActiveForm;
use yii\helpers\Html;

$template = ['template' => "{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div>"];
$hiddenTemplate = ['template' => "{input}"];

$form = ActiveForm::begin([
    'action' => ['news/add-comment'],
    'options'=>[
        'id' => "consultation_form_modal",
    ]
]);

?>
<div class="row" style="margin-top: 5%">
    <div class="col-md-12">
        <?php
        if (Yii::$app->session->hasFlash("success")) {
            echo '<br /><div class="alert alert-success">' . Yii::$app->session->getFlash("success") . '</div>';
        }
        ?>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group custom-group">
            <?= $form->field($form_model, 'name',$template)->textInput(['placeholder'=>'Ad, Soyad', 'required']) ?>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group custom-group">
            <?= $form->field($form_model, 'email',$template)->textInput(['placeholder'=>'Elektron poçt']) ?>
        </div>
    </div>
    <div class="col-md-12 col-12">
        <div class="form-group custom-group">
            <?= $form->field($form_model, 'comment',$template)->textarea(['placeholder'=>' Rəy Bildir']) ?>
        </div>
    </div>
</div>

<?= $form->field($form_model, 'connect_id',$hiddenTemplate)->hiddenInput(['value'=> $news_id]) ?>
<?= Html::submitButton("Göndər", ['class' => 'cb cyan w-m-100 d-inline-block text-center']); ?>

<?PHP ActiveForm::end(); ?>



