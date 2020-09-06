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
                    <div class="col-md-9" style="margin-bottom: 30px;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block-back">
                                    <h4 class="heading-inner"><?=str_replace("I","İ",mb_strtoupper($data['title']))?></h4>
                                </div>
                            </div>
                            <div class="col-md-12 healthy-left">
                                <div class="block">
                                    <div class="block-cover">
                                        <div class="img-cover blood-img-cover">
                                            <img src="<?=Functions::getUploadUrl().$customPath.'/qanvar-3.png'?>"  alt="<?=Functions::getCleanText($data['title'])?>" class="img-responsive cover" >
                                            <div class="image-over">
                                                <!--                                                <p class="left-item">-->
                                                <!--                                                    <img src="--><?//=Url::base();?><!--/assets/img/eye.png" alt="eye"> <span>--><?//=$news['news_read']?><!--</span>-->
                                                <!--                                                </p>-->
                                                <p>
                                                    <img src="<?=Url::base();?>/assets/img/date.png" alt="date"> <span><?=Functions::getDatetime($data['created_at'],['type'=>'date'])?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="information">
                                        <div class="article">
                                            <h4 class="heading-inner"><?=$data['title']?></h4>
                                            <?=$data['text']?>
                                            <h5 class="author">

                                            </h5>
                                        </div>
                                        <div class="article-social">
                                            <span class="share">Paylaşım et:</span>
                                            <ul class="list-inline social-icons">
                                                <li class="fb">
                                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?=Functions::getSiteUrl().'/'.url::base().'/'.Yii::$app->params['site.qanver_uri'].Functions::slugify($data['title'],['transliterate' => true]).'-'.$data['id']?>"><span><i class="fa fa-facebook" aria-hidden="true"></i></span></a>
                                                </li>
                                                <li class="twitter">
                                                    <a href="https://twitter.com/intent/tweet?text=<?=$data['title']?>&url=<?=Functions::getSiteUrl().'/'.url::base().'/'.Yii::$app->params['site.qanver_uri'].Functions::slugify($data['title'],['transliterate' => true]).'-'.$data['id']?>"><span><i class="fa fa-twitter" aria-hidden="true"></i></span></a>
                                                </li>
                                                <li class="linkedln">
                                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?=Functions::getSiteUrl().'/'.url::base().'/'.Yii::$app->params['site.qanver_uri'].Functions::slugify($data['title'],['transliterate' => true]).'-'.$data['id']?>"><span><i class="fa fa-linkedin" aria-hidden="true"></i></span></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?=$this->render('/layouts/partials/sidebar_new'); ?>
                </div>
            </div>
        </div>
    </div>
</section>