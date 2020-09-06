<?phpuse yii\helpers\Html;use yii\widgets\ActiveForm;use backend\assets\AppAsset;use yii\helpers\ArrayHelper;use backend\models\SiteEnterprises;use backend\models\SiteDoctors;use backend\components\Functions;/* @var $this yii\web\View *//* @var $model backend\models\SiteComments *//* @var $form yii\widgets\ActiveForm */?><div class="row">    <div class="col-md-12 col-sm-12 col-xs-12">        <div class="bgWhite">            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-0">                <div class="x_panel">                    <div class="x_content">                        <?php                        $template = ['template' => "{label}<div class=\"col-lg-6 col-md-12 col-sm-12 col-sm-12\">{input}{hint}{error}</div>"];                        $form = ActiveForm::begin([                            'options'=>[                                'enctype' => 'multipart/form-data',                            ],                            'fieldConfig' => [                                'template' => "{label}<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">{input}{hint}{error}</div>",                                'labelOptions' => [ 'class' => 'control-label col-lg-12 col-md-12 col-sm-12 col-xs-12' ],                                'options'=>[                                    'class'=>'form-group row'                                ],                            ],                        ]);                        ?>                        <?= $form->field($model, 'name',$template)->textInput(['maxlength' => 60, 'disabled'=> true]) ?>                        <?= $form->field($model, 'email',$template)->textInput(['maxlength' => 60, 'disabled'=> true]) ?>                        <?PHP                        if($model->type == 1)                        {                            $doctor = SiteDoctors::find()->where(['id' => $model->connect_id])->one();                            echo $form->field($model, 'connect_id',$template)->label('Həkim')->textInput(['value' => $doctor->name,'disabled'=> true]);                        }else if($model->type == 2){                            $enterprises = SiteEnterprises::find()->where(['id' => $model->connect_id])->one();                            echo $form->field($model, 'connect_id',$template)->label('Obyekt')->textInput(['value' => $enterprises->name,'disabled'=> true]);                        }else if($model->type == 3){                            $news = \backend\models\SiteNews::find()->where(['id' => $model->connect_id])->one();                            echo $form->field($model, 'connect_id',$template)->label('Xəbər')->textInput(['value' => $news->headline,'disabled'=> true]);                        }                        ?>                        <?= $form->field($model, 'positive',['template' => "{label}<div class=\"col-md-3\">{input}{error}</div>"])->dropDownList(Yii::$app->params['filter.global.positive']) ?>                        <?= $form->field($model, 'comment')->textarea(['class'=>'ckeditor']) ?>                        <div class="form-group">                            <?PHP                            $buttonName = $model->isNewRecord ? 'Əlavə et': 'Təsdiqlə';                            echo Html::submitButton($buttonName, ['class' => 'btn btn-success']);                            echo Html::a('Sıfırla',[Yii::$app->controller->action->id,'id'=>$model->id], ['class' => 'btn btn-primary']);                            ?>                        </div>                        <?php ActiveForm::end(); ?>                    </div>                </div>            </div>        </div>    </div></div><?PHP$this->registerJsFile("@web/vendor/ckeditor4/ckeditor.js",['depends' => [AppAsset::className()]]);?>