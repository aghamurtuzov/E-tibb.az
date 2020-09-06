<?php
use backend\components\Functions;
use yii\helpers\Url;
$this->title = $page_title;
$param = Yii::$app->params;
$url         = $param['site.aksiya'].Functions::slugify($promotion['headline'],['transliterate' => true]).'-'.$promotion['id'];
$title       = $promotion['headline'];
$lead        = Functions::textLimit(Functions::getCleanText($promotion['content']),80);
$image       = Functions::getUploadUrl().$customPath.'/'.$promotion['photo'];
$imageSmall  = Functions::getUploadUrl().$customPath.'/small/'.$promotion['photo'];
$imageInfo   = @getimagesize($image);
$imageWidth  = isset($imageInfo[0]) ? $imageInfo[0] : 200;
$imageHeight = isset($imageInfo[1]) ? $imageInfo[1] : 200;
$catName     = $breadcrumb;

Yii::$app->params['og_title']['content'] = Functions::getCleanText($promotion['headline']);
Yii::$app->params['og_description']['content'] = Functions::getCleanText($promotion['content']);
Yii::$app->params['og_url']['content'] = Url::current([], true);
Yii::$app->params['og_image']['content'] = Functions::getUploadUrl().$customPath.'/'.$promotion['photo'];
Yii::$app->params['og_type']['content'] = 'article';
Yii::$app->params['og_image_width']['content'] = 720;
Yii::$app->params['og_image_height']['content'] = 377;

