<?php

use backend\components\Functions;


?>


<section class="healthy-blog about">
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
                            <div class="col-md-12">
                                <div class="block-back">
                                    <h4 class="heading-inner"><?= mb_strtoupper($breadcrumb) ?></h4>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="block">
                                    <div class="row flex-wrap">
                                        <?php
                                    foreach ($news as $value)
                                    {
        //                                 Yii::$app->params['site.post_uri'] . Functions::slugify($value['headline'], ['transliterate' => true]) . '-' . $value['id']
                                        ?>

                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <a href="<?= Yii::$app->params['site.url'] ."xeber/" . $value['slug'] . '-' . $value['id'] ?>">
                                            <div class="block-cover">
                                                <div class="img-cover">
                                                    <img src="<?= Functions::getUploadUrl() . $customPath . '/small/' . $value['photo'] ?>" class="img-responsive cover" alt="health1">
                                                    <div class="image-over">
                                                        <p class="right-item">
                                                            <img src="<?=Yii::$app->params['site.url']?>assets/img/date.png" alt="date"> <span><?= Functions::getDatetime($value['datetime'], ['type' => 'date']) ?></span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="information">
                                                    <p class="information-text"><?= Functions::getCleanText($value['headline']) ?></p>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 text-center web">
                                <nav aria-label="Page navigation">
                                    <?= \yii\widgets\LinkPager::widget([
                                        'pagination' => $pagination,
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
                                        'pagination' => $pagination,
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

                    <?= $this->render('/layouts/partials/news_sidebar',['tags'=>$tags, 'most_read'=>$most_read, 'cat_id'=>$cat_id]); ?>
                </div>
            </div>
        </div>
    </div>
</section>