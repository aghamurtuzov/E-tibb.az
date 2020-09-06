<?php
 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\AppAsset;
use yii\helpers\ArrayHelper;
use backend\components\Functions;
use backend\models\SiteMenus;

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
        echo $form->field($model, 'parent',$template)->dropDownList(ArrayHelper::map(SiteMenus::find()->where(['parent'=>NULL])->all(),'id','name'),['prompt'=>'Seç','tabindex'=>'-1', 'class'=>'select2_single form-control']);
    ?>
    <?PHP
        echo $form->field($model, 'position',$template)->dropDownList(SiteMenus::get_Position(),['tabindex'=>'-1', 'class'=>'select2_single form-control']);
    ?>

    <?PHP
        if($model->isNewRecord)
            echo $form->field($model, 'type',$template)->dropDownList(SiteMenus::get_Type(),['tabindex'=>'-1', 'class'=>'select2_single form-control','options' => [2 => ['selected' => 'selected']]]);
        else
            echo $form->field($model, 'type',$template)->dropDownList(SiteMenus::get_Type(),['tabindex'=>'-1', 'class'=>'select2_single form-control']);
    ?>
                        
    <?PHP
        if($model->isNewRecord)
        {
            echo $form->field($model, 'menu_order',$template)->textInput(['type'=>'number','value'=>SiteMenus::get_Last_Order(),'min'=>"1", 'max'=>"100"]);
        }
        else
        {
           echo $form->field($model, 'menu_order',$template)->textInput(['type'=>'number','min'=>"1", 'max'=>"100"]); 
        }
        
    ?>
    <?= $form->field($model, 'target',$template)->checkbox(['label'=>'Növbəti Səhifə Keçid']); ?>

    <?= $form->field($model, 'hidden',$template)->checkbox(array('label'=>'Görünməz')); ?>

    <?PHP
        echo $form->field($model, 'status',$template)->dropDownList(SiteMenus::get_Status(),['tabindex'=>'-1', 'class'=>'select2_single form-control','options' => [1 => ['selected' => 'selected']]]);
    ?>

    <?= $form->field($model, 'name',$template)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link',$template)->label('Link')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'keywords',$template)->textInput(['maxlength' => true]) ?>

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

<?PHP $this->registerJsFile("@web/vendor/ckeditor4/ckeditor.js",['depends' => [AppAsset::className()]]);  ?>
