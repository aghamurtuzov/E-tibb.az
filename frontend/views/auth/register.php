<?php
use \yii\widgets\ActiveForm;
?>

<section class="user-signin donate-inner about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="/">Ana səhifə </a></li>
                        <li class="active"> İstifadəçi qeydiyyatı</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="block-back">
            <div class="row user-signin-body">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>İSTİFADƏÇİ  QEYDİYYATI</h3>
                        </div>
                    </div>
                    <?php
                    $form = ActiveForm::begin(['enableClientScript' => false,
                        'options'=>[
                            'enctype' => 'multipart/form-data',
                            'class'   => 'doc-log'
                        ],
                        'fieldConfig' => [
                            'template' => "{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div>",
                            'labelOptions' => [ 'class' => '' ],
                            'options'=>[
                                'class'=>'form-group'
                            ],
                        ],
                    ]);
                    ?>
                    <div class="form">
                        <div class="form-list">
                            <h4>Növü</h4>
                            <div class="form-group">
                                <select class="selectpicker form-control" title="Növ seçin"  onchange="location = this.value;">
                                    <option value="<?= 'qeydiyyat'?>" selected>İstifadəçi</option>
                                    <option value="<?= 'hekim-qeydiyyat'?>">Həkim</option>
                                    <?php
                                    if(isset($enterprise_categories))
                                    {
                                        foreach ($enterprise_categories as $category){
                                            echo '<option value="obyekt-qeydiyyat/'.$category["id"].'">'.$category["name"].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <h4>Ad, Soyad *</h4>
                            <div class="input-group">
                                <label>
                                    <?= $form->field($model, 'name',$template)->textInput(['class'=>"form-control",'placeholder'=>'Ad, Soyad *'])->label(false) ?>
                                </label>
                            </div>
                            <h4>Mobil telefon *</h4>
                            <div class="input-group">
                                <label>
                                    <?= $form->field($model, 'phone_number',$template)->textInput(['class'=>"form-control",'placeholder'=>'Mobil telefon nömrəniz'])->label(false) ?>
                                </label>
                            </div>
                            <h4>E-mail *</h4>
                            <div class="input-group">
                                <label>
                                    <?= $form->field($model, 'email',$template)->textInput(['class'=>"form-control",'placeholder'=>'İşlək e-mail ünvanınızı qeyd edin'])->label(false) ?>
                                </label>
                            </div>
                            <h4>Ünvan </h4>
                            <div class="input-group">
                                <label>
                                    <?= $form->field($model, 'address',$template)->textInput(['class'=>"form-control",'placeholder'=>'Hal-hazırda yaşadığınız ünvanı qeyd edin'])->label(false) ?>
                                </label>
                            </div>
                            <h4>Şifrə *</h4>
                            <div class="input-group">
                                <label>
                                    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Şifrə'])->label(false) ?>
                                </label>
                            </div>
                            <h4>Təkrar şifrə *</h4>
                            <div class="input-group">
                                <label>
                                    <?= $form->field($model, 'repassword')->passwordInput(['placeholder'=>'Şifrə Təkrarı'])->label(false) ?>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-effect">Qeydiyyatdan keç</button>
                        </div>
                    </div>
                    <?php
                    ActiveForm::end();
                    ?>
                </div>
                <div class="col-md-8">
                    <img src="/assets/img/user-signin.png" alt="sign-in-user" class="center-block">
                </div>
            </div>
        </div>
    </div>
</section>
