<?php

use yii\helpers\Url;
use backend\components\Functions;

?>

<section class="clinics doctors pharmacy">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?=Yii::$app->params['site.url']?>">Ana səhifə </a></li>
                        <li class="active"><?= $page_title ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block-back">
                                    <form action="<?=Yii::$app->request->url?>" id="enterprise_search">
                                        <div class="form">
                                            <div class="form-list">
                                                <div class="search_enterprise_container">
                                                    <div class="select-part">
                                                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Açar söz" value="<?=$keyword?>" />
                                                    </div>
                                                    <div>
                                                        <button type="submit" class="btn btn-effect">Axtarış et</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!--
                            <div class="col-md-12">
                                <?php if(!empty($premium)) { ?>
                                <div class="block-back">
                                    <h4 class="heading-inner">PREMİUM <?=mb_strtoupper($category['name'])?></h4>
                                    <a href="<?=Yii::$app->params['site.url']?>beledci-premium/<?=$category['link']."-".$category['id']?>" class="btn transparent btn-bottom">Bütün Premium <?=mb_strtolower($category['name'])?></a>
                                </div>
                                <?php } ?>
                            </div>
                            -->
                            <div class="block ">
                                <?php
                                /*
                                if (!empty($premium)) {
                                    foreach ($premium as $enterprise) {
                                        $saat24 = false;
                                        $catdirilma = false;
                                        $eve_cagiris = false;
                                        $features = json_decode($enterprise["feature"], true);


                                        $link = $page_slug . '/' . $enterprise["id"] . '-' . $enterprise["slug"];

                                        if (is_array($features) and count($features) > 0) {
                                            if (key_exists("saat24", $features)) {
                                                $saat24 = true;
                                            }

                                            if (key_exists("catdirilma", $features)) {
                                                $catdirilma = true;
                                            }

                                            if (key_exists("eve_caqiris", $features)) {
                                                $eve_cagiris = true;
                                            }
                                        }
                                        ?>

                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <a href="/<?= $link ?>">
                                                <div class="block-cover">
                                                    <div class="img-cover">
                                                        <img src="<?= $enterprise['photo'] ? Functions::getUploadUrl() . Yii::$app->params['path.enterprises'] . '/' . $enterprise['photo'] : Yii::$app->params['site.url'].Yii::$app->params['site.defaultThumb'] ?>"
                                                             alt="clinics1" class="img-responsive">
                                                    </div>
                                                    <div class="information text-center">
                                                        <p class="info-name"><?= $enterprise["name"] ?></p>
                                                        <p class="info-major"><?= $enterprise["address"] ?></p>
<!--                                                        <p class="rate">-->
<!--                                                            --><?php //if ($premium != false) { ?>
<!--                                                                <span class="rating"-->
<!--                                                                      data-rate="--><?//= intval($enterprise["rating"]) ?><!--">-->
<!--                                                                <i class="fa fa-star active" aria-hidden="true"></i>-->
<!--                                                                <i class="fa fa-star" aria-hidden="true"></i>-->
<!--                                                                <i class="fa fa-star" aria-hidden="true"></i>-->
<!--                                                                <i class="fa fa-star" aria-hidden="true"></i>-->
<!--                                                                <i class="fa fa-star" aria-hidden="true"></i>-->
<!--                                                            </span>-->
<!--                                                            --><?php //} ?>
<!--                                                        </p>-->

                                                        <?php if ($catdirilma) { ?>
                                                            <h6>Çağırış <img src="<?=Yii::$app->params['site.url']?>assets/img/emergency.png"
                                                                             alt="emergency"></h6>
                                                        <?php }
                                                        if ($saat24) { ?>
                                                            <h6>24 saat <span><i class="fa fa-clock-o"
                                                                                 aria-hidden="true"></i></span></h6>

                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <?php
                                    }
                                }
                                */
                                ?>
                                <div class="col-md-12">
                                    <div class="block-back">
                                        <h4 class="heading-inner"><?=str_replace("I","İ",mb_strtoupper($category['name'], 'UTF-8'))?></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="block flex-wrap">
                                <?php
                                if (!empty($enterprises)) {
                                    foreach ($enterprises as $enterprise) {
                                        $saat24 = false;
                                        $catdirilma = false;
                                        $eve_cagiris = false;
                                        $features = json_decode($enterprise["feature"], true);

                                        $link = $page_slug . '/' . $enterprise["id"] . '-' . $enterprise["slug"];

                                        if (is_array($features) and count($features) > 0) {
                                            if (key_exists("saat24", $features)) {
                                                $saat24 = true;
                                            }

                                            if (key_exists("catdirilma", $features)) {
                                                $catdirilma = true;
                                            }

                                            if (key_exists("eve_caqiris", $features)) {
                                                $eve_cagiris = true;
                                            }
                                        }
                                        ?>
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <a href="/<?= $link ?>">
                                                <div class="block-cover">
                                                    <div class="img-cover">
                                                        <img src="<?= $enterprise['photo'] ? Functions::getUploadUrl() . Yii::$app->params['path.enterprises'] . '/' . $enterprise['photo'] : Yii::$app->params['site.url'].Yii::$app->params['site.defaultThumb'] ?>" />
                                                    </div>
                                                    <div class="information text-center">
                                                        <p class="info-name"><?= $enterprise["name"] ?></p>
                                                        <p class="info-major"><?= $enterprise["address"] ?></p>
                                                        <!--                                                        <p class="rate">-->
                                                        <!--                                                            --><?php //if ($premium != false) { ?>
                                                        <!--                                                                <span class="rating"-->
                                                        <!--                                                                      data-rate="--><?//= intval($enterprise["rating"]) ?><!--">-->
                                                        <!--                                                                <i class="fa fa-star active" aria-hidden="true"></i>-->
                                                        <!--                                                                <i class="fa fa-star" aria-hidden="true"></i>-->
                                                        <!--                                                                <i class="fa fa-star" aria-hidden="true"></i>-->
                                                        <!--                                                                <i class="fa fa-star" aria-hidden="true"></i>-->
                                                        <!--                                                                <i class="fa fa-star" aria-hidden="true"></i>-->
                                                        <!--                                                            </span>-->
                                                        <!--                                                            --><?php //} ?>
                                                        <!--                                                        </p>-->

                                                        <?php if ($catdirilma) { ?>
                                                            <h6>Çağırış <img src="<?=Yii::$app->params['site.url']?>assets/img/emergency.png"
                                                                             alt="emergency"></h6>
                                                        <?php }
                                                        if ($saat24) { ?>
                                                            <h6>24 saat <span><i class="fa fa-clock-o"
                                                                                 aria-hidden="true"></i></span></h6>

                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                            <div class="col-xs-12 text-center web">
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
                            <div class="col-xs-12 text-center mobile">
                                <nav aria-label="Page navigation">

                                    <?= \yii\widgets\LinkPager::widget([
                                        'pagination' => $pages,
                                        'maxButtonCount' => 5,
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