<?PHP

use yii\widgets\ActiveForm;
use backend\models\SiteMenus;
use backend\models\SitePhoneNumbers;
use yii\helpers\ArrayHelper;
use backend\components\Functions;
use backend\models\SiteSpecialists;
use yii\helpers\Html;
use backend\models\ViewDoctorSpecialist;

$form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data',
        "class" => "doc-log",
    ],
    'fieldConfig' => [
        'template' => "<div class=\"form-group custom-group\">{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div></div>",
        'labelOptions' => ['class' => ''],
        'options' => [
            'class' => 'form-group'
        ],
    ],
]);
$template = ['template' => "{input}{hint}<div class=\"help-block help-block-error\">{error}</div>"];
$sosialLinks = \backend\models\SiteSosialLinks::find()->where(['connect_id' => $model->id, 'type' => 1])->all();
$linkOptions = isset($sosialLinks[0]['link_type']) ? Functions::SosialLinkType($model, $sosialLinks[0]['link_type']) : Functions::SosialLinkType($model);
$count_sosialLinks = !empty($sosialLinks) ? count($sosialLinks) - 1 : 0;
//$photo = !empty($model->photo) ? Functions::getUploadUrl().Yii::$app->params['path.doctor'].'/small/'.$model->photo : 'site.defaultThumbDoctor';
if(!empty($model->photo)) $photo = Functions::getUploadUrl() . $customPath . '/small/' . $model->photo;
else {
    $photo = ($model->gender == 0 ? Yii::$app->params['site.defaultThumbDoctorF'] : Yii::$app->params['site.defaultThumbDoctor']);
}



