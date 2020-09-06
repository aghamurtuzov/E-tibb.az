<?php
use yii\helpers\Url;
use backend\components\Functions;
use \frontend\models\SitePhoneNumbersModel;
use \frontend\models\SiteAdressesModel;

Yii::$app->view->registerLinkTag(["rel" => "canonical","href" => $href]);
$special = false;
$img_class = ' clinic-image';
if(isset($settings["template"])){
    if($settings["template"]==1){
        $special = true;
        $img_class= ' cosmetology-image';
    }
}

$this->title = $page_title." ".$page_type_title;
$enterprise = $model;
$premium = false;
$premium_class = '';
if(date("Y-m-d")<$enterprise["expires"]){
    $premium = true;
    $premium_class = ' premium';
}
$saat24  = false;
$catdirilma = false;
$eve_cagiris = false;
$features = json_decode($enterprise["feature"],true);
if(is_array($features) and count($features)>0){
    if(key_exists("saat24",$features)){
        $saat24 = true;
    }

    if(key_exists("catdirilma",$features)){
        $catdirilma = true;
    }

    if(key_exists("eve_caqiris",$features)){
        $eve_cagiris = true;
    }
}
$link        = Url::base('https').'/'.$category['link'].'-'.$category['id'].'/'.$enterprise["id"].'-'.Functions::slugify($enterprise["name"],['transliterate'=>true]);
$title       = $page_title;
$lead        = Functions::textLimit(Functions::getCleanText($enterprise["about"]),50);
$image       = Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/'.$enterprise['photo'];
$imageSmall  = Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/small/'.$enterprise['photo'];
$imageInfo   = @getimagesize($image);
$imageWidth  = isset($imageInfo[0]) ? $imageInfo[0] : 200;
$imageHeight = isset($imageInfo[1]) ? $imageInfo[1] : 200;
$catName     = $page_title;

//tabs data
$addresses = SiteAdressesModel::getAddresses($enterprise["id"],2);
$phones = SitePhoneNumbersModel::getPhones($enterprise["id"],2);
$socialsLinks = \frontend\models\SiteSocialLinksModel::getSocialLinks($enterprise["id"],2);
$phones_array = [];

foreach ($phones as $phone){
    $phones_array[$phone["number_type"]][] = $phone["number"];
}
$socials = [];
foreach ($socialsLinks as $social)
{
    $sosialLinkType = ['facebook','instagram','youtube','twitter','linkedin'];
    $socials[$sosialLinkType[$social["link_type"]]] = $social["link"];
}
//tabs data


?>
<section class="pharmacy-inner pharmacy">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <?php
                        foreach ($breadcrumbs as $breadcrumb){
                        $b_str = $breadcrumb["name"];
                        $b_active = ' active';
                        if($breadcrumb["url"]) {
                            $b_str = '<a href="'.$breadcrumb["url"].'">'.$b_str.'</a>';
                            $b_active = '';
                        };
                        ?>
                        <li class="breadcrumb-item<?= $b_active?>">
                            <?=$b_str  ?>
                            <?php
                            }
                            ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                if(Yii::$app->session->hasFlash("success")){
                    ?>
                    <br />
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <?= Yii::$app->session->getFlash("success"); ?>
                    </div>
                    <?php
                }

                ?>
                <div class="row">
                    <div class="col-md-9 pharmacy-left">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block-back">
                                    <h4 class="heading-inner"><?= $page_title?></h4>