?>
<!--<div class="row -h-top">-->
<!--    <div class="col-md-12">-->
<!--        <iframe class="map-frame" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12155.893067084138!2d49.87453016977538!3d40.38728510000001!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d40a035a6bd%3A0xa8c2cbf267a83fbd!2sHeydar+Aliyev+Centre!5e0!3m2!1sen!2s!4v1539126391661" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>-->
<!--    </div>-->
<!--</div>-->
<section class="stocks stocks-inner">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?=Yii::$app->params['site.url']?>">Ana səhifə </a></li>
                        <li class="active"> <?=$breadcrumb;?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row promotion-inner">
                    <div class="col-md-9 stocks-left">
                        <div class="row">
                            <!--                            <div class="col-md-12">-->
                            <!--                                <div class="block-back">-->
                            <!--                                    <div class="form">-->
                            <!--                                        <div class="form-list row">-->
                            <!--                                            <div class="col-md-4">-->
                            <!--                                                <select class="form-control selectpicker" name="money" title="Klinika">-->
                            <!--                                                    <option value="allerqoloq-29">Allerqoloq</option>-->
                            <!--                                                    <option value="androloq-9">Androloq</option>-->
                            <!--                                                    <option value="anestezioloq-47">Anestezioloq</option>-->
                            <!--                                                    <option value="dermatoloq-23">Dermatoloq</option>-->
                            <!--                                                    <option value="dietoloq-43">Dietoloq</option>-->
                            <!--                                                </select>-->
                            <!--                                            </div>-->
                            <!--                                            <div class="col-md-4">-->
                            <!--                                                <select class="form-control selectpicker" name="money" title="Həkim">-->
                            <!--                                                    <option value="allerqoloq-29">Allerqoloq</option>-->
                            <!--                                                    <option value="androloq-9">Androloq</option>-->
                            <!--                                                    <option value="anestezioloq-47">Anestezioloq</option>-->
                            <!--                                                    <option value="dermatoloq-23">Dermatoloq</option>-->
                            <!--                                                    <option value="dietoloq-43">Dietoloq</option>-->
                            <!--                                                </select>-->
                            <!--                                            </div>-->
                            <!--                                            <div class="col-md-4">-->
                            <!--                                                <button type="submit" class="btn btn-effect">Axtarış et</button>-->
                            <!--                                                <button type="submit" class="btn btn-all">Elan yerləşdir</button>-->
                            <!--                                            </div>-->
                            <!--                                        </div>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <div class="col-md-12">
                                <div class="block-back">
                                    <h4 class="heading-inner"><?=$promotion['headline']?></h4>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="block-back">
                                    <div class="stocks-inner-body">
                                        <div class="row">
                                            <div class="col-md-5 col-xs-12">
                                                <img src="<?=Functions::getUploadUrl().$customPath.'/'.$promotion['photo']?>" alt="<?=Functions::getCleanText($promotion['headline'])?>" class="img-responsive">
                                            </div>
                                            <div class="col-md-7 col-xs-12">
                                                <div class="stocks-inner-information">
                                                    <?php if(!empty($promotion['price2'])){ ?>
                                                        <div class="pharmacy-sales">
                                                            <p>Endirim qiyməti:</p>
                                                            <h5 class="stocks-inner-sale"><?=$promotion['price']?> <sup>M</sup></h5>
                                                            <h5 class="stocks-inner-coin"><?=$promotion['price2']?> <sup>M</sup></h5>
                                                            <div class="sale-flag">
                                                                <div class="flag-content">
                                                                    <h3><?=$promotion['discount'];?> <sup>%</sup></h3>
                                                                    <span>ENDİRİM</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php }elseif(!empty($promotion['price'])){ ?>
                                                        <div class="pharmacy-sales">
                                                            <h5 class="stocks-inner-coin"><?=$promotion['price']?> <sup>M</sup></h5>
                                                        </div>
                                                    <?php } ?>
                                                    <?php
                                                        if(!empty($promotion['address']) or !empty($promotion['phones']))
                                                        {
                                                            ?>
                                                            <div class="stocks-contact pharmacy-contact">
                                                                <?php if (!empty($promotion['address'])){ ?>
                                                                    <div class="pharmacy-connect">
                                                                        <?php
                                                                        foreach ($promotion['address'] as $addr){
                                                                            ?>
                                                                            <i class="fa fa-map-marker" aria-hidden="true"></i><span> <?=$addr['address'];?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                <?php  } ?>

                                                                <ul class="list-unstyled pharmacy-connect">
                                                                    <?php
                                                                    if($promotion['phones']) {
                                                                        foreach ($promotion['phones'] as $phone){
                                                                            $type = ($phone['number_type']==0) ? "phone" : "mobile";
                                                                            echo '<li><a href="tel:'.$phone['number'].'"><i class="fa fa-'.$type.'" aria-hidden="true"></i> <span>'.$phone['number'].'</span></a></li>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
                                                            <?php
                                                        }
                                                    ?>

                                                    <div class="stocks-social col-xs-12">
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <p>Aksiyanın müddəti:</p>
                                                                <span class="date-gray"><?=$promotion['dates']?></span>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <p>Sosial şəbəkələrdə paylaş:</p>
                                                                <div class="pharmacy-contact stock_social">
                                                                    <ul class="list-inline">
                                                                        <li class="fb">
                                                                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?=Functions::getSiteUrl().'/'.url::base().'/'.Yii::$app->params['site.post_uri'].Functions::slugify($promotion['headline'],['transliterate' => true]).'-'.$promotion['id']?>"><span><i class="fa fa-facebook" aria-hidden="true"></i></span></a>
                                                                        </li>
                                                                        <li class="twitter">
                                                                            <a href="https://twitter.com/intent/tweet?text=<?=$promotion['headline']?>&url=<?=Functions::getSiteUrl().'/'.url::base().'/'.Yii::$app->params['site.post_uri'].Functions::slugify($promotion['headline'],['transliterate' => true]).'-'.$promotion['id']?>"><span><i class="fa fa-twitter" aria-hidden="true"></i></span></a>
                                                                        </li>
                                                                        <li class="linkedln">
                                                                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?=Functions::getSiteUrl().'/'.url::base().'/'.Yii::$app->params['site.post_uri'].Functions::slugify($promotion['headline'],['transliterate' => true]).'-'.$promotion['id']?>"><span><i class="fa fa-linkedin" aria-hidden="true"></i></span></a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stocks-inner-text">
                                        <p><?=$promotion['content'];?></p>
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

