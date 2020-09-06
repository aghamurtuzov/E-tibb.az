<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use backend\components\Functions;
$this->title = 'Kompaniyalar';
$photo = !empty($model->photo) ? Functions::getUploadUrl().'promotions'.'/small/'.$model->photo : '';

?>

<div  class="doc-log">
    <div class="row">
        <div class="col-12">
            <?php
                if($typeModel){
                   echo  $this->render($tabs, ['model' => $typeModel,"pages" => $pages,"page_type" => $page_type]);
                }
            ?>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane tab-pad active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="t-card mini3 -h-top">
                        <div class="head-campaigns">
                            <h6>Yenİ Kampanİya əlavə et</h6>
                            <div class="hint_info">
                                <?php
                                    if(Yii::$app->session->hasFlash("success")){
                                        echo '<br /><div class="alert alert-success">'.Yii::$app->session->getFlash("success").'</div>';
                                    }else if(Yii::$app->session->hasFlash("danger")){
                                        echo '<br /><div class="alert alert-danger">'.Yii::$app->session->getFlash("danger").'</div>';
                                    }
                                ?>
                            </div>
                        </div>
                        <?php
                            $form = ActiveForm::begin([
                                'enableClientScript' => false,
                                'options'=>[
                                    'enctype' => 'multipart/form-data',
                                ]]);
                        ?>
                            <div class="campaign-inner">
                            <div class="row">

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group custom-group">
                                        <?= $form->field($model, 'headline')->textInput(['class'=>'form-control', 'placeholder'=>'Kampaniyanın başlığı' ]) ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group custom-group">
                                        <?= $form->field($model, 'price')->textInput(['type'=>'number','class'=>'form-control', 'placeholder'=>'Qiymət' ]) ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group custom-group">
                                        <?= $form->field($model, 'discount')->textInput(['type'=>'number','class'=>'form-control', 'placeholder'=>'Endirim faizi']) ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group date custom-group">
                                        <?= $form->field($model, 'date_start')->textInput(['class'=>'form-control datepicker','id'=>'datepic', 'placeholder'=>'- - -']) ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="form-group date custom-group">
                                        <?= $form->field($model, 'date_end')->textInput(['class'=>'form-control datepicker','id'=>'datepic', 'placeholder'=>'- - -']) ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12" style="margin-top: 5px">
                                    <div class="form-group custom-group">
                                        <?= $form->field($model, 'promo_type')->dropDownList($model->getPromoTypes(),['class'=>'w-100 promo_type_select','minimumResultsForSearch'=>"-1",'data-placeholder'=>"Promo kod tipi","select2"=>"select2"]); ?>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 after_limit" style="display: <?=$model['max_user'] > 0 ? 'block' : 'none'?>">
                                    <div class="form-group custom-group">
                                        <?= $form->field($model, 'max_user')->textInput(['type'=>'number','class'=>'form-control', 'placeholder'=>'Maksimum istifadəçi sayı' ]) ?>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="custom-group">
                                        <?= $form->field($model, 'content')->textarea(['class'=>'form-control','placeholder'=>'Kampaniya haqqında']) ?>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 end-block">
                                    <div class="form-group upload">
                                        <?= $form->field($model, 'photo')->fileInput(['class'=>'form-control-file file-upload'])?>
                                    </div>
                                    <?PHP
                                        if($model->isNewRecord)
                                            echo '<button type="submit" class="cb orange orange-hover shadow">Əlavə et</button>';
                                        else
                                            echo '<button type="submit" class="cb orange orange-hover shadow">Redaktə et</button>';
                                    ?>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <img class="img-fluid profile-image" src="<?=$photo?>">
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <?php if (!empty($promotions) and isset($promotions)){
                        $counter = 0;
                        foreach ($promotions as $key=>$pr){
                            $counter++;
                        ?>
                    <div class="t-card mini -h-top">
                        <div class="align-items-center discount-block">
                            <div class="discount-block-inner border-bottom">
                                <div class="row">
                                    <div class="col-md-1 d-none d-lg-block">
                                        <p class="counter">
                                            <?=$counter;?>
                                        </p>
                                    </div>
                                    <div class="col-md col-8">
                                        <div class="detail">
                                            <p class="name"><?=$pr['headline'];?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-auto col-4 discount-right text-right">
                                        <?php
                                            if (!empty($pr['price2']) and !empty($pr['discount'])){
                                        ?>
                                        <div class="price">
                                            <span class="d-block dc price-blue pr-l bold"><?=$pr['price2']?><sup class="jis">M</sup></span>
                                            <span class="d-block dc gray-color n-tshadow pr-s margin-clear"><?=$pr['price']?><sup class="jis">M</sup></span>
                                        </div>
                                        <?php
                                            }else if(!empty($pr['price2'])){
                                                echo '<div class="price">';
                                                echo '<span class="d-block dc price-blue pr-l bold">'.$pr['price'].'<sup class="jis">M</sup></span>';
                                                //echo '<span class="d-block dc gray-color n-tshadow pr-s margin-clear">'.$pr['price'].'<sup class="jis">M</sup></span>';
                                                echo'</div>';
                                            }
                                            ?>
                                        <?php
                                        if (!empty($pr['discount']) and $pr['discount']!=null){
                                            echo '<div class="ribbon">';
                                                echo '<p class="num">'.$pr['discount'].'</p><span class="percentage">%</span>';
                                                echo '<span class="text">endirim</span>';
                                            echo '</div>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="discount-block-end">
                                <p>Başlama vaxtı: <span class="time"><?=$pr['start'];?></span></p>
                                <p>Bitmə vaxtı: <span class="time"><?=$pr['end'];?></span></p>
                                <div class="button-list">
                                    <a href="<?php echo Url::base().'/profil/aksiyalar/redakte/'.$pr['id']; ?>" class="cb shadow cyan d-inline-block edit">Redaktə et</a>
                                    <button type="button" class="cb shadow d-inline-block pink delete" data-id="<?=$pr['id'];?>">Sil
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php } } ?>
                    <div class="row -h-top-2">
                        <div class="text-center">
                            <div class="t-pagination">
                                <?= \yii\widgets\LinkPager::widget([
                                    'pagination'=>$pagination,
                                    'maxButtonCount' => 7,
                                    'firstPageLabel' => false,
                                    'lastPageLabel' => false,
                                    'prevPageLabel' =>  '<img src="assets/img/arrow-left.png"> Əvvəl',
                                    'nextPageLabel' => 'Sonra <img src="assets/img/arrow-right.png">' ,
                                ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>