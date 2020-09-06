<?php

use yii\helpers\Url;
use backend\components\Functions;
use \yii\bootstrap\ActiveForm;

?>

<section class="pharmacy stocks">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="index.html">Ana səhifə </a></li>
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
                    <div class="col-md-9 pharmacy-left">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block-back">
                                    <div class="form">
                                        <div class="form-list">
                                            <form method="get" action="">
                                                <div class="input-group">
                                                    <label>
                                                        <input type="text" class="form-control" name="name" placeholder="Aptek adı">
                                                    </label>
                                                </div>
                                                <select class="form-control selectpicker" name="city" title="Şəhər">
                                                    <option value="1">Baki</option>
                                                    <option value="2">Gence</option>
                                                    <option value="3">Qax</option>
                                                    <option value="4">Qazax</option>
                                                </select>
                                                <select class="form-control selectpicker" name="village" title="Qəsəbə">
                                                    <option value="allerqoloq-29">Allerqoloq</option>
                                                    <option value="androloq-9">Androloq</option>
                                                    <option value="anestezioloq-47">Anestezioloq</option>
                                                    <option value="dermatoloq-23">Dermatoloq</option>
                                                    <option value="dietoloq-43">Dietoloq</option>
                                                </select>
                                                <label class="check-button">
                                                    <span class="check-btn-text">24 saat</span>
                                                    <input name="hours" type="checkbox" >
                                                    <span class="checkmark"></span>
                                                </label>
                                                <button type="submit" class="btn btn-effect right-item">Axtarış et</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="block-back">
                                    <h4 class="heading-inner">PREMİUM APTEKLƏR</h4>
                                    <a href="" class="btn transparent btn-bottom">Bütün Premium apteklər</a>
                                </div>
                            </div>
                            <div class="block">
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
                                <div class="col-md-4">
                                    <a href="<?= $link ?>">
                                        <div class="block-cover">
                                            <div class="img-cover">
                                                <img src="<?= $enterprise['photo'] ? Functions::getUploadUrl() . Yii::$app->params['path.enterprises'] . '/small/' . $enterprise['photo'] : '/' . Yii::$app->params['site.defaultThumb'] ?>" class="img-responsive center-block" alt="pharmacy">
                                            </div>
                                            <div class="information text-center">
                                                <p class="info-name"><?= $enterprise["name"] ?></p>
                                                <p class="info-major"><?= $enterprise["address"] ?></p>
                                                <p class="rate">
                                                    <span class="rating">
                                                        <i class="fa fa-star active" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
                                                </p>
                                                <?php if ($catdirilma) { ?>
                                                    <h6>Çağırış <img src="/assets/img/emergency.png" alt="emergency"></h6>
                                                <?php }
                                                if ($saat24) { ?>
                                                    <h6>24 saat <span><i class="fa fa-clock-o" aria-hidden="true"></i></span></h6>
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
                            <div class="col-xs-12 text-center">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        <?= \yii\widgets\LinkPager::widget([
                                            'pagination' => $pages,
                                            'maxButtonCount' => 7,
                                            'firstPageLabel' => false,
                                            'lastPageLabel' => false,
                                            'prevPageLabel' => '<span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i> Əvvəl</span>',
                                            'nextPageLabel' => '<span aria-hidden="true">Sonra<i class="fa fa-angle-right" aria-hidden="true"></i></span>',

                                        ]); ?>
                                    </ul>
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