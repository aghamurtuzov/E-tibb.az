<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminUsers */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="bgWhite">
            <div class="col-md-5 col-sm-12 col-xs-12 p-0">
                <div class="x_panel">
                    <div class="x_content">

                        <?php
                        $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'template' => "{label}<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">{input}{error}</div>",
                                'labelOptions' => [ 'class' => 'control-label col-lg-12 col-md-12 col-sm-12 col-xs-12' ],
                                'options'=>[
                                    'class'=>'form-group row',
                                ],
                            ],
                        ]);
                        ?>

                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                        <?PHP
                        if(!$model->isNewRecord)
                        {
                            echo $form->field($model, 'password', ['enableClientValidation' => false])->passwordInput(['value'=>'','maxlength' => true]);
                        }else{
                            echo $form->field($model, 'password')->passwordInput(['maxlength' => true]);
                        }
                        ?>

                        <?= $form->field($model, 'status',['template' => "{label}<div class=\"col-md-3\">{input}{error}</div>"])->dropDownList(Yii::$app->params['filter.global.status']) ?>

                        <div class="ln_solid"></div>

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