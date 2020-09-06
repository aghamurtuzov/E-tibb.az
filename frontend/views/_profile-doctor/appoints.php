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

$this->title = 'Randevu';
$request = Yii::$app->request;
$model = new MainModel();
$getMenus = ArrayHelper::toArray(new Menu());
$data['menus']  = $getMenus['list'];
/*if(!Yii::$app->request->isPost){
    $time_list = Functions::exp_time(30);
}*/
?>

<div class="doc-log">
    <div class="row">
        <div class="col-12">
            <?= $this->render('_tabs', ['model' => $doctor,"pages" => $pages,"page_type" => $page_type]) ?>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                     aria-labelledby="nav-home-tab">
                    <div class="head-campaigns">
                        <?php
                            if($counter>0){
                                echo '<h6>Sizin təsdiq edilməmiş '.$counter.' randevunuz var</h6>';
                            }else if($counter==0){
                                echo '<h6>Sizin təsdiq edilməmiş randevunuz yoxdur</h6>';
                            }else if(!empty($counter)){
                                echo '<h6>Sizin təsdiq edilməmiş randevunuz yoxdur</h6>';
                            }
                        ?>
                        <div class="hint_info">
                        </div>
                    </div>
                        <?php
                            if($reserv_times){
                                foreach ($reserv_times as $reserv){
                        ?>
                        <div class="qebul t-card mini3 -h-top">
                            <div class="list">
                                <div>
                                    <ul class="first-list">
                                        <li><?=$reserv['fname'];?></li>
                                        <li><?=$reserv['telefon'];?></li>
                                    </ul>
                                </div>
                                <div>
                                    <ul class="sec-list">
                                        <?php
                                            if(!empty($reserv['email'])) echo '<li>'.$reserv['email'].'</li>';
                                            else echo '<li>Yoxdur</li>'
                                        ?>
                                        <li><?=$reserv['time'];?></li>
                                    </ul>
                                </div>
                                <div>
                                    <ul class="third-list">
                                        <li><?=$reserv['date'];?></li>
                                    </ul>
                                </div>
                                <div>
                                    <ul>
                                        <li>
                                            <?php if($reserv['status']!=1){ ?>
                                            <button type="button" class="cb shadow cyan d-inline-block accept" data-id="<?=$reserv['id'];?>">Təsdiq et</button>
                                            <?php }?>
                                            <button type="button" class="cb shadow d-inline-block pink delete" data-id="<?=$reserv['id'];?>">Sil</button>
                                        </li>
                                    </ul>
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