<?php
/**
 * Created by PhpStorm.
 * User: Taleh
 * Date: 1/24/2019
 * Time: 11:22 AM
 */
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use backend\components\Functions;
use common\widgets\Alert;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;
use frontend\models\MainModel;
use frontend\models\SiteCalling;
use \frontend\models\WorkDaysModel;

if(isset($save))
{
    if(!$save)
    {
        $doc_id         = Yii::$app->request->get('id');
        $date_y         = date('Y-m-d');
        $w_model        = new WorkDaysModel();
        if(!empty($doc_id)){
            $closed_times   = SiteCalling::getSuitTimes($doc_id,$date_y);
            $workdays       = $w_model->getUserWorkday($doc_id,$date_y);
            if(!empty($workdays))
                $workdays = explode(',',$workdays['workdays']);
            else
                $workdays = array();
        }else{
            $workdays     = array();
            $closed_times = array();
        }


        $template       = ['template' => "{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div>"];
        $hiddenTemplate = ['template' => "{input}"];

        $form = ActiveForm::begin([
            'action' => ['ajax/save-appoint'],
            'enableClientScript' => false,
            'options'=>[
                'id' => "appointment_form_modal",
                'data-type-id' => $doc_id
            ]
        ]);
?>
    <div class="row">
        <div class="col-md col-12 m-top-10 qebula-yazil">
            <div class="form-group custom-group">
                <?= $form->field($appointment, 'fullname',$template)->textInput(['placeholder'=>'Ad, Soyad'])->label(false) ?>
            </div>
            <div class="form-group custom-group">
                <?= $form->field($appointment, 'telefon',$template)->textInput(['placeholder'=>'Telefon'])->label(false) ?>
            </div>
            <div class="form-group custom-group">
                <?= $form->field($appointment, 'email',$template)->textInput(['placeholder'=>'Elektron poçt'])->label(false) ?>
            </div>
            <div class="form-group custom-group">
                <?php
                    if($appointment->isNewRecord)
                    {
                        $appointment->date = date('m-d-Y');
                        echo $form->field($appointment, 'date',$template)->textInput(['class'=>'datepicker-in1 form-control', 'id'=>'datepicker-in1'])->label(false);
                    }else{
                        echo $form->field($appointment, 'date',$template)->textInput(['class'=>'datepicker-in1 form-control', 'id'=>'datepicker-in1'])->label(false);
                    }
                ?>
            </div>
            <div class="form-group custom-group">
                <select select2 class="w-100 form-control" name="work_time" data-placeholder="Vaxt seçin" id="work_time">
                 <?php
                    if(empty($workdays)){
                        echo '<option disabled value="0">İş günü deyil</option>';
                    }else{
                        foreach($workdays as $val) {
                            if (in_array($val,$closed_times)) {
                                echo '<option disabled value="'.$val.'">'.$val.'</option>';
                            }
                            else {
                                echo '<option value="'.$val.'">'.$val.'</option>';
                            }
                        }
                    }
                 ?>
                </select>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <?= $form->field($appointment, 'doctor_id',$hiddenTemplate)->hiddenInput(['value'=>$doc_id,'class'=>'doc_id'])->label(false) ?>
    <?php ActiveForm::end(); ?>
    <?PHP
}else{ echo "true"; }
};
?>