?>
    <style>
        .select2.select2-container.select2-container--default {
            margin-top: 5px;
        }
        .datepicker.dropdown-menu {
            margin: 80px 0;
        }
    </style>
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
                                <li class="breadcrumb-item"><a href="/profil/sual-cavab">Profil</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tənzimləmələr</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <?php
            if (Yii::$app->session->hasFlash("success")) {
                echo '<br /><div class="alert alert-success">' . Yii::$app->session->getFlash("success") . '</div>';
            }
            ?>
            <div class="t-card mini3 -h-top block">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="image-upload">
                            <img class="profile-image" src="<?= $photo; ?>" alt="<?= $model->name; ?>">
                            <div class="p-image">
                                <img src="assets/img/icon/photo.png" alt="photo" class="upload-button">
                                <?= $form->field($model, 'files')->fileInput(['class' => "file-upload"])->label(' ') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group custom-group">
                                    <?= $form->field($model, 'name')->textInput(['maxlength' => true, "class" => "form-control", "placeholder" => "Ad"]) ?>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group date custom-group" data-provide="datepicker"
                                     id="datepicker_experience">
                                    <?= $form->field($model, 'experience1')->textInput(["class" => "form-control", "placeholder" => "", 'value' => $model->experience1]) ?>
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
                            <!--                            <div class="col-md-6 col-sm-6 col-xs-12">-->
                            <!--                                <div class="form-group custom-group">-->
                            <!--                                    --><? //= $form->field($model, 'experience')->textInput(['maxlength' => true,"class" => "form-control","placeholder" => "Məs:14"]) ?>
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php
                                //var_dump($model->specialists);
                                ?>
                                <div class="form-group custom-group">
                                    <label>İxtisas seç*</label>
                                    <select class="form-control multi-select-demo" name="SiteDoctors[specialists][]"
                                            multiple="multiple">
                                        <?php

                                        //print_r($tm_list);
                                        foreach ($specialist_list as $key => $item) {
                                            ?>
                                            <option value="<?= $key; ?>" <?php if (in_array($key, $model->specialists)) echo 'selected'; ?>> <?= $item; ?> </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group custom-group">
                                    <label>Elmi dərəcə</label>
                                    <?= $form->field($model, 'degree', $template)->dropDownList($model->getDegree(), ['class' => 'w-100', 'minimumResultsForSearch' => "-1", 'data-placeholder' => "İş təcrübəniz", "select2" => "select2"]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="t-card mini3 -h-top datetime">
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="form-group date custom-group" data-date-format="dd-mm-yyyy"
                             data-provide="datepicker">
                            <?php

                            ?>
                            <?= $form->field($model, 'birthday')->textInput(["class" => "form-control", "placeholder" => "--/--/--"]) ?>
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-6 col-xs-12">
                        <div class="form-group custom-group">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true, "class" => "form-control", "placeholder" => "E-mail"]) ?>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="form-group custom-group">
                            <label>Cins*</label>
                            <?= $form->field($model, 'gender', $template)->dropDownList(['0' => 'Qadın', '1' => 'Kişi'], ['class' => 'w-100', 'minimumResultsForSearch' => "-1", 'data-placeholder' => "Cinsiniz", "select2" => "select2"]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="t-card mini3 -h-top append-block general single">
                <?php
                $phoneNumbers = SitePhoneNumbers::find()->where(['connect_id' => $model->id, 'type' => 1])->all();
                $count_phoneNumbers = !empty($phoneNumbers) ? count($phoneNumbers) - 1 : 0;

                $phone_numbers[0] = [];
                $phone_numbers[1] = [];
                if (!empty($phoneNumbers)) {

                    foreach ($phoneNumbers as $key => $val) {
                        $phone_numbers[$val['number_type']][] = $phoneNumbers[$key];
                    }
                }
                $count_phoneNumbers = count($phone_numbers[0]) >= 1 ? (count($phone_numbers[0]) - 1) : 0;
                $count_mobileNumbers = count($phone_numbers[1]) >= 1 ? (count($phone_numbers[1]) - 1) : 0;
                ?>
                <div class="row pad">
                    <div class="col-10 col-md-11">
                        <div class="form-group custom-group">
                            <label>Telefon nömrələri</label>
                            <input class="form-control" type="text" name="SiteDoctors[phone_numbers][]"
                                   value="<?= isset($phone_numbers[0][0]['number']) ? $phone_numbers[0][0]['number'] : null; ?>"
                                   placeholder="Telefon">
                        </div>
                    </div>
                    <div class="col-2 col-md-1">
                        <button type="button" data-val="Şirkət telefonu" data-input="SiteDoctors[phone_numbers][]"
                                data-cName=".append-block.single" class="cb cyan d-inline-block add first"
                                data-count="<?= $count_phoneNumbers ?>">+
                        </button>
                    </div>
                </div>
                <?PHP
                if (!empty($phone_numbers[0])) {
                    $AddedPhoneNumbers = [];
                    if (isset($phone_numbers[0][0])) {
                        $AddedPhoneNumbers[0]['type'] = $phone_numbers[0][0]['number_type'];
                        $AddedPhoneNumbers[0]['number'] = $phone_numbers[0][0]['number'];
                        $AddedPhoneNumbers[0]['id'] = $phone_numbers[0][0]['id'];
                        unset($phone_numbers[0][0]);
                    }

                    foreach ($phone_numbers[0] as $key => $val) {
                        echo '<div class="row border-top"> 
                                    <div class="col-10 col-md-11">
                                        <div class="form-group custom-group">
                                            <label>Telefon nömrələri</label>
                                            <input class="form-control" type="text" value="' . $val['number'] . '"  name="SiteDoctors[phone_numbers][]" placeholder="Şirkət nömrəsi">
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-1">
                                        <button type="button" class="cb cyan d-inline-block remove">-</button>
                                    </div>
                                </div>';


                        $AddedPhoneNumbers[$key]['type'] = $val['number_type'];
                        $AddedPhoneNumbers[$key]['number'] = $val['number'];
                        $AddedPhoneNumbers[$key]['id'] = $val['id'];

                    }
                    $AddedPhoneNumbers = base64_encode(json_encode($AddedPhoneNumbers));
                    echo '<input name="SiteDoctors[added_phone_numbers]" type="hidden" value="' . $AddedPhoneNumbers . '">';


                }
                ?>


            </div>
            <div class="t-card mini3 -h-top append-block general sec">
                <div class="row pad">
                    <div class="col-10 col-md-11">
                        <div class="form-group custom-group">
                            <label>Mobil nömrələr*</label>
                            <input class="form-control" type="text" name="SiteDoctors[mobile_numbers][]"
                                   value="<?= isset($phone_numbers[1][0]['number']) ? $phone_numbers[1][0]['number'] : null; ?>"
                                   placeholder="Telefon">
                        </div>
                    </div>
                    <div class="col-2 col-md-1">
                        <button type="button" data-val="Şirkət Mobil telefonu" data-cName=".append-block.sec"
                                data-input="SiteDoctors[mobile_numbers][]" class="cb cyan d-inline-block add sec"
                                data-count="<?= $count_mobileNumbers ?>">+
                        </button>
                    </div>
                </div>
                <?PHP
                if (!empty($phone_numbers[1])) {
                    $AddedMobileNumbers = [];
                    if (isset($phone_numbers[1][0])) {
                        $AddedMobileNumbers[0]['type'] = $phone_numbers[1][0]['number_type'];
                        $AddedMobileNumbers[0]['number'] = $phone_numbers[1][0]['number'];
                        $AddedMobileNumbers[0]['id'] = $phone_numbers[1][0]['id'];
                        unset($phone_numbers[1][0]);
                    }
                    foreach ($phone_numbers[1] as $key => $val) {
                        echo '<div class="row border-top"> 
                                    <div class="col-10 col-md-11">
                                        <div class="form-group custom-group">
                                            <label>Şirkət mobil nömrəsi</label>
                                            <input class="form-control" type="text" value="' . $val['number'] . '"  name="SiteDoctors[mobile_numbers][]" placeholder="Şirkət mobil nömrəsi">
                                        </div>
                                    </div>
                                    <div class="col-2 col-md-1">
                                        <button type="button" class="cb cyan d-inline-block remove">-</button>
                                    </div>
                                </div>';


                        $AddedMobileNumbers[$key]['type'] = $val['number_type'];
                        $AddedMobileNumbers[$key]['number'] = $val['number'];
                        $AddedMobileNumbers[$key]['id'] = $val['id'];
                    }
                    $AddedMobileNumbers = base64_encode(json_encode($AddedMobileNumbers));
                    echo '<input name="SiteDoctors[added_mobile_numbers]" type="hidden" value="' . $AddedMobileNumbers . '">';
                }
                ?>
            </div>
            <div class="t-card mini3 -h-top append-block twice">
                <?php
                $addresses = \frontend\models\SiteDoctorWorkplacesList::find()->where(['doctor_id' => $model->id])->all();
                $count_addresses = !empty($addresses) ? count($addresses) - 1 : 0;
                ?>
                <div class="row pad">
                    <div class="col-12 col-sm-5 col-md-5">
                        <div class="form-group custom-group">
                            <label>İş yeri adı</label>
                            <input class="form-control" type="text" name="SiteDoctors[workplaces_list][0][name]"
                                   placeholder="İş yeri adı"
                                   value="<?= isset($addresses[0]) ? $addresses[0]['name'] : null; ?>">
                        </div>
                    </div>
                    <div class="col-12 col-sm-7 col-md-7">
                        <div class="row">
                            <div class="col-8 col-md-10">
                                <div class="form-group custom-group">
                                    <label>İş yeri ünvanı</label>
                                    <input class="form-control" type="text"
                                           name="SiteDoctors[workplaces_list][0][address]"
                                           placeholder="İş yeri ünvanınız"
                                           value="<?= isset($addresses[0]) ? $addresses[0]['address'] : null; ?>">
                                </div>
                            </div>
                            <div class="col-4 col-md-2">
                                <button type="button" data-label class="cb cyan d-inline-block add twice"
                                        data-val1-third="İş yeri adı" data-val2-third="İş yeri ünvanı"
                                        data-cName=".append-block.twice"
                                        data-input1="SiteDoctors[workplaces_list][0][name]"
                                        data-input2="SiteDoctors[workplaces_list][0][address]"
                                        data-count="0">+
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                <?PHP
                if (!empty($addresses)) {
                    $AddedAddresses = [];
                    if (isset($addresses[0])) {
                        $AddedAddresses[0]['id'] = $addresses[0]['id'];
                        $AddedAddresses[0]['name'] = $addresses[0]['name'];
                        $AddedAddresses[0]['address'] = $addresses[0]['address'];
                        unset($addresses[0]);
                    }
                    foreach ($addresses as $key => $val) {
                        ?>
                        <div class="row border-top">
                            <div class="col-12 col-sm-5 col-md-5">
                                <div class="form-group custom-group">
                                    <label>İş yeri adı</label>
                                    <input class="form-control" type="text"
                                           name="SiteDoctors[workplaces_listworkplaces_list][][name]"
                                           placeholder="İş yeri adı" value="<?= $val['name'] ?>">
                                </div>
                            </div>
                            <div class="col-12 col-sm-7 col-md-7">
                                <div class="row">
                                    <div class="col-8 col-md-10">
                                        <div class="form-group custom-group">
                                            <label>İş yeri ünvanı</label>
                                            <input class="form-control" type="text"
                                                   name="SiteDoctors[workplaces_list][][address]"
                                                   placeholder="İş yeri ünvanınız" value="<?= $val['address'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-2">
                                        <button type="button" class="cb cyan d-inline-block remove added">-</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <?php
                        $AddedAddresses[$key]['id'] = $val['id'];
                        $AddedAddresses[$key]['name'] = $val['name'];
                        $AddedAddresses[$key]['address'] = $val['address'];
                    }
                    $AddedAddresses = base64_encode(json_encode($AddedAddresses));
                    echo '<input name="SiteDoctors[added_addresses]" type="hidden" value="' . $AddedAddresses . '">';
                }
                ?>
            </div>
            <div class="t-card mini3 -h-top social">
                <div class="row">
                    <?php
                    $AddedSosialLinks = [];
                    $link_0 = $link_1 = $link_2 = $link_3 = $link_4 = $link_5 = '';
                    foreach ($sosialLinks as $key => $val) {
                        $AddedSosialLinks[$key]['type'] = $val['link_type'];
                        $AddedSosialLinks[$key]['link'] = $val['link'];
                        $AddedSosialLinks[$key]['id'] = $val['id'];
                        if ($val->link_type == 0) {
                            $link_0 = $val->link;
                        } elseif ($val->link_type == 1) {
                            $link_1 = $val->link;
                        } elseif ($val->link_type == 2) {
                            $link_2 = $val->link;
                        } elseif ($val->link_type == 3) {
                            $link_3 = $val->link;
                        } elseif ($val->link_type == 4) {
                            $link_4 = $val->link;
                        } elseif ($val->link_type == 5) {
                            $link_5 = $val->link;
                        }
                    }
                    ?>
                    <div class="col-6">
                        <?= $form->field($model, 'sosial_links[0][link]')->textInput(["placeholder" => "Facebook", "value" => $link_0])->label("Facebook"); ?>
                        <input type="hidden" value="0" name="SiteDoctors[sosial_links][0][type]">
                    </div>
                    <div class="col-6">
                        <?= $form->field($model, 'sosial_links[3][link]')->textInput(["placeholder" => "Twitter", "value" => $link_3])->label("Twitter"); ?>
                        <input type="hidden" value="3" name="SiteDoctors[sosial_links][3][type]">
                    </div>
                    <div class="col-6">
                        <?= $form->field($model, 'sosial_links[2][link]')->textInput(["placeholder" => "Youtube", "value" => $link_2])->label("Youtube"); ?>
                        <input type="hidden" value="2" name="SiteDoctors[sosial_links][2][type]">
                    </div>
                    <div class="col-6">
                        <?= $form->field($model, 'sosial_links[4][link]')->textInput(["placeholder" => "Linkedin", "value" => $link_4])->label("Linkedin"); ?>
                        <input type="hidden" value="4" name="SiteDoctors[sosial_links][4][type]">
                    </div>
                    <div class="col-6">
                        <?= $form->field($model, 'sosial_links[1][link]')->textInput(["placeholder" => "Instagram", "value" => $link_1])->label("Instagram"); ?>
                        <input type="hidden" value="1" name="SiteDoctors[sosial_links][1][type]">
                    </div>
                    <div class="col-6">
                        <?= $form->field($model, 'sosial_links[5][link]')->textInput(["placeholder" => "Skype", "value" => $link_5])->label("Skype"); ?>
                        <input type="hidden" value="5" name="SiteDoctors[sosial_links][5][type]">
                    </div>
                    <?php
                    $AddedSosialLinks = base64_encode(json_encode($AddedSosialLinks));
                    echo '<input name="SiteDoctors[added_sosial_links]" type="hidden" value="' . $AddedSosialLinks . '">';
                    ?>
                </div>
            </div>
            <div class="t-card mini3 -h-top social">
                <div class="form-group custom-group">
                    <?= $form->field($model, 'about')->textarea(["placeholder" => "Haqqımda məlumat", "class" => "form-control"]) ?>
                </div>
            </div>
            <div class="t-card mini3 -h-top social">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group custom-group">
                            <?= $form->field($model, 'password')->passwordInput() ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group custom-group">
                            <?= $form->field($model, 'repassword')->passwordInput() ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="t-card mini3 -h-top social">
                <div class="row">
                    <div class="col-12 col-md-3 col-sm-3">
                        <?= Html::submitButton("Yadda saxla", ['class' => 'cb orange orange-hover shadow inner-shadow']); ?>
                    </div>
                    <div class="col-12 col-md-9 col-sm-9 check-list">
                        <?php
                        $feature = json_decode($model->feature, true);
                        $child_doctor_status = $home_doctor_status = '';
                        if (isset($feature['child_doctor'])) {
                            $model->child_doctor = 2;
                            $child_doctor_status = ' checked';
                        }
                        if (isset($feature['home_doctor'])) {
                            $model->home_doctor = 1;
                            $home_doctor_status = ' checked';
                        }
                        ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="SiteDoctors[home_doctor]"
                                       value="1"<?= $home_doctor_status ?>>
                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                Evə çağırış
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="SiteDoctors[child_doctor]"
                                       value="2" <?= $child_doctor_status ?>>
                                <span class="cr"><i class="cr-icon fa fa-check"></i></span>
                                Uşaq həkimi
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>