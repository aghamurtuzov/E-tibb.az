<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;

?>
    <?php
    $form = ActiveForm::begin(['enableClientScript' => false,
        'action' => ['auth/register2'],

        'options'=>[
            'enctype' => 'multipart/form-data',
            "id" => "register_form_modal"
        ]]);
    $user_types = $model->user_types;
    ?>
    <div class="form-group custom-group">
        <?php
        echo $form->field($model, 'type')->dropDownList($user_types,["select2" => "select2","minimumResultsForSearch" => "-1","class" => "w-100","placeholder" => "Seçin"]);
        ?>
        <script>
            if ($("[select2]").length) {
                $("[select2]").each(function () {
                    const self = this;
                    $(this).select2({
                        minimumResultsForSearch: $(self).attr("minimumResultsForSearch"),
                        placeholder: $(self).attr("data-placeholder"),
                        theme: ($(self).attr("data-icon")) ? 'default with-icon' : 'default',
                    });
                    $(this).next("span").find(".select2-selection").css('background-image', 'url(' + $(self).attr("data-icon") + ')');
                });
            }
        </script>
    </div>
    <div class="form-group custom-group">
        <?= $form->field($model, 'name')->textInput(['placeholder'=>'Ad, Soyad']) ?>
    </div>
    <div class="form-group custom-group">
        <?= $form->field($model, 'phone_number')->textInput(['placeholder'=>'Mobil Telefon']) ?>
    </div>
    <div class="form-group custom-group">
        <?= $form->field($model, 'email')->textInput(['placeholder'=>'Email']) ?>
    </div>

    <div class="form-group custom-group">
        <?= $form->field($model, 'pass')->passwordInput(['placeholder'=>'Şifrə']) ?>
    </div>

    <div class="form-group custom-group">
        <?= $form->field($model, 'repassword')->passwordInput(['placeholder'=>'Şifrə Təkrarı']) ?>
    </div>
    <div class="clearfix"></div>
    <div class="form-group custom-group -h-top">

    </div>
    <?php
    ActiveForm::end();
    ?>

