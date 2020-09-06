<?php

use backend\components\Functions;

$this->title = $page_title;
?>

    <section class="stocks">
        <div class="head">
            <div class="container">
                <div class="row page-arrange">
                    <div class="col-xs-12 text-right">
                        <ol class="breadcrumb">
                            <li><a href="<?=Yii::$app->params['site.url']?>">Ana səhifə </a></li>
                            <li class="active"> <?= $breadcrumb; ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row promotions-list">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-9 stocks-left">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="block-back">
                                        <div class="form">
                                            <div class="row">
                                                <form action="" method="get" id="promotion_search">
<!--                                                    <div class="col-md-4">-->
<!--                                                        <select class="form-control selectpicker" name="clinic" title="Klinika">-->
<!--                                                            --><?php
//                                                            $clinic=0;
//                                                            if(isset($_GET['clinic']))
//                                                                $clinic=intval($_GET['clinic']);
//                                                            foreach ($enterprises as $item) {
//                                                               echo '<option '.($clinic==$item["id"] ? "selected" : "").' value="'.$item["id"].'">'.$item["name"].'</option>';
//                                                            }?>
<!--                                                        </select>-->
<!--                                                    </div>-->
<!--                                                    <div class="col-md-4">-->
<!--                                                        <select class="form-control selectpicker" name="doctor" title="Həkim">-->
<!--                                                            --><?php
//                                                            $doc=0;
//                                                            if(isset($_GET['doctor']))
//                                                                $clinic=intval($_GET['doctor']);
//                                                            foreach ($doctors as $item) {
//                                                                echo '<option '.($doc==$item["id"] ? "selected" : "").' value="'.$item["id"].'">'.$item["name"].'</option>';
//                                                            }?>
<!--                                                        </select>-->
<!--                                                    </div>-->
                                                    <div class="form-list">
                                                        <div class="search_enterprise_container">
                                                            <div class="select-part">
                                                                <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Aksiya adı və ya açar söz" value="<?=$keyword?>" />
                                                            </div>
                                                            <div>
                                                                <button type="submit" class="btn btn-effect">Axtarış et</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="block-back">
                                        <h4 class="heading-inner">AKSİYALAR</h4>
                                    </div>
                                </div>
                                <div class="block">
                                    <?php
                                    if(!empty($promotions)) {
                                        foreach ($promotions as $pr) { ?>
                                            <div class="col-md-4 col-sm-6">
                                                <a href="<?= $pr['url'] ?>">
                                                    <div class="block-cover">
                                                        <div class="img-cover image-thumbnail">
                                                            <img src="<?= Functions::getUploadUrl() . $customPath . '/small/' . $pr['photo'] ?>"
                                                                 class="img-responsive center-block"
                                                                 alt="<?= $pr['photo'] ?>">
                                                        </div>
                                                        <div class="information">
                                                            <?php
                                                            if (!empty($pr['connect'])) {
                                                                echo '<p class="info-name">' . $pr['connect'] . '</p>';
                                                            }
                                                            ?>
                                                            <?php
                                                            if (!empty($pr['headline'])) {
                                                                echo '<p class="info-major">' . $pr['headline'] . '</p>';
                                                            }
                                                            ?>

                                                            <?PHP if(!empty($pr['date'])){ ?>
                                                            <span class="date-gray"><i class="fa fa-calendar" aria-hidden="true"></i> <?= $pr['date']; ?></span>
                                                            <?PHP }; ?>

                                                            <?php
                                                            if (!empty($pr['discount'])) {
                                                                ?>
                                                                <div class="pharmacy-sales">
                                                                    <div class="sale-flag">
                                                                        <div class="flag-content">
                                                                            <h3><?= $pr['discount']; ?><sup>%</sup>
                                                                            </h3>
                                                                            <span>ENDİRİM</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php }
                                    }else{
                                        echo 'Melumat yoxdur';
                                    } ?>
                                </div>
                                <div class="col-xs-12 text-center">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            <?= \yii\widgets\LinkPager::widget([
                                                'pagination' => $pagination,
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