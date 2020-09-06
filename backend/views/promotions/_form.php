<?php



use yii\helpers\Html;

use yii\widgets\ActiveForm;

use backend\assets\AppAsset;

use yii\helpers\ArrayHelper;

use backend\components\Functions;

use backend\models\SitePromotions;



/* @var $this yii\web\View */

/* @var $model backend\models\SitePromotions */

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



    <?= $form->field($model, 'headline',$template)->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, 'price',$template)->textInput() ?>



    <?= $form->field($model, 'discount',$template)->label('Endirim %')->textInput()->hint('Faiz işarəsini daxil etməyin!') ?>



    <?php

        if($model->isNewRecord)

            {

                echo $form->field($model, 'date_start', $template)->widget(\kartik\date\DatePicker::classname(), [

                            'options' => ['placeholder' => 'Tarix ...',

                            'value'=>date('Y-m-d')],

                            'pluginOptions' => [

                                'autoclose'=>true,

                                'format' => 'yyyy-mm-dd'

                            ]

                    ]);

            }

            else

            {

                echo $form->field($model, 'date_start', $template)->widget(\kartik\date\DatePicker::classname(), [

                            'options' => ['placeholder' => 'Tarix ...'],

                            'pluginOptions' => [

                                'autoclose'=>true,

                                'format' => 'yyyy-mm-dd'

                            ]

                    ]);

            }

        

    ?>



     <?php

          echo $form->field($model, 'date_end', $template)->widget(\kartik\date\DatePicker::classname(), [

                            'options' => ['placeholder' => 'Tarix ...'],

                            'pluginOptions' => [

                                'autoclose'=>true,

                                'format' => 'yyyy-mm-dd'

                            ]

                        ]);

     ?>



        <?PHP

            if($model->isNewRecord)

            {

                echo $form->field($model, 'photo',$template)->label('Qalereya ( png , jpg )')->fileInput();

            }

            else

            {

        ?>



 

        <?php

                echo $form->field($model, 'photo',$template)->label('Qalereya ( png , jpg )')->fileInput();

                if(!empty($model->photo))

                {

        ?>

        <div class="row galleryBox">

            <?php

                    echo '<div class="col-lg-2 col-md-4 col-sm-4 col-xs-6 item">';

                    echo '  <img src="' . Functions::getUploadUrl() . $customPath . '/small/' . $model->photo . '"alt="image">';

                    echo '    <div data-id="' . $model->id . '" class="btn btn-danger btn-xs deleteImage">Faylı Sil</div>';

                    echo '</div>';

                }

            ?>

        </div>

        <?php

            }

        ?>



    <?PHP

        echo '<input type="hidden" name="SitePromotions[deletedImages]" class="deletedImages">';

   ?>



    <?= $form->field($model, 'organizer',$template)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type',$template)->dropDownList(\backend\models\SitePromotions::get_Type(),['tabindex'=>'-1','onchange'=>'fetch_select(this.value)', 'class'=>'select2_single form-control','prompt'=>'Seç','options' => [$model->type => ['selected' => 'selected']]]) ?>



    <?PHP

        if($model->isNewRecord){

            echo $form->field($model, 'connect_id',$template)->dropDownList([],['tabindex'=>'-1','class'=>'select2_single form-control','id'=>'new_select','prompt'=>'Seç']);

        }

        else
        {
            $type_id = $model->type;
            if($type_id==1){
                echo $form->field($model, 'connect_id',$template)->dropDownList(ArrayHelper::map(\backend\models\SiteDoctors::find()->all(),'id','name'),['tabindex'=>'-1','class'=>'select2_single form-control','id'=>'new_select','options' =>
                    [
                        $model->connect_id => ['selected' => true]
                    ]]);
            }else if($type_id==2){
                echo $form->field($model, 'connect_id',$template)->dropDownList(ArrayHelper::map(\backend\models\SiteEnterprises::find()->all(),'id','name'),['tabindex'=>'-1','class'=>'select2_single form-control','id'=>'new_select','options' =>
                    [
                        $model->connect_id => ['selected' => true]
                    ]]);
            }else{
                echo $form->field($model, 'connect_id',$template)->dropDownList([],['tabindex'=>'-1','class'=>'select2_single form-control','id'=>'new_select','prompt'=>'Seç']);
            }

        }

    ?>

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

//$site_url = 'http://localhost/etibb/admin/';

$scripts = <<< JS

    var l = 5;

JS;





$this->registerJsFile("@web/vendor/ckeditor4/ckeditor.js",['depends' => [AppAsset::className()]]);

$this->registerJsFile("@web/vendor/jquery-ui.min.js",['depends' => [AppAsset::className()]]);





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

