<?php
use yii\widgets\ActiveForm;
use backend\models\SiteMenus;
use yii\helpers\ArrayHelper;

use backend\models\SiteSosialLinks;
use backend\models\SitePhoneNumbers;
use \backend\components\Functions;
use \backend\models\SiteAddresses;

$form = ActiveForm::begin([
    'options'=>[
        'enctype' => 'multipart/form-data',
        "class" => "doc-log"
    ],
    'fieldConfig' => [
        'template' => "<div class=\"form-group custom-group\">{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div></div>",
        'labelOptions' => [ 'class' => '' ],
        'options'=>[
            'class'=>'form-group'
        ],
    ],
]);
$template        = ['template' => "{input}{hint}<div class=\"heldata-countp-block help-block-error\">{error}</div>"];

$sosialLinks        = SiteSosialLinks::find()->where(['connect_id'=>$model->id,'type'=>2])->all();
$linkOptions        = isset($sosialLinks[0]['link_type']) ? Functions::SosialLinkType($model,$sosialLinks[0]['link_type']) : Functions::SosialLinkType($model);
$count_sosialLinks  = !empty($sosialLinks) ? count($sosialLinks)-1 : 0;






?>
    <div class="row">
        <div class="col-12">
            <div class="t-card mini3 -h-top">
                <div class="row">
                    <div class="col col-md-6 text-left d-none d-md-block">
                        <h2 class="breadcrumb-title">Tənzimləmələr</h2>
                    </div>
                    <div class="col col-md-6 text-left text-md-right">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb" style="padding:15px 0 8px;">
                                <li class="breadcrumb-item"><a href="/etibb.az">Ana səhifə</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Klinikalar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <?php
                if(Yii::$app->session->hasFlash("success")){
                    echo '<br /><div class="alert alert-success">'.Yii::$app->session->getFlash("success").'</div>';
                }
            ?>
            <div class="t-card mini3 -h-top nov search-box">
                <h5>Növü</h5>
                <div class="form-group">
                    <select class="selectpicker form-control custom-control" title="Növ seçin">
                        <?php
                        if (!empty($enterprise_categories)) {
                            foreach ($enterprise_categories as $category) {
                                $selected = '';
                                if ($model->category_id == $category["id"]) {
                                    $selected = 'selected';
                                }
                                echo '<option value="obyekt-qeydiyyat/' . $category["id"] . '" ' . $selected . '>' . $category["name"] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="t-card mini3 -h-top block">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="image-upload">
                            <?php
                            if(!empty($model->photo)){
                                //$enterprise['photo'] ? Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/small/'.$enterprise['photo'] : Yii::$app->params['site.defaultThumb1'];
                                $src = Functions::getUploadUrl().$customPath.'/small/'.$model->photo;

                            }else{
                                $src = Yii::$app->params['site.defaultThumb1'];
                            }
                            ?>
                            <img class="profile-image" src="<?= $src?>" alt="your image">
                            <div class="p-image">

                                <img src="assets/img/icon/photo.png" alt="photo" class="upload-button">
<!--                                <input type='file' class="file-upload">
-->                                <?= $form->field($model, 'files[]')->fileInput(['multiple' => false,'class'=> "file-upload"])->label(' ') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?= $form->field($model, 'name')->textInput(['maxlength' => true,"class" => "form-control","placeholder" => "Ad"]) ?>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group custom-group">
                                    <?= $form->field($model, 'email')->textInput(['maxlength' => true,"class" => "form-control","placeholder" => "Email","readonly" => "readonly"]) ?>

                                </div>
                            </div>
                            <div class="col-12">
                                <p class="ish">İş günləri və saatları</p>
                                <div class="row">

                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group custom-group">
                                            <label>B.e - Cümə</label>
                                            <input type="text" value="<?=$model->weekdays?>" id="siteenterprises-weekdays" class="form-control time-range-input" name="SiteEnterprises[weekdays]" placeholder="09:00 - 18:00" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group custom-group">
                                            <label>Şənbə</label>
                                            <input type="text" value="<?=$model->saturday?>" id="siteenterprises-saturday" class="form-control time-range-input" name="SiteEnterprises[saturday]" placeholder="09:00 - 18:00" aria-invalid="false">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="form-group custom-group">
                                            <label>Bazar</label>
                                            <input type="text" value="<?=$model->sunday?>" id="siteenterprises-sunday" class="form-control time-range-input" name="SiteEnterprises[sunday]" placeholder="09:00 - 18:00" aria-invalid="false">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="t-card mini3 -h-top datetime">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <?= $form->field($model, 'contact_name')->textInput(['maxlength' => true,"class" => "form-control","placeholder" => "Ad Soyad"])->label("Əlaqələndirici şəxs") ?>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => true,"class" => "form-control number-mask","placeholder" => "Nömrə"])->label("Əlaqələndirici şəxsin nömrəsi") ?>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group date custom-group" data-date-format="dd-mm-yyyy" data-provide="datepicker">
                            <?= $form->field($model, 'birthday')->textInput(["class" => "form-control datetime-mask","placeholder"=>"Doğum tarixi"])?>
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                            <style>
                                .datepicker.dropdown-menu {
                                    margin: 80px 0;
                                }
                            </style>
                        </div>
                    </div>
                </div>
            </div>
            <div class="t-card mini3 -h-top append-block general single">
                <?php
                $phoneNumbers       = SitePhoneNumbers::find()->where(['connect_id'=>$model->id,'type'=>2])->all();
                $count_phoneNumbers = !empty($phoneNumbers) ? count($phoneNumbers)-1 : 0;

                    $phone_numbers[0] = [];
                    $phone_numbers[1] = [];
                if(!empty($phoneNumbers))
                {

                    foreach($phoneNumbers as $key => $val)
                    {
                        $phone_numbers[$val['number_type']][]  = $phoneNumbers[$key];
                    }
                }
                    $count_phoneNumbers = count($phone_numbers[0])>=1?(count($phone_numbers[0])-1):0;
                    $count_mobileNumbers = count($phone_numbers[1])>=1?(count($phone_numbers[1])-1):0;
                ?>
                <div class="row pad">
                    <div class="col-10 col-md-11">
                        <div class="form-group custom-group">
                            <label>Şirkət telefonu</label>
                            <input class="form-control" type="text" name="SiteEnterprises[phone_numbers][]" value="<?= isset($phone_numbers[0][0]['number']) ? $phone_numbers[0][0]['number']: null; ?>" placeholder="Telefon">
                        </div>
                    </div>
                    <div class="col-2 col-md-1">
                        <button type="button" data-val="Şirkət telefonu" data-input="SiteEnterprises[phone_numbers][]"
                                data-cName=".append-block.single" class="cb cyan d-inline-block add first" data-count="<?= $count_phoneNumbers?>">+
                        </button>
                    </div>
                </div>
                    <?PHP
                    if(!empty($phone_numbers[0]))
                    {
                        $AddedPhoneNumbers = [];
                        if(isset($phone_numbers[0][0]))
                        {
                            $AddedPhoneNumbers[0]['type']   = $phone_numbers[0][0]['number_type'];
                            $AddedPhoneNumbers[0]['number'] = $phone_numbers[0][0]['number'];
                            $AddedPhoneNumbers[0]['id']     = $phone_numbers[0][0]['id'];
                            unset($phone_numbers[0][0]);
                        }

                        foreach($phone_numbers[0] as $key => $val)
                        {
                            echo '<div class="row border-top"> 
                                    <div class="col-10 col-md-11">
                                        <div class="form-group custom-group">
                                            <label>Şirkət nömrəsi</label>
                                            <input class="form-control" type="text" value="'.$val['number'].'"  name="SiteEnterprises[phone_numbers][]" placeholder="Şirkət nömrəsi">
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-1">
                                        <button type="button" class="cb cyan d-inline-block remove">-</button>
                                    </div>
                                </div>';


                            $AddedPhoneNumbers[$key]['type']   = $val['number_type'];
                            $AddedPhoneNumbers[$key]['number'] = $val['number'];
                            $AddedPhoneNumbers[$key]['id']     = $val['id'];

                        }
                        $AddedPhoneNumbers = base64_encode(json_encode($AddedPhoneNumbers));
                        echo '<input name="SiteEnterprises[added_phone_numbers]" type="hidden" value="'.$AddedPhoneNumbers.'">';


                    }
                    ?>


            </div>
            <div class="t-card mini3 -h-top append-block general sec">
                <div class="row pad">
                    <div class="col-10 col-md-11">
                        <div class="form-group custom-group">
                            <label>Şirkət Mobil telefonu</label>
                            <input class="form-control" type="text" name="SiteEnterprises[mobile_numbers][]" value="<?= isset($phone_numbers[1][0]['number']) ? $phone_numbers[1][0]['number']: null; ?>" placeholder="Telefon">
                        </div>
                    </div>
                    <div class="col-2 col-md-1">
                        <button type="button" data-val="Şirkət Mobil telefonu" data-cName=".append-block.sec"
                                data-input="SiteEnterprises[mobile_numbers][]" class="cb cyan d-inline-block add sec"  data-count="<?= $count_mobileNumbers?>">+
                        </button>
                    </div>
                </div>
                <?PHP
                if(!empty($phone_numbers[1]))
                {
                    $AddedMobileNumbers = [];
                    if(isset($phone_numbers[1][0]))
                    {
                        $AddedMobileNumbers[0]['type']   = $phone_numbers[1][0]['number_type'];
                        $AddedMobileNumbers[0]['number'] = $phone_numbers[1][0]['number'];
                        $AddedMobileNumbers[0]['id']     = $phone_numbers[1][0]['id'];
                        unset($phone_numbers[1][0]);
                    }
                     foreach($phone_numbers[1] as $key => $val)
                    {
                        echo '<div class="row border-top"> 
                                    <div class="col-10 col-md-11">
                                        <div class="form-group custom-group">
                                            <label>Şirkət mobil nömrəsi</label>
                                            <input class="form-control" type="text" value="'.$val['number'].'"  name="SiteEnterprises[mobile_numbers][]" placeholder="Şirkət mobil nömrəsi">
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-1">
                                        <button type="button" class="cb cyan d-inline-block remove">-</button>
                                    </div>
                                </div>';


                        $AddedMobileNumbers[$key]['type']   = $val['number_type'];
                        $AddedMobileNumbers[$key]['number'] = $val['number'];
                        $AddedMobileNumbers[$key]['id']     = $val['id'];

                    }
                    $AddedMobileNumbers = base64_encode(json_encode($AddedMobileNumbers));
                    echo '<input name="SiteEnterprises[added_mobile_numbers]" type="hidden" value="'.$AddedMobileNumbers.'">';


                }
                ?>
            </div>
            <div class="t-card mini3 -h-top append-block general third">
                <div class="row pad">
                    <?php
                    $addresses = SiteAddresses::find()->where(['connect_id'=>$model->id,'type'=>2])->all();
                    $count_addresses = !empty($addresses) ? count($addresses)-1 : 0;
                    ?>
                    <div class="col-10 col-md-11">
                        <div class="form-group custom-group">
                            <label>Şirkət ünvanı</label>
                            <input class="form-control" type="text" value="<?= isset($addresses[0]) ? $addresses[0]['address'] : null; ?>" name="SiteEnterprises[addresses][]" placeholder="Şirkət ünvan">
                        </div>
                    </div>
                    <div class="col-2 col-md-1">
                        <button type="button" data-val="Şirkət ünvanı" data-cName=".append-block.third"
                                data-input="SiteEnterprises[addresses][]" class="cb cyan d-inline-block add third"  data-count="<?= $count_addresses?>">+
                        </button>
                    </div>
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
                            echo '<div class="row border-top"> 
                                    <div class="col-10 col-md-11">
                                        <div class="form-group custom-group">
                                            <label>Şirkət ünvanı</label>
                                            <input class="form-control" type="text" value="'.$val['address'].'"  name="SiteEnterprises[addresses][]" placeholder="Şirkət ünvan">
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-1">
                                        <button type="button" class="cb cyan d-inline-block remove">-</button>
                                    </div>
                                </div>';
                            $AddedAddresses[$key]['id']   = $val['id'];
                            $AddedAddresses[$key]['name'] = $val['address'];
                        }
                        $AddedAddresses = base64_encode(json_encode($AddedAddresses));
                        echo '<input name="SiteEnterprises[added_addresses]" type="hidden" value="'.$AddedAddresses.'">';
                    }
                    ?>

            </div>
            <div class="t-card mini3 -h-top social">
                <div class="row">
                    <?php
                    $AddedSosialLinks = [];
                    $link_0 = $link_1 = $link_2 = $link_3 = $link_4 = $link_5 = '';
                    foreach($sosialLinks as $key => $val)
                    {
                        $AddedSosialLinks[$key]['type'] = $val['link_type'];
                        $AddedSosialLinks[$key]['link'] = $val['link'];
                        $AddedSosialLinks[$key]['id']   = $val['id'];


                        if($val->link_type==0){
                            $link_0 = $val->link;
                        }elseif($val->link_type==1){
                            $link_1 = $val->link;
                        }elseif($val->link_type==2){
                            $link_2 = $val->link;
                        }elseif($val->link_type==3){
                            $link_3 = $val->link;
                        }elseif($val->link_type==4){
                            $link_4 = $val->link;
                        }elseif($val->link_type==5){
                            $link_5 = $val->link;
                        }


                    }

                    ?>
                    <div class="col-6">
                        <?=$form->field($model, 'sosial_links[0][link]')->textInput(["placeholder" => "Facebook","value" => $link_0])->label("Facebook");?>
                        <input type="hidden" value="0" name="SiteEnterprises[sosial_links][0][type]">
                    </div>
                    <div class="col-6">
                        <?=$form->field($model, 'sosial_links[3][link]')->textInput(["placeholder" => "Twitter","value" => $link_3])->label("Twitter");?>
                        <input type="hidden" value="3" name="SiteEnterprises[sosial_links][3][type]">
                    </div>
                    <div class="col-6">
                        <?=$form->field($model, 'sosial_links[2][link]')->textInput(["placeholder" => "Youtube","value" => $link_2])->label("Youtube");?>
                        <input type="hidden" value="2" name="SiteEnterprises[sosial_links][2][type]">
                    </div>
                    <div class="col-6">
                        <?=$form->field($model, 'sosial_links[4][link]')->textInput(["placeholder" => "Linkedin","value" => $link_4])->label("Linkedin");?>
                        <input type="hidden" value="4" name="SiteEnterprises[sosial_links][4][type]">
                    </div>
                    <div class="col-6">
                        <?=$form->field($model, 'sosial_links[1][link]')->textInput(["placeholder" => "Instagram","value" => $link_1])->label("Instagram");?>
                        <input type="hidden" value="1" name="SiteEnterprises[sosial_links][1][type]">
                    </div>
                    <div class="col-6">
                        <?=$form->field($model, 'sosial_links[5][link]')->textInput(["placeholder" => "Skype","value" => $link_5])->label("Skype");?>
                        <input type="hidden" value="5" name="SiteEnterprises[sosial_links][5][type]">
                    </div>
                    <?php
                    $AddedSosialLinks = base64_encode(json_encode($AddedSosialLinks));
                    echo '<input name="SiteEnterprises[added_sosial_links]" type="hidden" value="'.$AddedSosialLinks.'">';
                    ?>
                </div>
            </div>
            <div class="t-card mini3 -h-top social">
                <?= $form->field($model, 'about')->textarea() ?>

            </div>
            <div class="t-card mini3 -h-top social">
                <div class="row">
                    <div class="col-6">
                        <?= $form->field($model, 'password')->passwordInput() ?>
                    </div>
                    <div class="col-6">
                        <?= $form->field($model, 'repassword')->passwordInput() ?>
                    </div>
                </div>
            </div>
            <div class="t-card mini3 -h-top social">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <?php
                        echo \yii\helpers\Html::submitButton("Yadda Saxla", ['class' => 'cb orange orange-hover shadow inner-shadow']);
                        ?>
                        <div class="form-group upload">
                            <?=$form->field($model, 'diploma_file')->fileInput(['multiple' => false,'class'=>'form-control-file'])->label('Şəhadətnamə')?>

                        </div>
                        <?php
                        if($model->diploma_file != null) echo '<p style="font-size: 10px">Sizin şəhadətnamə yüklənmişdir.</p>'
                        ?>
                    </div>
                    <div class="col-12 col-md-6 check-list">
                        <?php
                        $feature = json_decode($model->feature,true);
                        $catdirilma_status = $saat24_status = $eve_caqiris_status = '';
                        if(isset($feature['catdirilma'])){ $model->catdirilma = 1; $catdirilma_status = ' checked'; }
                        if(isset($feature['saat24'])){ $model->saat24 = 1; $saat24_status = ' checked';}
                        if(isset($feature['eve_caqiris'])){ $model->eve_caqiris = 1; $eve_caqiris_status = ' checked'; }
                        ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="SiteEnterprises[catdirilma]" value="1"<?=$catdirilma_status?>>
                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                Evə çatdırılma
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="SiteEnterprises[saat24]" value="1"<?=$saat24_status?>>
                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                24 Saat açıq
                            </label>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<style>
    .orange{
        padding: 8px 36px !important;
    }
</style>