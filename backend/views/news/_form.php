<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\AppAsset;
use yii\helpers\ArrayHelper;
use backend\components\Functions;
use backend\models\SiteMenus;
use backend\models\SiteNews;
use backend\models\SiteGallery;

/* @var $this yii\web\View */
/* @var $model app\models\SiteNews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="bgWhite">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-0">
                <div class="x_panel">
                    <div class="x_content">

    <?php
        $template  = ['template' => "{label}<div class=\"col-lg-6 col-md-4 col-sm-8 col-xs-12\">{input}{hint}{error}</div>"];
        $form = ActiveForm::begin([
                    'options'=>[
                        'enctype' => 'multipart/form-data',
                    ],
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">{input}{hint}{error}</div>",
                        'labelOptions' => [ 'class' => 'control-label col-lg-12 col-md-12 col-sm-12 col-xs-12' ],
                        'options'=>[
                            'class'=>'form-group row'
                ],
            ],
        ]);
    ?>
    <?PHP
        echo $form->field($model, 'category_id',$template)->dropDownList(ArrayHelper::map(SiteMenus::find()->where(['type'=>3])->all(),'id','name'),['tabindex'=>'-1', 'class'=>'select2_single form-control']);
    ?>

    <?= $form->field($model, 'files[]',$template)->label('Qalereya ( png , jpg )')->fileInput(['multiple' => true])?>
    <?PHP
        $gallery = SiteGallery::find()->where(['connect_id'=>$model->id,'type'=>3])->all();

        if(!empty($gallery))
            {
                $mainIimage = null;
                echo '<div class="row galleryBox">';
                foreach($gallery as $key => $val)
                {
                    if($val['main'] == 1)
                    {
                    $mainClass  = 'main';
                    $mainIimage = $val['photo'];
                    }else{
                    $mainClass = null;
                    }
    ?>
        <div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 item <?=$mainClass?>">
            <img src="<?=Functions::getUploadUrl().$customPath.'/small/'.$val['photo']?>" alt="image">
            <div data-id="<?=$val['id']?>" class="btn btn-danger btn-xs deleteImage">Faylı Sil</div>
        </div>
    <?PHP
            }
        echo '</div>';
        echo '<input type="hidden" name="SiteNews[mainImage]" value="'.$mainIimage.'">';
        echo '<input type="hidden" name="SiteNews[deletedImages]" class="deletedImages">';
        }
   ?>
    <?= $form->field($model, 'is_video',$template)->checkbox(array('label'=>'Video xəbər')); ?>
    <?= $form->field($model, 'headline', $template)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords', $template)->textInput(['maxlength' => true]) ?>

                        <?PHP
                        if(!$model->isNewRecord) {
                            $dates = explode(" ", $model->datetime);
                            $date  = $dates[0];
                            $hour  = $dates[1];
                            echo $form->field($model, 'datetime', $template)->textInput(['type' => 'date','value' => $date]);
                            echo '<div class="form-group row field-sitenews-date-hours">';
                            echo    '<label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" for="sitenews-hours">Saat</label>';
                            echo    '<div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">';
                            echo     '<input type="text" class="form-control" name="hour" value="'.$hour.'">';
                            echo    '</div>';
                            echo '</div>';

                        }
                        else {
                            echo $form->field($model, 'datetime', $template)->textInput(['type' => 'date', 'value' => date('Y-m-d')]);
                            ?>
                            <div class="form-group row field-sitenews-date-hours">
                                <label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" for="sitenews-hours">Saat</label>
                                <div class="col-lg-6 col-md-4 col-sm-8 col-xs-12">
                                    <input type="text" class="form-control" name="hour" value="<?=date('H:i:s')?>">
                                </div>
                            </div>

                        <?php } ?>
    <?= $form->field($model, 'content')->textarea(['class'=>'ckeditor']) ?>
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

$scripts = <<< JS
    var l = 5;
JS;

$this->registerJsFile("@web/vendor/ckeditor4/ckeditor.js?v=657",['depends' => [AppAsset::className()]]);

$this->registerJs("

    $(document).on(\"click\", \".deleteImage\", function (e) {
        var result = confirm(\"Faylı silmək istədiyinizdən əminsinizmi?\");
        if(result)
        {

            getPhoto = $(this).attr('data-id');
            
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
    
    $scripts
    
");

?>

