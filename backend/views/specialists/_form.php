<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\components\Functions;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteSpecialists */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="bgWhite">
            <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 p-0">
                <div class="x_panel">
                    <div class="x_content">

                        <?php
                        $form = ActiveForm::begin([
                            'options'=>[
                                'enctype' => 'multipart/form-data',
                            ],
                            'fieldConfig' => [
                                'template' => "{label}<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">{input}{error}</div>",
                                'labelOptions' => [ 'class' => 'control-label col-lg-12 col-md-12 col-sm-12 col-xs-12' ],
                                'options'=>[
                                    'class'=>'form-group row'
                                ],
                            ],
                        ]);
                        ?>

                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                        <?PHP
                        if($model->isNewRecord)
                        {
                            echo $form->field($model,'icon')->fileInput();
                        }else{

                            if(!empty($model->icon))
                            {
                        ?>
                                <div class="row galleryBox">
                                    <div class="col-lg-5 col-md-4 col-sm-8 col-xs-12 item">
                                        <img src="<?=Functions::getUploadUrl().$customPath.'/'.$model->icon?>" alt="image">
                                        <div data-id="<?=$model->id?>" class="btn btn-danger btn-xs deleteImage">Faylı Sil</div>
                                    </div>
                                </div>

                                <?PHP echo $form->field($model,'icon',['template' => "<div class=\"imageUpload hidden\">{label}<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">{input}{error}</div></div>"])->fileInput(); ?>

                                <input type="hidden" name="deletedImages" class="deletedImages">
                        <?PHP
                            }else{
                                echo $form->field($model,'icon')->fileInput();
                            }

                        }
                        ?>

                        <div class="form-group">
                            <?PHP
                            $buttonName = $model->isNewRecord ? 'Əlavə et': 'Düzəliş et';
                            echo Html::submitButton($buttonName, ['class' => 'btn btn-success']);
                            echo Html::a('Sıfırla',[Yii::$app->controller->action->id,'id'=>$model->id], ['class' => 'btn btn-primary']);
                            ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?PHP
$this->registerJs("
    $(document).on(\"click\", \".deleteImage\", function (e) {
        var result = confirm(\"Faylı silmək istədiyinizdən əminsinizmi?\");
        if(result)
        {

            getPhoto = $(this).attr('data-id');
            
            console.log(getPhoto);
            
            if(getPhoto!='')
            {
                getDeletedImages = $('.deletedImages').val();
                deletedImages = getDeletedImages != '' ? getDeletedImages+','+getPhoto : getPhoto;
                $('.deletedImages').val(deletedImages);
                $('.imageUpload').removeClass('hidden');
                $(this).parent('.item').remove();
            }

        }

        e.preventDefault();
        
    });
");
?>