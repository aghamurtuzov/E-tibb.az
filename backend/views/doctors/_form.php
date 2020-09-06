<?phpuse yii\helpers\Html;use yii\widgets\ActiveForm;use backend\assets\AppAsset;use yii\helpers\ArrayHelper;use backend\models\SiteSpecialists;use backend\models\SiteDoctorFilesModel;use backend\models\ViewDoctorSpecialist;use backend\models\SiteSosialLinks;use backend\models\SitePhoneNumbers;use backend\models\SiteGallery;use backend\models\SiteMenus;use backend\models\SiteDoctors;use backend\models\SiteDoctorWorkplaces;use backend\components\Functions;/* @var $this yii\web\View *//* @var $model backend\models\SiteDoctors *//* @var $form yii\widgets\ActiveForm */?><div class="row">    <div class="col-md-12 col-sm-12 col-xs-12">        <div class="bgWhite">            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 p-0">                <div class="x_panel">                    <div class="x_content">                        <div class="" role="tabpanel" data-example-id="togglable-tabs">                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">                                <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">Əsas</a>                                </li>                            </ul>                            <div id="myTabContent" class="tab-content">                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">                        <?php                            if ($model->isNewRecord) {                                $linkOptions    = Functions::SosialLinkType($model);                                $numberOptions  = Functions::PhoneNumberType($model);                            } else {                                $sosialLinks        = SiteSosialLinks::find()->where(['connect_id' => $model->id, 'type' => 1])->all();                                $linkOptions        = isset($sosialLinks[0]['link_type']) ? Functions::SosialLinkType($model, $sosialLinks[0]['link_type']) : Functions::SosialLinkType($model);                                $count_sosialLinks  = !empty($sosialLinks) ? count($sosialLinks) - 1 : 0;                                $phoneNumbers       = SitePhoneNumbers::find()->where(['connect_id' => $model->id, 'type' => 1])->all();                                $numberOptions      = isset($phoneNumbers[0]['number_type']) ? Functions::PhoneNumberType($model, $phoneNumbers[0]['number_type']) : Functions::PhoneNumberType($model);                                $count_phoneNumbers = !empty($phoneNumbers) ? count($phoneNumbers) - 1 : 0;                            }                            $template        = ['template' => "{label}<div class=\"col-lg-5 col-md-4 col-sm-8 col-xs-12\">{input}{hint}{error}</div>"];                            $template_sosial = ['template' => "{label}<div class=\"col-lg-5 col-md-4 col-sm-8 col-xs-12\"><select class=\"miniSelectBox\" name=\"SiteDoctors[sosial_links][0][type]\">{$linkOptions}</select><div class=\"input-group\">{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-primary inputAdd\" data-t=\"1\" data-c=\"0\">+</button></span>{hint}{error}</div></div>"];                            $template_number = ['template' => "{label}<div class=\"col-lg-5 col-md-4 col-sm-8 col-xs-12\"><select class=\"miniSelectBox\" name=\"SiteDoctors[phone_numbers][0][type]\">{$numberOptions}</select><div class=\"input-group\">{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-primary inputAdd\" data-t=\"2\" data-c=\"0\">+</button></span>{hint}{error}</div></div>"];                            $template_plus2  = ['template' => "{label}<div class=\"col-lg-5 col-md-4 col-sm-8 col-xs-12\"><div class=\"input-group\">{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-primary inputAdd\" data-t=\"4\" data-c=\"0\">+</button></span>{hint}{error}</div><div class=\"input-group\"> <input type=\"text\" id=\"sitedoctors-new_workplace\" class=\"form-control\" placeholder=\"Ünvan\" name=\"SiteDoctors[workplaces_list][0][name]\"><span class=\"input-group-btn\"><div class=\"btn btn-none inputAdd\">+</div></span> </div> <div class=\"input-group\"> <input type=\"text\" id=\"sitedoctors-new_workplace\" class=\"form-control\" placeholder=\"Ünvan\" name=\"SiteDoctors[workplaces_list][0][address]\"><span class=\"input-group-btn\"><div class=\"btn btn-none inputAdd\">+</div></span></div></div>"];                            $form = ActiveForm::begin([                                'options' => [                                    'enctype' => 'multipart/form-data',                                ],                                'fieldConfig' => [                                    'template' => "{label}<div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\">{input}{hint}{error}</div>",                                    'labelOptions' => ['class' => 'control-label col-lg-12 col-md-12 col-sm-12 col-xs-12'],                                    'options' => [                                        'class' => 'form-group row'                                    ],                                ],                            ]);                        ?>                        <?= $form->field($model, 'status',$template)->dropDownList(SiteDoctors::getStatus(),['tabindex'=>'-1', 'class'=>'select2_single form-control','options' => [$model->status => ['selected' => 'true']]]);?>                                    <?PHP                            if ($model->isNewRecord) {                                if (Yii::$app->request->isGet) {                                    $hidden_id = Yii::$app->request->get('id');                                    if (!empty($hidden_id)) {                                        echo '<input type="hidden" name="hidden_user" value="' . $hidden_id . '">';                                    }                                }                            }                        ?>                        <?= $form->field($model, 'name', $template)->textInput(['maxlength' => true]) ?>                        <?PHP                            if($model->isNewRecord)                                echo $form->field($model, 'email', $template)->textInput(['maxlength' => true]);                            else                                echo $form->field($model, 'email', $template)->textInput(['maxlength' => true, 'disabled' => 'true']);                        ?>                        <?= $form->field($model, 'gender',$template)->dropDownList(['0' => 'Qadın','1' => 'Kişi'],['class'=>'select2_single form-control']); ?>                        <?= $form->field($model, 'experience1',$template)->textInput(["class" => "form-control datetime-mask","placeholder"=>"2010"])?>                        <?PHP                            if ($model->isNewRecord)                                echo $form->field($model, 'degree', $template)->dropDownList(SiteDoctors::getDegree(), ['class' => 'select2_single form-control', 'options' => [$model->degree => ['selected' => 'selected']]]);                            else                                echo $form->field($model, 'degree', $template)->dropDownList(SiteDoctors::getDegree(), ['tabindex' => '-1', 'class' => 'select2_single form-control']);                        ?>                        <?PHP                            if ($model->isNewRecord) {                                echo $form->field($model, 'specialists[]', $template)->dropDownList(ArrayHelper::map(SiteSpecialists::find()->orderBy('name')->all(), 'id', 'name'), ['multiple' => 'multiple', 'class' => 'form-control multiple_selectBox']);                            } else {                                $options         = [];                                $selectedOptions = [];                                $selectedSpc     = ViewDoctorSpecialist::find()->where(['doctor_id' => $model->id])->all();                                if (!empty($selectedSpc)) {                                    foreach ($selectedSpc as $val) {                                        $options[$val['id']] = ['selected' => true];                                        $selectedOptions[]   = $val['id'];                                    }                                }                                $selectedOptions = base64_encode(json_encode($selectedOptions));                                echo $form->field($model, 'specialists[]', $template)->dropDownList(ArrayHelper::map(SiteSpecialists::find()->orderBy('name')->all(), 'id', 'name'), ['multiple' => 'multiple', 'class' => 'form-control multiple_selectBox', 'options' => $options]);                                echo "<input name=\"SiteDoctors[spc_selected_options]\" type=\"hidden\" value=\"$selectedOptions\">";                            }                        ?><!--                        <div class="col-md-6 col-sm-6 col-xs-12 ">--><!--                            <div class="form-group date custom-group" data-date-format="yyyy" data-provide="datepicker" id="datepicker_experience">--><!--                               <div class="input-group-addon">--><!--                                    <span class="glyphicon glyphicon-th"></span>--><!--                                </div>--><!--                                <style>--><!--                                    .datepicker.dropdown-menu {--><!--                                        margin: 80px 0;--><!--                                    }--><!--                                </style>--><!--                            </div>--><!--                        </div>-->                        <?PHP                            if ($model->isNewRecord) {                                echo $form->field($model, 'sosial_links[0][link]', $template_sosial)->textInput();                            }                            else                            {                        ?>                            <div class="form-group row field-sitedoctors-sosial_links-0-link">                                <label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12"                                       for="sitedoctors-sosial_links-0-link">Sosial linklər</label>                                <div class="col-lg-5 col-md-4 col-sm-8 col-xs-12">                                    <select class="miniSelectBox"                                            name="SiteDoctors[sosial_links][0][type]"><?= $linkOptions ?></select>                                    <div class="input-group">                                        <input type="text"                                               value="<?= isset($sosialLinks[0]['link']) ? $sosialLinks[0]['link'] : null; ?>"                                               id="sitedoctors-sosial_links-0-link" class="form-control"                                               name="SiteDoctors[sosial_links][0][link]">                                        <span class="input-group-btn"><button type="button"                                                                              class="btn btn-primary inputAdd"                                                                              data-t="1"                                                                              data-c="<?= $count_sosialLinks ?>">+</button></span>                                        <div class="help-block"></div>                                    </div>                                    <?PHP                                    $AddedSosialLinks = [];                                    if (!empty($sosialLinks)) {                                        if (isset($sosialLinks[0])) {                                            $AddedSosialLinks[0]['type'] = $sosialLinks[0]['link_type'];                                            $AddedSosialLinks[0]['link'] = $sosialLinks[0]['link'];                                            $AddedSosialLinks[0]['id'] = $sosialLinks[0]['id'];                                            unset($sosialLinks[0]);                                        }                                        foreach ($sosialLinks as $key => $val) {                                            echo '<select class="miniSelectBox" name="SiteDoctors[sosial_links][' . $key . '][type]">';                                            echo Functions::SosialLinkType($model, $val['link_type']);                                            echo '</select>';                                            echo '<div class="input-group">';                                            echo "<input type=\"text\" id=\"sitedoctors-sosial_links\" class=\"form-control\" value=\"{$val['link']}\" name=\"SiteDoctors[sosial_links][$key][link]\">";                                            echo '<span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span>';                                            echo '</div>';                                            $AddedSosialLinks[$key]['type'] = $val['link_type'];                                            $AddedSosialLinks[$key]['link'] = $val['link'];                                            $AddedSosialLinks[$key]['id'] = $val['id'];                                        }                                        $AddedSosialLinks = base64_encode(json_encode($AddedSosialLinks));                                        echo '<input name="SiteDoctors[added_sosial_links]" type="hidden" value="' . $AddedSosialLinks . '">';                                    }                                    ?>                                </div>                            </div>                        <?PHP                            }                        ?>                        <?PHP                            if ($model->isNewRecord) {                            echo $form->field($model, 'phone_numbers[0][number]', $template_number)->textInput();                        } else {                        ?>                            <div class="form-group row field-sitedoctors-phone_numbers-0-number">                                <label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12"                                       for="sitedoctors-phone_numbers-0-number">Telefon nömrələri</label>                                <div class="col-lg-5 col-md-4 col-sm-8 col-xs-12">                                    <select class="miniSelectBox"                                            name="SiteDoctors[phone_numbers][0][type]"><?= $numberOptions ?></select>                                    <div class="input-group">                                        <input type="text"                                               value="<?= isset($phoneNumbers[0]['number']) ? $phoneNumbers[0]['number'] : null; ?>"                                               id="sitedoctors-phone_numbers-0-number" class="form-control"                                               name="SiteDoctors[phone_numbers][0][number]">                                        <span class="input-group-btn"><button type="button"                                                                              class="btn btn-primary inputAdd"                                                                              data-t="2"                                                                              data-c="<?= $count_phoneNumbers ?>">+</button></span>                                        <div class="help-block"></div>                                    </div>                                    <?PHP                                    if (!empty($phoneNumbers)) {                                        $AddedPhoneNumbers = [];                                        if (isset($phoneNumbers[0])) {                                            $AddedPhoneNumbers[0]['type'] = $phoneNumbers[0]['number_type'];                                            $AddedPhoneNumbers[0]['number'] = $phoneNumbers[0]['number'];                                            $AddedPhoneNumbers[0]['id'] = $phoneNumbers[0]['id'];                                            unset($phoneNumbers[0]);                                        }                                        foreach ($phoneNumbers as $key => $val) {                                            echo '<select class="miniSelectBox" name="SiteDoctors[phone_numbers][' . $key . '][type]">';                                            echo Functions::PhoneNumberType($model, $val['number_type']);                                            echo '</select>';                                            echo '<div class="input-group">';                                            echo "<input type=\"text\" id=\"sitedoctors-phone_numbers\" class=\"form-control\" value=\"{$val['number']}\" name=\"SiteDoctors[phone_numbers][$key][number]\">";                                            echo '<span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span>';                                            echo '</div>';                                            $AddedPhoneNumbers[$key]['type'] = $val['number_type'];                                            $AddedPhoneNumbers[$key]['number'] = $val['number'];                                            $AddedPhoneNumbers[$key]['id'] = $val['id'];                                        }                                        $AddedPhoneNumbers = base64_encode(json_encode($AddedPhoneNumbers));                                        echo '<input name="SiteDoctors[added_phone_numbers]" type="hidden" value="' . $AddedPhoneNumbers . '">';                                    }                                    ?>                                </div>                            </div>                            <?PHP                        }                        ?>                        <?PHP                        if ($model->isNewRecord) {                            echo $form->field($model, 'addresses[0][name]', $template_plus2)->textInput();                        } else {                            $addresses = SiteDoctorWorkplaces::find()->where(['doctor_id' => $model->id])->all();                            $count_addresses = !empty($addresses) ? count($addresses) - 1 : 0;                            ?>                            <div class="form-group row field-sitedoctors-addresses-0-name">                                <label class="control-label col-lg-12 col-md-12 col-sm-12 col-xs-12"                                       for="sitedoctors-addresses-0-name">İş Yerləri</label>                                <div class="col-lg-5 col-md-4 col-sm-8 col-xs-12">                                    <div class="input-group">                                        <input type="text"                                               value="<?= isset($addresses[0]) ? $addresses[0]['name'] : null; ?>"                                               id="sitedoctors-addresses-0-name" class="form-control"                                               name="SiteDoctors[workplaces_list][0][name]">                                        <input type="text"                                               value="<?= isset($addresses[0]) ? $addresses[0]['address'] : null; ?>"                                               id="sitedoctors-addresses-0-address" class="form-control"                                               name="SiteDoctors[workplaces_list][0][address]">                                        <span class="input-group-btn"><button type="button"                                                                              class="btn btn-primary inputAdd"                                                                              data-t="4"                                                                              data-c="<?= $count_addresses ?>">+</button></span>                                        <div class="help-block"></div>                                    </div>                                    <?PHP                                    if (!empty($addresses)) {                                        $AddedAddresses = [];                                        if (isset($addresses[0])) {                                            $AddedAddresses[0]['id'] = $addresses[0]['id'];                                            $AddedAddresses[0]['name'] = $addresses[0]['name'];                                            $AddedAddresses[0]['address'] = $addresses[0]['address'];                                            unset($addresses[0]);                                        }                                        foreach ($addresses as $key => $val) {                                            echo '<div class="input-group">';                                            echo "<input type=\"text\" id=\"sitedoctors-new_workplace\" value=\"{$val['name']}\" class=\"form-control\" name=\"SiteDoctors[workplaces_list][$key][name]\">";                                            echo "<input type=\"text\" id=\"sitedoctors-new_workplace\" value=\"{$val['address']}\" class=\"form-control\" name=\"SiteDoctors[workplaces_list][$key][address]\">";                                            echo '<span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span>';                                            echo '</div>';//                                            echo '<div class="input-group">';//                                            echo '</div>';                                            $AddedAddresses[$key]['id'] = $val['id'];                                            $AddedAddresses[$key]['name'] = $val['address'];                                        }                                        $AddedAddresses = base64_encode(json_encode($AddedAddresses));                                        echo '<input name="SiteDoctors[added_addresses]" type="hidden" value="' . $AddedAddresses . '">';                                    }                                    ?>                                </div>                            </div>                            <?PHP                        }                        ?>                        <?= $form->field($model, 'files', $template)->fileInput(['multiple' => true]) ?>                        <?PHP                            $mainIimage = $model->photo;                            if (!empty($mainIimage)) {                                echo '<div class="row">';                        ?>                                <div class="col-md-55 item">                                    <div class="thumbnail">                                        <div class="image view view-first">                                            <img style="width: 100%; display: block;" src="<?= Functions::getUploadUrl().$customPath.'/small/'.$mainIimage ?>" alt="image">                                        </div>                                        <div class="caption">                                            <div data-id="<?= $model->id?>" class="btn btn-danger btn-xs deleteImage">Faylı                                                Sil                                            </div>                                        </div>                                    </div>                                </div>                        <?PHP                                echo '</div>';                                //echo '<input type="hidden" name="SiteDoctors[mainImage]" value="'.$mainIimage.'">';                                echo '<input type="hidden" name="SiteDoctors[deletedImages]" class="deletedImages">';                            }                        ?>                        <?= $form->field($model, 'dp_files[]', $template)->fileInput(['multiple' => true]) ?>                        <?PHP                            $dipomas = SiteDoctorFilesModel::find()->where(['connect_id' => $model->id, 'type' => 1])->all();                            if (!empty($dipomas)) {                                echo '<div class="row">';                                foreach ($dipomas as $key => $val) {                        ?>                                    <div class="col-md-55 item">                                        <div class="thumbnail">                                            <div class="image view view-first">                                                <img style="width: 100%; display: block;" src="<?= Functions::getUploadUrl() . $customPath . '/small/' . $val['file_photo'] ?>" alt="image">                                            </div>                                            <div class="caption">                                                <div data-id="<?= $val['id'] ?>" class="btn btn-danger btn-xs deleteImage">Faylı                                                    Sil                                                </div>                                            </div>                                        </div>                                    </div>                        <?PHP                                }                                echo '</div>';                                //echo '<input type="hidden" name="SiteDoctors[mainDiploma]" value="' . $mainIimage . '">';                                echo '<input type="hidden" name="SiteDoctors[deletedDiplomas]" class="deletedImages">';                            }                        ?>                        <?= $form->field($model, 'ct_files[]', $template)->fileInput(['multiple' => true]) ?>                        <?PHP                            $certificates = SiteDoctorFilesModel::find()->where(['connect_id' => $model->id, 'type' => 2])->all();                            if (!empty($certificates)) {                                echo '<div class="row">';                                foreach ($certificates as $key => $val) {                        ?>                                <div class="col-md-55 item">                                    <div class="thumbnail">                                        <div class="image view view-first">                                            <img style="width: 100%; display: block;" src="<?= Functions::getUploadUrl() . $customPath . '/small/' . $val['file_photo'] ?>" alt="image">                                        </div>                                        <div class="caption">                                            <div data-id="<?= $val['id'] ?>" class="btn btn-danger btn-xs deleteImage">Faylı                                                Sil                                            </div>                                        </div>                                    </div>                                </div>                                <?PHP                            }                            echo '</div>';                            //echo '<input type="hidden" name="SiteDoctors[mainCertificate]" value="' . $mainIimage . '">';                            echo '<input type="hidden" name="SiteDoctors[deletedCertificates]" class="deletedImages">';                        }                        ?>                        <?= $form->field($model, 'about')->textarea(['class' => 'ckeditor']) ?>                        <?PHP                        if ($model->feature == 1) {                            $model->home_doctor = 1;                        } elseif ($model->feature == 2) {                            $model->child_doctor = 1;                        } elseif ($model->feature == 3) {                            $model->home_doctor = 1;                            $model->child_doctor = 1;                        }                        ?>                        <?= $form->field($model, 'home_doctor', $template)->checkbox() ?>                        <?= $form->field($model, 'child_doctor', $template)->checkbox() ?>                        <div class="form-group">                            <?PHP                            $buttonName = $model->isNewRecord ? 'Əlavə et' : 'Düzəliş et';                            echo Html::submitButton($buttonName, ['class' => 'btn btn-success']);                            echo Html::a('Sıfırla', [Yii::$app->controller->action->id, 'id' => $model->id], ['class' => 'btn btn-primary']);                            ?>                        </div>                        <?php ActiveForm::end(); ?>                    </div>                            </div>                        </div>                    </div>                </div>            </div>        </div>    </div></div><?PHP$scripts = <<< JS    var l = 5;     $('.inputAdd').click(function(){        var c = $(this).attr('data-c') ? $(this).attr('data-c') : 0;        var t = $(this).attr('data-t') ? $(this).attr('data-t') : 1;        //console.log(t);        if(c<l){            var step = Number(c)+1;            if(t == 1)            {                $(this).parents('.col-md-4').append('<select class="miniSelectBox" name="SiteDoctors[sosial_links]['+step+'][type]">{$linkOptions}</select><div class="input-group"><input type="text" id="sitedoctors-sosial_links" class="form-control" name="SiteDoctors[sosial_links]['+step+'][link]"><span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span></div>');            }else if(t == 2){                $(this).parents('.col-md-4').append('<select class="miniSelectBox" name="SiteDoctors[phone_numbers]['+step+'][type]">{$numberOptions}</select><div class="input-group"><input type="text" id="sitedoctors-phone_numbers" class="form-control" name="SiteDoctors[phone_numbers]['+step+'][number]"><span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span></div');            }            else if(t == 4){                $(this).parents('.col-md-4').append('<div class="input-group"><input type="text" placeholder="Ad" id="sitedoctors-new_workplace" class="form-control" name="SiteDoctors[workplaces_list]['+step+'][name]"><span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span></div> <div class="input-group"><input type="text" placeholder="Ünvan" id="sitedoctors-new_workplace" class="form-control" name="SiteDoctors[workplaces_list]['+step+'][address]"><span class="input-group-btn"><div class="btn btn-none inputAdd">+</div></span></div></div>');            }            c++;            $(this).attr('data-c',c);        }    });JS;$this->registerJsFile("@web/vendor/ckeditor4/ckeditor.js", ['depends' => [AppAsset::className()]]);$this->registerJs("        $(document).on(\"click\", \".deleteImage\", function (e) {        var result = confirm(\"Faylı silmək istədiyinizdən əminsinizmi?\");        if(result)        {            getPhoto = $(this).attr('data-id');            if(getPhoto!='')            {                getDeletedImages = $('.deletedImages').val();                deletedImages = getDeletedImages != '' ? getDeletedImages+','+getPhoto : getPhoto;                $('.deletedImages').val(deletedImages);                $('.imageUpload').removeClass('hidden');                $(this).parent('.item').remove();            }        }        e.preventDefault();     });    $scripts ");?>