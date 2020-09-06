<?php

use backend\components\Functions;
use yii\helpers\Url;

?>

<section class="healthy-bloginner healthy-blog about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?= Yii::$app->params['site.url'] ?>">Ana səhifə </a></li>
                        <li><a href="<?= Yii::$app->params['site.url'] ?>xeberler">Xəbərlər </a></li>
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
                                    <h4 class="heading-inner"><?= mb_strtoupper($breadcrumb) ?></h4>
                                </div>
                            </div>
                            <div class="col-md-12 healthy-left single_news">
                                <div class="block">
                                    <div class="block-cover">
                                        <div class="img-cover">
                                            <img src="<?= Functions::getUploadUrl() . $customPath . '/' . $news['photo'] ?>"
                                                 alt="<?= Functions::getCleanText($news['headline']) ?>"
                                                 class="img-responsive cover">
                                            <div class="image-over">
                                                <!--                                                <p class="left-item web">-->
                                                <!--                                                    <img src="-->
                                                <? //=Url::base();?><!--/assets/img/eye.png" alt="eye"> <span>-->
                                                <? //=$news['news_read']?><!--</span>-->
                                                <!--                                                </p>-->
                                                <p>
                                                    <img src="<?= Url::base(); ?>/assets/img/date.png" alt="date">
                                                    <span><?= Functions::getDatetime($news['datetime'], ['type' => 'date']) ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="information">
                                        <div class="article">
                                            <h4 class="heading-inner"><?= $news['headline'] ?></h4>
                                            <?= $news['content'] ?>
                                            <h5 class="author">

                                            </h5>
                                        </div>
                                        <div class="article-social">
                                            <span class="share">Paylaşım et:</span>
                                            <ul class="list-inline social-icons">
                                                <li class="fb">
                                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= Functions::getSiteUrl() . url::base() . '/' . Yii::$app->params['site.post_uri'] . Functions::slugify($news['headline'], ['transliterate' => true]) . '-' . $news['id'] ?>"><span><i
                                                                    class="fa fa-facebook"
                                                                    aria-hidden="true"></i></span></a>
                                                </li>
                                                <li class="twitter">
                                                    <a href="https://twitter.com/intent/tweet?text=<?= $news['headline'] ?>&url=<?= Functions::getSiteUrl() . url::base() . '/' . Yii::$app->params['site.post_uri'] . Functions::slugify($news['headline'], ['transliterate' => true]) . '-' . $news['id'] ?>"><span><i
                                                                    class="fa fa-twitter" aria-hidden="true"></i></span></a>
                                                </li>
                                                <!--                                                <li class="instagram">-->
                                                <!--                                                    <a href=""><span><i class="fa fa-instagram" aria-hidden="true"></i></span></a>-->
                                                <!--                                                </li>-->
                                                <li class="linkedln">
                                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= Functions::getSiteUrl() . url::base() . '/' . Yii::$app->params['site.post_uri'] . Functions::slugify($news['headline'], ['transliterate' => true]) . '-' . $news['id'] ?>"><span><i
                                                                    class="fa fa-linkedin"
                                                                    aria-hidden="true"></i></span></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 text-center">
                                <nav>
                                    <div class="pager pager_news">
                                        <div class="previous text-left">
                                            <a href="<?= Yii::$app->params['site.url'] . Yii::$app->params['site.post_uri'] . $post['prev']['slug'] . '-' . $post['prev']['id'] ?>">
                                                <span aria-hidden="true"><i class="fa fa-arrow-left"
                                                                            aria-hidden="true"></i> </span> Əvvəlki
                                                postu oxu
                                            </a>
                                        </div>
                                        <div class="next text-right">
                                            <a href="<?= Yii::$app->params['site.url'] . Yii::$app->params['site.post_uri'] . $post['next']['slug'] . '-' . $post['next']['id'] ?>">Sonrakı
                                                postu oxu <span aria-hidden="true"> <i class="fa fa-arrow-right"
                                                                                       aria-hidden="true"></i></span>
                                            </a>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <?= $this->render('/layouts/partials/news_sidebar', ['tags' => $tags, 'most_read' => $most_read, 'cat_id' => $news['category_id']]); ?>
                </div>
            </div>
        </div>
    </div>
</section>