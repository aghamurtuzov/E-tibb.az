<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\AppAsset;
use yii\helpers\ArrayHelper;
use backend\models\SiteEnterprises;
use backend\models\SiteSpecialists;
use backend\models\ViewDoctorSpecialist;
use backend\models\SiteSosialLinks;
use backend\models\SitePhoneNumbers;
use backend\models\ViewDoctorWorkplaces;
use backend\models\SiteGallery;
use backend\components\Functions;
use backend\models\SiteMenus;
use backend\models\SiteAddresses;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteEnterprises */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="bgWhite">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-0">
                    <div class="x_panel">
                        <div class="x_content">
                            <?php
                            if($model->isNewRecord)
                            {
                                $linkOptions   = Functions::SosialLinkType($model);
                                $numberOptions = Functions::PhoneNumberType($model);
                            }else{

                                $sosialLinks        = SiteSosialLinks::find()->where(['connect_id'=>$model->id,'type'=>2])->all();
                                $linkOptions        = isset($sosialLinks[0]['link_type']) ? Functions::SosialLinkType($model,$sosialLinks[0]['link_type']) : Functions::SosialLinkType($model);
                                $count_sosialLinks  = !empty($sosialLinks) ? count($sosialLinks)-1 : 0;

                                $phoneNumbers       = SitePhoneNumbers::find()->where(['connect_id'=>$model->id,'type'=>2])->all();
                                $numberOptions      = isset($phoneNumbers[0]['number_type']) ? Functions::PhoneNumberType($model,$phoneNumbers[0]['number_type']) : Functions::PhoneNumberType($model);
                                $count_phoneNumbers = !empty($phoneNumbers) ? count($phoneNumbers)-1 : 0;

                            }

                            $template        = ['template' => "{label}<div class=\"col-lg-5 col-md-4 col-sm-8 col-xs-12\">{input}{hint}{error}</div>"];
                            $template_sosial = ['template' => "{label}<div class=\"col-lg-5 col-md-4 col-sm-8 col-xs-12\"><select class=\"miniSelectBox\" name=\"SiteEnterprises[sosial_links][0][type]\">{$linkOptions}</select><div class=\"input-group\">{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-primary inputAdd\" data-t=\"1\" data-c=\"0\">+</button></span>{hint}{error}</div></div>"];
                            $template_number = ['template' => "{label}<div class=\"col-lg-5 col-md-4 col-sm-8 col-xs-12\"><select class=\"miniSelectBox\" name=\"SiteEnterprises[phone_numbers][0][type]\">{$numberOptions}</select><div class=\"input-group\">{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-primary inputAdd\" data-t=\"2\" data-c=\"0\">+</button></span>{hint}{error}</div></div>"];
                            $template_plus   = ['template' => "{label}<div class=\"col-lg-5 col-md-4 col-sm-8 col-xs-12\"><div class=\"input-group\">{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-primary inputAdd\" data-t=\"3\" data-c=\"0\">+</button></span>{hint}{error}</div></div>"];
                            //$template_plus   = ['template' => "{label}<div class=\"col-lg-5 col-md-4 col-sm-8 col-xs-12\"><div class=\"input-group\">{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-primary inputAdd\" data-t=\"3\" data-c=\"0\">+</button></span>{hint}{error}</div><div class=\"input-group\"><input type=\"text\" id=\"SiteEnterprises-nw-clinic\" class=\"form-control\" placeholder=\"Ünvan\" name=\"SiteEnterprises[new_clinic][0][address]\"><span class=\"input-group-btn\"><div class=\"btn btn-none inputAdd\">+</div></span></div></div>"];

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

                            <?= $form->field($model, 'name',$template)->textInput(['maxlength' => true]) ?>

                            <?= $form->field($model, 'category_id',$template)->dropDownList(ArrayHelper::map(SiteMenus::find()->where(['type'=>2])->orderBy('name')->all(),'id','name')) ?>

                            <div class="form-group row field-siteenterprises-weekdays">
                                <label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" for="siteenterprises-weekdays">İş saatları</label>
                                <div class="col-lg-2 col-md-4 col-sm-8 col-xs-12">
                                    <input type="text" value="<?=$model->weekdays?>" id="siteenterprises-weekdays" class="form-control" name="SiteEnterprises[weekdays]" placeholder="Məs: 09:00 - 18:00" aria-invalid="false">
                                    <div class="hint-block">Həftə içi</div>
                                    <div class="help-block"></div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-sm-8 col-xs-12">
                                    <input type="text" value="<?=$model->saturday?>" id="siteenterprises-saturday" class="form-control" name="SiteEnterprises[saturday]" placeholder="Məs: 09:00 - 18:00" aria-invalid="false">
                                    <div class="hint-block">Şənbə</div>
                                    <div class="help-block"></div>
                                </div>
                                <div class="col-lg-1 col-md-4 col-sm-8 col-xs-12">
                                    <input type="text" value="<?=$model->sunday?>" id="siteenterprises-sunday" class="form-control" name="SiteEnterprises[sunday]" placeholder="Məs: 09:00 - 18:00" aria-invalid="false">
                                    <div class="hint-block">Bazar</div>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <?PHP
                            if($model->isNewRecord)
                            {
                                echo $form->field($model, 'sosial_links[0][link]',$template_sosial)->textInput();
                            }else{
                                ?>
                                <div class="form-group row field-SiteEnterprises-sosial_links-0-link">
                                    <label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" for="SiteEnterprises-sosial_links-0-link">Sosial linklər</label>
                                    <div class="col-lg-5 col-md-4 col-sm-8 col-xs-12">
                                        <select class="miniSelectBox" name="SiteEnterprises[sosial_links][0][type]"><?=$linkOptions?></select>
                                        <div class="input-group">
                                            <input type="text" value="<?= isset($sosialLinks[0]['link']) ? $sosialLinks[0]['link']: null; ?>" id="SiteEnterprises-sosial_links-0-link" class="form-control" name="SiteEnterprises[sosial_links][0][link]">
                                            <span class="input-group-btn"><button type="button" class="btn btn-primary inputAdd" data-t="1" data-c="<?=$count_sosialLinks?>">+</button></span>
                                            <div class="help-block"></div>
                                        </div>
                                        <?PHP
                                        $AddedSosialLinks = [];
                                        if(!empty($sosialLinks))
                                        {
                                            if(isset($sosialLinks[0]))
                                            {
                                                $AddedSosialLinks[0]['type'] = $sosialLinks[0]['link_type'];
                                                $AddedSosialLinks[0]['link'] = $sosialLinks[0]['link'];
                                                $AddedSosialLinks[0]['id']   = $sosialLinks[0]['id'];
                                                unset($sosialLinks[0]);
                                            }
                                            foreach($sosialLinks as $key => $val)
                                            {
                                                echo '<select class="miniSelectBox" name="SiteEnterprises[sosial_links]['.$key.'][type]">';
                                                echo Functions::SosialLinkType($model,$val['link_type']);
                                                echo '</select>';

                                                echo '<div class="input-group">';
                                                echo "<input type=\"text\" id=\"SiteEnterprises-sosial_links\" class=\"form-control\" value=\"{$val['link']}\" name=\"SiteEnterprises[sosial_links][$key][link]\">";
                                                echo '<span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span>';
                                                echo '</div>';

                                                $AddedSosialLinks[$key]['type'] = $val['link_type'];
                                                $AddedSosialLinks[$key]['link'] = $val['link'];
                                                $AddedSosialLinks[$key]['id']   = $val['id'];
                                            }
                                            $AddedSosialLinks = base64_encode(json_encode($AddedSosialLinks));
                                            echo '<input name="SiteEnterprises[added_sosial_links]" type="hidden" value="'.$AddedSosialLinks.'">';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?PHP
                            }
                            ?>

                            <?PHP
                            if($model->isNewRecord)
                            {
                                echo $form->field($model, 'phone_numbers[0][number]',$template_number)->textInput();
                            }else{
                                ?>
                                <div class="form-group row field-SiteEnterprises-phone_numbers-0-number">
                                    <label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" for="SiteEnterprises-phone_numbers-0-number">Telefon nömrələri</label>
                                    <div class="col-lg-5 col-md-4 col-sm-8 col-xs-12">
                                        <select class="miniSelectBox" name="SiteEnterprises[phone_numbers][0][type]"><?=$numberOptions?></select>
                                        <div class="input-group">
                                            <input type="text" value="<?= isset($phoneNumbers[0]['number']) ? $phoneNumbers[0]['number']: null; ?>" id="SiteEnterprises-phone_numbers-0-number" class="form-control" name="SiteEnterprises[phone_numbers][0][number]">
                                            <span class="input-group-btn"><button type="button" class="btn btn-primary inputAdd" data-t="2" data-c="<?=$count_phoneNumbers?>">+</button></span>
                                            <div class="help-block"></div>
                                        </div>
                                        <?PHP
                                        if(!empty($phoneNumbers))
                                        {
                                            $AddedPhoneNumbers = [];
                                            if(isset($phoneNumbers[0]))
                                            {
                                                $AddedPhoneNumbers[0]['type']   = $phoneNumbers[0]['number_type'];
                                                $AddedPhoneNumbers[0]['number'] = $phoneNumbers[0]['number'];
                                                $AddedPhoneNumbers[0]['id']     = $phoneNumbers[0]['id'];
                                                unset($phoneNumbers[0]);
                                            }
                                            foreach($phoneNumbers as $key => $val)
                                            {
                                                echo '<select class="miniSelectBox" name="SiteEnterprises[phone_numbers]['.$key.'][type]">';
                                                echo Functions::PhoneNumberType($model,$val['number_type']);
                                                echo '</select>';

                                                echo '<div class="input-group">';
                                                echo "<input type=\"text\" id=\"SiteEnterprises-phone_numbers\" class=\"form-control\" value=\"{$val['number']}\" name=\"SiteEnterprises[phone_numbers][$key][number]\">";
                                                echo '<span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span>';
                                                echo '</div>';

                                                $AddedPhoneNumbers[$key]['type']   = $val['number_type'];
                                                $AddedPhoneNumbers[$key]['number'] = $val['number'];
                                                $AddedPhoneNumbers[$key]['id']     = $val['id'];

                                            }
                                            $AddedPhoneNumbers = base64_encode(json_encode($AddedPhoneNumbers));
                                            echo '<input name="SiteEnterprises[added_phone_numbers]" type="hidden" value="'.$AddedPhoneNumbers.'">';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?PHP
                            }
                            ?>

                            <?PHP
                            if($model->isNewRecord)
                            {
                                echo $form->field($model, 'addresses[0][name]',$template_plus)->textInput();
                            }else{
                                $addresses = SiteAddresses::find()->where(['connect_id'=>$model->id,'type'=>2])->all();
                                $count_addresses = !empty($addresses) ? count($addresses)-1 : 0;
                                ?>
                                <div class="form-group row field-siteenterprises-addresses-0-name">
                                    <label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12" for="siteenterprises-addresses-0-name">Ünvanlar</label>
                                    <div class="col-lg-5 col-md-4 col-sm-8 col-xs-12">
                                        <div class="input-group">
                                            <input type="text" value="<?= isset($addresses[0]) ? $addresses[0]['address'] : null; ?>" id="siteenterprises-addresses-0-name" class="form-control" name="SiteEnterprises[addresses][0][name]">
                                            <span class="input-group-btn"><button type="button" class="btn btn-primary inputAdd" data-t="3" data-c="<?=$count_addresses?>">+</button></span>
                                            <div class="help-block"></div>
                                        </div>
                                        <?PHP
                                        if(!empty($addresses))
                                        {
                                            $AddedAddresses = [];
                                            if(isset($addresses[0]))
                                            {
                                                $AddedAddresses[0]['id']   = $addresses[0]['id'];
                                                $AddedAddresses[0]['name'] = $addresses[0]['address'];
                                                unset($addresses[0]);
                                            }
                                            foreach($addresses as $key => $val)
                                            {
                                                echo '<div class="input-group">';
                                                echo "<input type=\"text\" id=\"SiteEnterprises-new_clinic\" value=\"{$val['address']}\" class=\"form-control\" name=\"SiteEnterprises[addresses][$key][name]\">";
                                                echo '<span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span>';
                                                echo '</div>';
                                                $AddedAddresses[$key]['id']   = $val['id'];
                                                $AddedAddresses[$key]['name'] = $val['address'];
                                            }
                                            $AddedAddresses = base64_encode(json_encode($AddedAddresses));
                                            echo '<input name="SiteEnterprises[added_addresses]" type="hidden" value="'.$AddedAddresses.'">';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?PHP
                            }
                            ?>

                            <?= $form->field($model, 'files[]',$template)->fileInput(['multiple' => true]) ?>

                            <?PHP
                            $gallery = SiteGallery::find()->where(['connect_id'=>$model->id,'type'=>2])->all();

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
                                echo '<input type="hidden" name="SiteEnterprises[mainImage]" value="'.$mainIimage.'">';
                                echo '<input type="hidden" name="SiteEnterprises[deletedImages]" class="deletedImages">';
                            }
                            ?>

                            <?= $form->field($model, 'about')->textarea(['class'=>'ckeditor']) ?>

                            <?= $form->field($model, 'services_prices')->textarea(['class'=>'ckeditor']) ?>

                            <?PHP
                            $feature = json_decode($model->feature,true);
                            if(isset($feature['catdirilma'])){ $model->catdirilma = 1; }
                            if(isset($feature['saat24'])){ $model->saat24 = 1; }
                            if(isset($feature['eve_caqiris'])){ $model->eve_caqiris = 1; }
                            ?>

                            <?= $form->field($model, 'catdirilma',$template)->checkbox() ?>

                            <?= $form->field($model, 'saat24',$template)->checkbox() ?>

                            <?= $form->field($model, 'eve_caqiris',$template)->checkbox() ?>

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

//$linkOptions = '';
//$numberOptions = '';

$scripts = <<< JS
    var l = 5;

    $('.inputAdd').click(function(){
        var c = $(this).attr('data-c') ? $(this).attr('data-c') : 0;
        var t = $(this).attr('data-t') ? $(this).attr('data-t') : 1;
        //console.log(t);
        if(c<l){
            var step = Number(c)+1;
            if(t == 1)
            {
                $(this).parents('.col-md-4').append('<select class="miniSelectBox" name="SiteEnterprises[sosial_links]['+step+'][type]">{$linkOptions}</select><div class="input-group"><input type="text" id="SiteEnterprises-sosial_links" class="form-control" name="SiteEnterprises[sosial_links]['+step+'][link]"><span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span></div>');
            }else if(t == 2){
                $(this).parents('.col-md-4').append('<select class="miniSelectBox" name="SiteEnterprises[phone_numbers]['+step+'][type]">{$numberOptions}</select><div class="input-group"><input type="text" id="SiteEnterprises-phone_numbers" class="form-control" name="SiteEnterprises[phone_numbers]['+step+'][number]"><span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span></div');
            }else if(t == 3){
                $(this).parents('.col-md-4').append('<div class="input-group"><input type="text" id="SiteEnterprises-new_clinic" class="form-control" name="SiteEnterprises[addresses]['+step+'][name]"><span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span></div></div>');
            }else{
                $(this).parents('.col-md-4').append('<div class="input-group"><input type="text" placeholder="Ad" id="SiteEnterprises-new_clinic" class="form-control" name="SiteEnterprises[new_clinic]['+step+'][name]"><span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span></div><div class="input-group"><input type="text" placeholder="Ünvan" id="SiteEnterprises-new_clinic" class="form-control" name="SiteEnterprises[new_clinic]['+step+'][address]"><span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span></div>');                
            }
            c++;
            $(this).attr('data-c',c);
        }
    });
JS;


$this->registerJsFile("@web/vendor/ckeditor4/ckeditor.js",['depends' => [AppAsset::className()]]);

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