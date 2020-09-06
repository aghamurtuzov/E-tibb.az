<?php

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

$this->title = 'Qəbul Günləri';
$request = Yii::$app->request;
$model = new MainModel();
$getMenus = ArrayHelper::toArray(new Menu());
$data['menus']  = $getMenus['list'];
$time_cat       = [30 => '30 dəqiqə', 45 => '45 dəqiqə', 60 => '1 saat'];
$time_list      = Functions::exp_time(30); //array();
/*if(!Yii::$app->request->isPost){
    $time_list = Functions::exp_time(30);
}*/
?>

<div class="doc-log">
    <div class="row">
        <div class="col-12">
            <?= $this->render('_tabs', ['model' => $doctor,"pages" => $pages,"page_type" => $page_type]) ?>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="t-card mini3 -h-top">
                        <h6>Yenİ gün əlavə et</h6>
                        <div class="hint_info">
                            <?= Functions::getFlash("danger","danger")?>
                            <?= Functions::getFlash("success","success")?>
                        </div>
                            <?php
                            $form = ActiveForm::begin(['enableClientScript' => false,
                                'options'=>[
                                    'enctype' => 'multipart/form-data',
                                ]]);
                        ?>
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-12">
                                    <div class="input-daterange custom-group">
                                        <input type="text" name="date" class="form-control" placeholder="Tarix">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-12">
                                    <div class="custom-group search-box">
                                        <select class="form-control selectpicker" onchange="time_select(this.value)"
                                                name="time_interval" id="profession">
                                            <?php
                                            foreach ($time_cat as $key => $item) {
                                                ?>
                                                <option value="<?= $key; ?>"> <?= $item; ?> </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-12">
                                    <div class="custom-group" id="worktime">
                                        <select name="times[]" class="form-control  multi-select-demo" id="ls-profession" multiple="multiple">
                                            <?php
                                            foreach ($time_list as $key => $item) {
                                                ?>
                                                <option value="<?= $item; ?>"> <?= $item; ?> </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-3 col-12">
                                    <button type="submit" class="cb cyan d-inline-block">Əlavə et</button>
                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                    <?php
                    if($workdays_data){
                        foreach ($workdays_data as $val) {
                            ?>
                            <div class="t-card mini3 -h-top">
                                <div class="row workdays">
                                    <div class="col-md-3 col-sm-3 col-12">
                                        <div class="input-daterange custom-group">
                                            <input type="text" name="date" value="<?=date('d/m/Y',strtotime($val['date']))?>" disabled class="form-control" placeholder="Tarix">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-12">
                                        <div class="custom-group" id="time-interval1">
                                            <div class="custom-group search-box">
                                                <select class="form-control selectpicker" onchange="time_select(this.value)"
                                                        name="time_interval" id="profession" disabled>
                                                    <?php
                                                    foreach ($time_cat as $key => $item) {
                                                        ?>
                                                        <option value="<?= $key; ?>" <?php if($val['time_interval']==$key) echo 'selected'; ?>> <?= $item; ?> </option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-12">
                                        <div class="custom-group" id="worktime1">
                                            <select class="form-control multi-select-demo" name="profession"
                                                    multiple="multiple">
                                                <?php
                                                $int_val = $val['time_interval'];
                                                $tm_list = Functions::exp_time($int_val);
                                                //print_r($tm_list);
                                                foreach ($tm_list as $key => $item) {
                                                    ?>
                                                    <option value="<?= $item; ?>" <?php if(in_array($item,$val['workdays'])) echo 'selected'; ?>> <?= $item; ?> </option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-12 added">
                                        <button class="cb cyan d-inline-block edit" data-id="<?=$val['id'];?>">Düzəliş</button>
                                        <button class="cb cyan d-inline-block delete pink" data-id="<?=$val['id'];?>">Sil</button>
                                    </div>
                                </div>
                            </div>
                        <?php } }?>
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