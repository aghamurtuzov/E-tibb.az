<?php
use backend\components\Functions;
use yii\helpers\Url;

$this->title = 'Site Ads';
$this->params['breadcrumbs'][] = $this->title;

?>

<section class="donate stocks">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?=Yii::$app->params['site.url']?>">Ana səhifə </a></li>
                        <li class="active">Qan ver</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-9 donate-right">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block-back blood_search_container">
                                    <form action="<?=Yii::$app->request->url?>" id="blood_search">
                                        <div class="form">
                                            <div class="form-list">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="blood_search">
                                                            <div class="select-part">
                                                                <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Açar söz" value="<?=$dataSearch['keyword']?>" />
                                                            </div>
                                                            <div class="select-part">
                                                                <select class="form-control selectpicker" name="blood_type" title="Qan qrupu" style="width: 100%">
                                                                    <?PHP
                                                                    foreach ($select as $key => $val){
                                                                        if(intval($val) > 0 || $val<0) {
                                                                            $selected = ($dataSearch['blood_type']==$val) ? "selected" : "";
                                                                            echo '<option '.$selected.' value="'.$val.'">'.$val.'</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                            <div class="">
                                                                <button type="submit" class="btn btn-effect">Axtarış et</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php if(Yii::$app->session->hasFlash("success")){
                                    echo '<div class="alert alert-success">'.Yii::$app->session->getFlash("success").'</div>';
                                }?>
                                <!--<div class="block-back">
                                    <div class="form">
                                        <div class="form-list row text-center">
                                            <select class="form-control selectpicker" name="money" title="Qan qurupu">
                                                <option value="allerqoloq-29">Allerqoloq</option>
                                                <option value="androloq-9">Androloq</option>
                                                <option value="anestezioloq-47">Anestezioloq</option>
                                                <option value="dermatoloq-23">Dermatoloq</option>
                                                <option value="dietoloq-43">Dietoloq</option>
                                            </select>
                                            <select class="form-control selectpicker" name="money" title="Şəhər">
                                                <option value="allerqoloq-29">Allerqoloq</option>
                                                <option value="androloq-9">Androloq</option>
                                                <option value="anestezioloq-47">Anestezioloq</option>
                                                <option value="dermatoloq-23">Dermatoloq</option>
                                                <option value="dietoloq-43">Dietoloq</option>
                                            </select>
                                            <select class="form-control selectpicker" name="money" title="Rayon">
                                                <option value="allerqoloq-29">Allerqoloq</option>
                                                <option value="androloq-9">Androloq</option>
                                                <option value="anestezioloq-47">Anestezioloq</option>
                                                <option value="dermatoloq-23">Dermatoloq</option>
                                                <option value="dietoloq-43">Dietoloq</option>
                                            </select>
                                            <button type="submit" class="btn btn-effect">Axtarış et</button>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                            <div class="col-md-12">
                                <div class="block-back add-advertisement">
                                    <h4 class="heading-inner">QAN VER</h4>
                                    <button type="submit" class="btn-all" onclick="location.href='<?=Yii::$app->params['site.url']?>qan-elani-ver';">Elan yerləşdir</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="block-back donate-questions">
                                    <div class="donate-questions-inn">
                                        <a href="<?=Yii::$app->params['site.url']?>sizə-tecili-qan-lazımdır"><p>Sizə təcili qan lazımdır?</p></a>
                                        <a href="<?=Yii::$app->params['site.url']?>donor-olmaq-isteyirsiniz"><p>Donor olmaq istəyirsiniz?</p></a>
                                        <a href="<?=Yii::$app->params['site.url']?>donor-olmaq-ucun-ne-etmeli"><p>Donor olmaq üçün nə etməli?</p></a>
                                    </div>

                                </div>
                            </div>
                            <div class="block">
                                <?php
                                foreach ($data as $item) { ?>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <a href="<?= Yii::$app->params['site.url'] ."qanver/". $item['slug'] . '-' . $item['id'] ?>">
                                            <div class="block-cover">
                                                <div class="img-cover img-blood-cover image-thumbnail">
                                                    <img src="<?=Yii::$app->params['site.url']?>upload/ads/small/qanver-1.png" class="img-responsive center-block" alt="donate1">
                                                </div>
                                                <div class="information text-center">
                                                    <p class="info-name"><?=$item['title']?></p>
<!--                                                    <p class="info-major">--><?//=substr($item['text'],0,50)?><!--</p>-->
                                                    <span class="date-gray"><i class="fa fa-calendar" aria-hidden="true"></i> <?= Functions::getDatetime($item['created_at'], ['type' => 'date']) ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php }
                                ?>
                            </div>
                            <div class="col-xs-12 text-center">
                                <nav aria-label="Page navigation">
                                    <?= \yii\widgets\LinkPager::widget([
                                        'pagination' => $pages,
                                        'maxButtonCount' => 7,
                                        'firstPageLabel' => false,
                                        'lastPageLabel' => false,
                                        'prevPageLabel' => '<span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i> Əvvəl</span>',
                                        'nextPageLabel' => '<span aria-hidden="true">Sonra<i class="fa fa-angle-right" aria-hidden="true"></i></span>',

                                    ]); ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <?=$this->render('/layouts/partials/sidebar_new'); ?>
                </div>
            </div>
        </div>
    </div>
</section>