<!--                                    <span class="rating right-item">-->
<!--                                        <i class="fa fa-star active" aria-hidden="true" data-rate="--><?//= $enterprise["rating"]?><!--"></i>-->
<!--                                        <i class="fa fa-star" aria-hidden="true"></i>-->
<!--                                        <i class="fa fa-star" aria-hidden="true"></i>-->
<!--                                        <i class="fa fa-star" aria-hidden="true"></i>-->
<!--                                        <i class="fa fa-star" aria-hidden="true"></i>-->
<!--                                    </span>-->
                                </div>
                                <div class="block-back pharmacy-body">
                                    <div class="row relative">
                                        <div class="col-md-3 col-sm-3 col-xs-12">
                                            <div>
                                                <img src="<?= $enterprise['photo'] ? Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/small/'.$enterprise['photo'] : Yii::$app->params['site.url'].Yii::$app->params['site.defaultThumb'] ?>" alt="pharmacy-inner1" class="img-responsive photo-square">
                                            </div>
                                            <?php
                                            if($premium){
                                                echo '<span class="crown"></span>';
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <div class="pharmacy-about">
                                                <h5>İş vaxtları</h5>
                                                <ul class="list-inline pharmacy-hours">
                                                    <li>
                                                        <p><img src="<?=Yii::$app->params['site.url'];?>/assets/img/calendar-blue.png" alt="calendar-blue"> B.e - Cümə</p>
                                                        <span> <img src="<?=Yii::$app->params['site.url'];?>/assets/img/clock-blue.png" alt="calendar-blue"> <?= ($enterprise["weekdays"]=='' and $saat24==true)?'24saat':$enterprise["weekdays"]?></span>
                                                    </li>
                                                    <li>
                                                        <p>Şənbə</p>
                                                        <span><?= ($enterprise["saturday"]=='' and $saat24==true)?'24saat':$enterprise["saturday"]?></span>
                                                    </li>
                                                    <li>
                                                        <p>Bazar</p>
                                                        <span><?= ($enterprise["sunday"]=='' and $saat24==true)?'24saat':$enterprise["sunday"]?></span>
                                                    </li>
                                                </ul>
                                                <div class="pharmacy-delivered">
                                                    <?php if($catdirilma==true) echo '<h6><img src="'.Yii::$app->params["site.url"].'/assets/img/delivered.png" alt="delivered">Çatdırılma</h6>' ?>
                                                    <?php if($saat24==true) echo '<h6><img src="'.Yii::$app->params["site.url"].'/assets/img/clock.png" alt="delivered">Saat</h6>' ?>
                                                </div>
                                                <?php if (intval($enterprise["promotion"])>=1){ ?>
                                                    <div class="percent">
                                                        <span><?= $enterprise["promotion"]?></span> <img src="<?=Yii::$app->params['site.url'];?>/assets/img/percent.png" alt="percent">
                                                        <h6>Aksiya sayı</h6>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?= $this->render('_promotions', ["promotions" => $promotions,"enterprise" => $enterprise]) ?>
                                <div class="block-back pharmacy-contact">
                                    <div class="doctor-about connection">
                                        <div class="row">
                                            <h5 class="connect">ƏLAQƏ VASİTƏLƏRİ</h5>
                                            <?php
                                                if(isset($phones_array[SitePhoneNumbersModel::$TYPE_PHONE])){
                                            ?>
                                            <div class="col-md-4 col-sm-6">
                                                <h5>Telefon</h5>
                                                <ul class="list-unstyled pharmacy-connect pt-0">
                                                    <?php
                                                        foreach ($phones_array[SitePhoneNumbersModel::$TYPE_PHONE] as $phone){
                                                            ?>
                                                            <li>
                                                                <a href="tel:<?= $phone?>"><i class="fa fa-phone" aria-hidden="true"></i> <span><?= $phone?></span></a>
                                                            </li>
                                                            <?php
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                            <?php } ?>
                                            <?php
                                                if(isset($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE]) || isset($phones_array[SitePhoneNumbersModel::$TYPE_WP])){
                                            ?>
                                            <div class="col-md-4 col-sm-6">
                                                <h5>Mobil</h5>
                                                <ul class="list-unstyled pharmacy-connect">
                                                    <?php
                                                    if(isset($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE])){
                                                        foreach ($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE] as $phone){
                                                            ?>
                                                            <li>
                                                                <a href="tel::<?= $phone?>"><i class="fa fa-mobile" aria-hidden="true"></i> <span>:<?= $phone?></span></a>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                    if(isset($phones_array[SitePhoneNumbersModel::$TYPE_WP]))
                                                        foreach ($phones_array[SitePhoneNumbersModel::$TYPE_WP] as $phone){
                                                            ?>
                                                            <li>
                                                                <a href="https://api.whatsapp.com/send?phone=<?=$phone?>"><i class="fa fa-whatsapp" aria-hidden="true"></i> <span><?=$phone?></span></a>
                                                            </li>
                                                            <?php
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                            <?php } ?>
                                            <div class="changeable-flex">
                                                <?php
                                                    if(count($socials)>0)
                                                    {
                                                        ?>
                                                        <div class="col-md-4 col-sm-6">
                                                            <h5>Sosial şəbəkəsi</h5>
                                                            <ul class="list-inline">
                                                                <?php if(isset($socials["facebook"])){ ?>
                                                                    <li>
                                                                        <a href="<?= $socials["facebook"]?>"><span><i class="fa fa-facebook" aria-hidden="true"></i></span></a>
                                                                    </li>
                                                                <?php } ?>

                                                                <?php if(isset($socials["instagram"])){ ?>
                                                                    <li>
                                                                        <a href="<?= $socials["instagram"]?>"><span><i class="fa fa-twitter" aria-hidden="true"></i></span></a>
                                                                    </li>
                                                                <?php } ?>

                                                                <?php if(isset($socials["youtube"])){ ?>
                                                                    <li>
                                                                        <a href="<?= $socials["youtube"]?>"><span><i class="fa fa-twitter" aria-hidden="true"></i></span></a>
                                                                    </li>
                                                                <?php } ?>


                                                                <?php if(isset($socials["twitter"])){ ?>
                                                                    <li>
                                                                        <a href="<?= $socials["twitter"]?>"><span><i class="fa fa-twitter" aria-hidden="true"></i></span></a>
                                                                    </li>
                                                                <?php } ?>

                                                                <?php if(isset($socials["linkedin"])){ ?>
                                                                    <li>
                                                                        <a href="<?= $socials["linkedin"]?>"><span><i class="fa fa-twitter" aria-hidden="true"></i></span></a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                                <?php
                                                    if(count($addresses)>0)
                                                    {
                                                        ?>
                                                        <div class="col-md-8 col-sm-6">
                                                            <h5>Ünvan</h5>
                                                            <div class="pharmacy-connect">
                                                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                                                <?php
                                                                foreach ($addresses as $address){
                                                                    ?>
                                                                    <span><?= $address["address"]?></span>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
<!--                                            <div class="col-md-4 col-sm-4">-->
<!--                                                <h5>VEB SAYT</h5>-->
<!--                                                <div class="pharmacy-connect">-->
<!--                                                    <a href="www.zafaranaptek.az">www.zafaranaptek.az</a>-->
<!--                                                </div>-->
<!--                                            </div>-->
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



<?php // $this->render('/layouts/partials/modals/comment_modal', ["comment" => $comment]) ?>


