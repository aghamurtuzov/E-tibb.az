<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteConsultation */
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

                            <?= $form->field($model, 'name')->textInput(['maxlength' => true,'disabled'=> true]) ?>

                            <?= $form->field($model, 'email')->textInput(['maxlength' => true,"readonly" =>"readonly"]) ?>

                            <?php
                            $doctor = \backend\models\SiteDoctors::find()->where(['id' => $model->doctor_id])->one();
                            echo $form->field($model, 'doctor_id')->label('Həkim')->textInput(['value' => $doctor->name,'disabled'=> true]);
                            ?>

                            <?= $form->field($model, 'question')->textarea(['rows' => 6]) ?>


                            <?= $form->field($model, 'answer')->textarea(['rows' => 6]) ?>


                        <?= $form->field($model, 'status',['template' => "{label}<div class=\"col-md-3\">{input}{error}</div>"])->dropDownList(Yii::$app->params['filter.global.status']) ?>

                        <?= $form->field($model, 'a_status',['template' => "{label}<div class=\"col-md-3\">{input}{error}</div>"])->dropDownList(Yii::$app->params['filter.global.status']) ?>

                        <div class="form-group">
                            <?php
                            $buttonName = $model->isNewRecord ? 'Əlavə et': 'Düzəliş et';

                            ?>
                            <?= Html::submitButton($buttonName  , ['class' => 'btn btn-success']) ?>
                        </div>


                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>