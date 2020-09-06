<?php
 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\AppAsset;
use yii\helpers\ArrayHelper;
use backend\components\Functions;
use backend\models\SitePackagesTypes;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteMenus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="bgWhite">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-0">
                <div class="x_panel">
                    <div class="x_content">    

    <?php 
        $template        = ['template' => "{label}<div class=\"col-lg-6 col-md-4 col-sm-8 col-xs-12\">{input}{hint}{error}</div>"];
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
        if($model->isNewRecord)
            echo $form->field($model, 'type',$template)->dropDownList(SitePackagesTypes::get_Type(),['tabindex'=>'-1', 'class'=>'select2_single form-control','options' => [2 => ['selected' => 'selected']]]);
        else
            echo $form->field($model, 'type',$template)->dropDownList(SitePackagesTypes::get_Type(),['tabindex'=>'-1', 'class'=>'select2_single form-control']);
    ?>
    <?= $form->field($model, 'name',$template)->textInput(['maxlength' => true]) ?>
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

<?PHP $this->registerJsFile("@web/vendor/ckeditor/ckeditor.js",['depends' => [AppAsset::className()]]);  ?>
