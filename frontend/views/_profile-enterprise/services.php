<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\components\Functions;
use \frontend\models\SiteEnterpriseEmployers;
$this->title = 'Xidmətlər';
?>

<div class="doc-log">
    <div class="row">
        <div class="col-12">
            <?= $this->render('_tabs', ['model' => $model,"pages" => $pages,"page_type" => $page_type]) ?>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active tab-pad" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="t-card mini3 -h-top">
                        <?php if(Yii::$app->session->hasFlash("success")){
                            echo '<div class="alert alert-success">'.Yii::$app->session->getFlash("success").'</div>';
                        }?>
                        <div class="head-campaigns">
                            <h6>Xidmətlər əlavə et</h6>
                        </div>
                        <div class="inner">
                            <div class="form">
                                <?php
                                    $form = ActiveForm::begin(['enableClientScript' => false,
                                        'options'=>[
                                            'enctype' => 'multipart/form-data',
                                        ]]);
                                ?>
                                    <p>Xidmətlər</p>
                                    <?= $form->field($model, 'services_prices')->textarea(['cols'=>'30','rows'=>'10','class'=>'form-control','placeholder'=>'Xidmətləri daxil edin'])->label(false) ?>
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <?PHP
                                                $buttonName = $model->isNewRecord ? 'Əlavə et': 'Düzəliş et';
                                                echo Html::submitButton($buttonName, ['class' => 'cb orange orange-hover shadow']);
                                            ?>
                                        </div>
                                    </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>