<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use \frontend\models\SiteEnterpriseEmployers;
use backend\components\Functions;

$form = ActiveForm::begin(['enableClientScript' => false,
    'options' => [
        'enctype' => 'multipart/form-data',
    ]]);
$this->title = 'Obyekt';

?>

<div class="doc-log">
    <div class="row">
        <div class="col-12">
            <?= $this->render('_tabs', ['model' => $enterprise, "pages" => $pages, "page_type" => $page_type]) ?>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active tab-pad" id="nav-profile" role="tabpanel"
                     aria-labelledby="nav-profile-tab">
                    <div class="t-card mini3 -h-top">
                        <div class="head-campaigns">
                            <h6>Mütəxəsİs əlavə et</h6>
                            <div class="hint_info">
                                <?php
                                if (Yii::$app->session->hasFlash("success")) {
                                    echo '<br /><div class="alert alert-success">' . Yii::$app->session->getFlash("success") . '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="campaign-inner">
                            <?php
                            $form = ActiveForm::begin(['enableClientScript' => false,
                                'options' => [
                                    'enctype' => 'multipart/form-data',
                                ]]);
                            ?>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group custom-group">
                                        <?= $form->field($model, 'name')->textInput(['class' => 'form-control', 'placeholder' => 'Ad və Soyadı'])->label('Ad, Soyad') ?>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group date custom-group" data-provide="datepicker"
                                         id="datepicker_experience">
                                        <?= $form->field($model, 'experience')->textInput(["class" => "form-control", "placeholder" => "", 'value' => $model->experience]) ?>
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
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group custom-group">
                                        <?= $form->field($model, 'specialty')->textInput(['class' => 'form-control', 'placeholder' => 'İxtisası'])->label('İxtisas') ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group custom-group">
                                        <?PHP
                                        echo $form->field($model, 'study')->dropDownList(SiteEnterpriseEmployers::get_Study(), [
                                            'select2' => '',
                                            'tabindex' => '-1',
                                            'class' => 'w-100 select2-hidden-accessible',
                                            'data-placeholder' => 'Elmi dərəcəsi',
                                            'minimumresultsforsearch' => '-1',
                                            'data-select2-id' => '4',
                                            'aria-hidden' => 'true',
                                            'options' => [0 => ['selected' => 'selected']]
                                        ]);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 end-block">
                                    <div class="form-group upload">
                                        <?= $form->field($model, 'photo')->fileInput(['class' => 'form-control-file file-upload'])->label('Şəkil yüklə') ?>
                                    </div>
                                    <?PHP
                                    $buttonName = $model->isNewRecord ? 'Əlavə et' : 'Düzəliş et';
                                    echo Html::submitButton($buttonName, ['class' => 'cb orange orange-hover shadow']);
                                    ?>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <img class="img-fluid profile-image" src="<?= $model->photo !=null ? Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/'.$model->photo : Yii::$app->params['site.defaultThumbDoctor']?>">
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    <?php
                    if (!empty($employers) and isset($employers)) {
                        echo '<div class="row">';
                        foreach ($employers as $key => $e) {
                            //echo $e['photo']; exit();
                            $photo = !empty($e['photo']) ? Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/small/'.$e['photo'] : Yii::$app->params['site.defaultThumbDoctor'];
                            ?>
                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="t-card mini3 -h-top">
                                    <div class="profile-tab">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-12">
                                                <img class="img-fluid" src="<?= $photo; ?>"
                                                     alt="your image">
                                            </div>
                                            <div class="right-text col-md-8 col-sm-8 col-xs-12">
                                                <ul class="doc-info">
                                                    <li><?=SiteEnterpriseEmployers::get_Study()[$e['study']]?></li>
                                                    <li><?= $e['name']; ?></li>
                                                    <li><?= $e['specs']; ?></li>
                                                    <li><?= $e['exp']; ?></li>
                                                </ul>
                                            </div>
                                            <div class="col-12 text-right worker-end">
                                                <a href="<?php echo Url::base() . '/profil/mutexessis-redakte/' . $e['id']; ?>"
                                                   class="cb shadow cyan d-inline-block edit">Düzəliş et</a>
                                                <button type="button" class="cb shadow d-inline-block pink delete"
                                                        data-id="<?= $e['id']; ?>">Sil
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        echo '</div>';
                    }
                    ?>
                    <div class="row -h-top-2">
                        <div class="text-center">
                            <div class="t-pagination">
                                <?= \yii\widgets\LinkPager::widget([
                                    'pagination' => $pagination,
                                    'maxButtonCount' => 7,
                                    'firstPageLabel' => false,
                                    'lastPageLabel' => false,
                                    'prevPageLabel' => '<img src="assets/img/arrow-left.png"> Əvvəl',
                                    'nextPageLabel' => 'Sonra <img src="assets/img/arrow-right.png">',
                                ]); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>