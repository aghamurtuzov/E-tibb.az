<?php

use backend\components\Functions;
?>

<section class="video-interview base">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="index.html">Ana səhifə </a></li>
                        <li class="active"><?= $breadcrumb; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h4 class="head-text"><?= mb_strtoupper($breadcrumb) ?></h4>
            </div>
            <div class="col-xs-12 relative">
                <div class="row">
                    <?php if(!empty($news[0])){ ?>
                    <div class="col-md-6">
                        <div class="video-content">
                            <div class="video-back" style="background: url(<?= Functions::getUploadUrl() . $customPath . '/' . $news[0]['photo'] ?>)">
                                <span class="play"><i class="fa fa-play" aria-hidden="true"></i></span>
                            </div>
                            <h4><a href="<?= Yii::$app->params['site.post_uri'] . $news[0]['slug'] . '-' . $news[0]['id'] ?>"><?= Functions::getCleanText($news[0]['headline']) ?></a></h4>
                            <div class="view-date">
                                <span><img src="/assets/img/eye_gray.png" alt="eye_gray"><?=$news[0]['news_read']?></span>
                                <span><img src="/assets/img/date_gray.png" alt="date_gray"><?= Functions::getDatetime($news[0]['datetime'], ['type' => 'date']) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="col-md-6 position-right">
                        <?php if(!empty($news[1])){ ?>
                        <div class="video-right">
                            <div class="list-articles">
                                <div class="row">
                                    <a href="<?= Yii::$app->params['site.post_uri'] . $news[1]['slug'] . '-' . $news[1]['id'] ?>" class="d-block">
                                        <div class="col-md-4">
                                            <div class="img-block">
                                                <img src="<?= Functions::getUploadUrl() . $customPath . '/small/' . $news[1]['photo'] ?>" alt="articles-1" class="img-responsive center-block">
                                                <span class="play"><i class="fa fa-play" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h4><?= Functions::getCleanText($news[1]['headline']) ?></h4>
                                            <div class="view-date">
                                                <span><img src="/assets/img/eye_gray.png" alt="eye_gray"><?=$news[1]['news_read']?></span>
                                                <span><img src="/assets/img/date_gray.png" alt="date_gray"><?= Functions::getDatetime($news[1]['datetime'], ['type' => 'date']) ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php }
                                if(!empty($news[2])){
                            ?>
                            <div class="list-articles">
                                <div class="row">
                                    <a href="<?= Yii::$app->params['site.post_uri'] . $news[2]['slug'] . '-' . $news[2]['id'] ?>" class="d-block">
                                        <div class="col-md-4">
                                            <div class="img-block">
                                                <img src="<?= Functions::getUploadUrl() . $customPath . '/small/' . $news[2]['photo'] ?>" alt="articles-1" class="img-responsive center-block">
                                                <span class="play"><i class="fa fa-play" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h4><?= Functions::getCleanText($news[2]['headline']) ?></h4>
                                            <div class="view-date">
                                                <span><img src="/assets/img/eye_gray.png" alt="eye_gray"><?=$news[2]['news_read']?></span>
                                                <span><img src="/assets/img/date_gray.png" alt="date_gray"><?= Functions::getDatetime($news[2]['datetime'], ['type' => 'date']) ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                                <?php }
                            if(!empty($news[3])){
                            ?>
                            <div class="list-articles">
                                <div class="row">
                                    <a href="<?= Yii::$app->params['site.post_uri'] . $news[3]['slug'] . '-' . $news[3]['id'] ?>" class="d-block">
                                        <div class="col-md-4">
                                            <div class="img-block">
                                                <img src="<?= Functions::getUploadUrl() . $customPath . '/small/' . $news[1]['photo'] ?>" alt="articles-1" class="img-responsive center-block">
                                                <span class="play"><i class="fa fa-play" aria-hidden="true"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <h4><?= Functions::getCleanText($news[3]['headline']) ?></h4>
                                            <div class="view-date">
                                                <span><img src="/assets/img/eye_gray.png" alt="eye_gray"><?=$news[3]['news_read']?></span>
                                                <span><img src="/assets/img/date_gray.png" alt="date_gray"><?= Functions::getDatetime($news[3]['datetime'], ['type' => 'date']) ?></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <h4 class="head-text">BÜTÜN MÜNASİBƏTLƏR</h4>
                <div class="row block">
                    <?php for($i=4; $i<count($news)-4; $i++){ ?>
                    <div class="col-md-3 col-12">
                        <a href="<?= Yii::$app->params['site.post_uri'] . $news[$i]['slug'] . '-' . $news[$i]['id'] ?>">
                            <div class="block-cover">
                                <div class="img-cover">
                                    <img src="<?= Functions::getUploadUrl() . $customPath . '/small/' . $news[$i]['photo'] ?>" class="img-responsive cover" alt="health1">
                                    <div class="image-over">
                                        <p class="left-item">
                                            <img src="/assets/img/eye.png" alt="eye"> <span><?=$news[$i]['news_read']?></span>
                                        </p>
                                        <p class="right-item">
                                            <img src="/assets/img/date.png" alt="date"> <span><?= Functions::getDatetime($news[$i]['datetime'], ['type' => 'date']) ?></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="information">
                                    <p class="information-text"><?= Functions::getCleanText($news[$i]['headline']) ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
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
</section>