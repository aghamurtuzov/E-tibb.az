<?php

use api\modules\doctor\models\EnterprisesApiModel;
use yii\helpers\Url;
use backend\components\Functions;
use \frontend\models\SitePhoneNumbersModel;
use \frontend\models\SiteAdressesModel;

Yii::$app->view->registerLinkTag(["rel" => "canonical", "href" => $href]);
$special = false;
$img_class = ' clinic-image';
if (isset($settings["template"])) {
    if ($settings["template"] == 1) {
        $special = true;
        $img_class = ' cosmetology-image';
    }
}

$this->title = $page_title . " " . $page_type_title;
$enterprise = $model;
$premium = false;
$premium_class = '';
if (date("Y-m-d") < $enterprise["expires"]) {
    $premium = true;
    $premium_class = ' premium';
}
$saat24 = false;
$catdirilma = false;
$eve_cagiris = false;
$features = json_decode($enterprise["feature"], true);
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
$link = Url::base('https') . '/' . $category['link'] . '-' . $category['id'] . '/' . $enterprise["id"] . '-' . Functions::slugify($enterprise["name"], ['transliterate' => true]);
$title = $page_title;
$lead = Functions::textLimit(Functions::getCleanText($enterprise["about"]), 50);
$image = Functions::getUploadUrl() . Yii::$app->params['path.enterprises'] . '/' . $enterprise['photo'];
$imageSmall = Functions::getUploadUrl() . Yii::$app->params['path.enterprises'] . '/small/' . $enterprise['photo'];
$imageInfo = @getimagesize($image);
$imageWidth = isset($imageInfo[0]) ? $imageInfo[0] : 200;
$imageHeight = isset($imageInfo[1]) ? $imageInfo[1] : 200;
$catName = $page_title;

//tabs data
$addresses = SiteAdressesModel::getAddresses($enterprise["id"], 2);
$phones = SitePhoneNumbersModel::getPhones($enterprise["id"], 2);
$socialsLinks = \frontend\models\SiteSocialLinksModel::getSocialLinks($enterprise["id"], 2);
$phones_array = [];

foreach ($phones as $phone) {
    $phones_array[$phone["number_type"]][] = $phone["number"];
}
$socials = [];
foreach ($socialsLinks as $social) {
    $sosialLinkType = ['facebook', 'instagram', 'youtube', 'twitter', 'linkedin'];
    $socials[$sosialLinkType[$social["link_type"]]] = $social["link"];
}
//tabs data


?>
<section class="clinic-inner doctor-page about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <?php
                        foreach ($breadcrumbs

                        as $breadcrumb){
                        $b_str = $breadcrumb["name"];
                        $b_active = ' active';
                        if ($breadcrumb["url"]) {
                            $b_str = '<a href="' . $breadcrumb["url"] . '">' . $b_str . '</a>';
                            $b_active = '';
                        };
                        ?>
                        <li class="breadcrumb-item<?= $b_active ?>">
                            <?= $b_str ?>
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
                <div class="row">
                    <?php
                    if (Yii::$app->session->hasFlash("success")) {
                        ?>
                        <br/>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            <?= Yii::$app->session->getFlash("success"); ?>
                        </div>
                        <?php
                    }

                    $getComments = \app\models\SiteComments::getCommentsByEnterprise($enterprise['id']);

                    $certificate = \frontend\models\SiteDoctorFileModel::getFiles($enterprise['id'], 2);
                    $certificates = !empty($certificate) ? \frontend\models\SiteDoctors::ResultList($certificate, 'enterprises') : [];

                    if ($promotions || count($getComments) > 0 || count($certificates) > 0) {
                        $class1 = 8;
                        $class2 = 4;
                    } else {
                        $class1 = 9;
                        $class2 = 3;
                    }

                    ?>
                    <div class="col-md-<?= $class1 ?> col-xs-12 clinic-inner-left">
                        <div class="clinic-about doctor-about block-back">
                            <div class="about-top">
                                <div class="row">
                                    <div class="col-xs-12 relative">
                                        <div class="doctor-specialty user clinics_info">
                                            <img src="<?= $enterprise['photo'] ? Functions::getUploadUrl() . Yii::$app->params['path.enterprises'] . '/small/' . $enterprise['photo'] : Yii::$app->params['site.url'] . Yii::$app->params['site.defaultThumb'] ?>"
                                                 alt="<?= htmlentities($page_title) ?>" class="img-responsive">
                                            <div class="preferences">
                                                <h4><?= $page_title ?></h4>
                                                <div class="pharmacy-about">
                                                    <h5>İş vaxtları</h5>
                                                    <ul class="list-inline pharmacy-hours">
                                                        <li>
                                                            <p>
                                                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                                                B.e- Cümə
                                                            </p>
                                                            <span>
                                                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                                                <?= ($enterprise["weekdays"] == '' and $saat24 == true) ? '24saat' : $enterprise["weekdays"] ?>
                                                            </span>
                                                        </li>
                                                        <li>
                                                            <p>Şənbə</p>
                                                            <span><?= ($enterprise["saturday"] == '' and $saat24 == true) ? '24saat' : $enterprise["saturday"] ?></span>
                                                        </li>
                                                        <li>
                                                            <p>Bazar</p>
                                                            <span>
                                                            <?php
                                                            if ($enterprise["sunday"] == '' and $saat24 == true) {
                                                                echo '24saat';
                                                            } elseif ($enterprise["sunday"] == '' and $saat24 == false) {
                                                                echo '-';
                                                            } else {
                                                                echo $enterprise["sunday"];
                                                            }
                                                            ?>
                                                        </li>
                                                    </ul>
                                                    <div class="pharmacy-delivered">
                                                        <!--                                            <h6><img src="img/emergency-blue.png" alt="emergency">Çağırış</h6>-->
                                                        <!--                                            <h6><img src="img/clock.png" alt="clock">Saat</h6>-->
                                                        <?php if ($catdirilma == true) echo '<h6><img src="' . Yii::$app->params["site.url"] . '/assets/img/delivered.png" alt="delivered">Çatdırılma</h6>' ?>
                                                        <?php if ($saat24 == true) echo '<h6><img src="' . Yii::$app->params["site.url"] . '/assets/img/clock.png" alt="delivered">Saat</h6>' ?>
                                                        <?php if ($eve_cagiris == true) echo '<h6><img src="' . Yii::$app->params["site.url"] . '/assets/img/emergency-blue.png" alt="delivered">Evə çağırış</h6>' ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--                                      <div class="flexible-part">-->
                                        <!--                                          <p class="rate">-->
                                        <!--                                                <span class="rating">-->
                                        <!--                                                    <i class="fa fa-star active" aria-hidden="true"></i>-->
                                        <!--                                                    <i class="fa fa-star active" aria-hidden="true"></i>-->
                                        <!--                                                    <i class="fa fa-star active" aria-hidden="true"></i>-->
                                        <!--                                                    <i class="fa fa-star active" aria-hidden="true"></i>-->
                                        <!--                                                    <i class="fa fa-star" aria-hidden="true"></i>-->
                                        <!--                                                </span>-->
                                        <!--                                          </p>-->
                                        <!--                                      </div>-->
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0 mb-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="about-body">
                                        <h5>Haqqında</h5>
                                        <p><?= Functions::getCleanText($enterprise["about"]) ?></p>
                                    </div>
                                    <hr class="mt-0">
                                </div>
                            </div>
                            <div class="row">
                                <div class="about-bottom">
                                    <div class="doctor-about connection">
                                        <div class="row">
                                            <?php
                                            if (isset($phones_array[SitePhoneNumbersModel::$TYPE_PHONE])) {
                                                ?>
                                                <div class="col-md-4 col-sm-4">
                                                    <h5>Telefon</h5>
                                                    <ul class="list-unstyled pharmacy-connect">
                                                        <?php
                                                        foreach ($phones_array[SitePhoneNumbersModel::$TYPE_PHONE] as $phone) {
                                                            ?>
                                                            <li>
                                                                <a href="tel:<?= $phone ?>"><i class="fa fa-phone"
                                                                                               aria-hidden="true"></i>
                                                                    <span><?= $phone ?></span></a>
                                                            </li>
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <?php
                                            if (isset($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE]) || isset($phones_array[SitePhoneNumbersModel::$TYPE_WP])) {
                                                ?>
                                                <div class="col-md-4 col-sm-4 web-xs">
                                                    <h5>Mobil</h5>
                                                    <ul class="list-unstyled pharmacy-connect">
                                                        <?php
                                                        if (isset($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE])) {
                                                            foreach ($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE] as $phone) {
                                                                ?>
                                                                <li>
                                                                    <a href="tel::<?= $phone ?>"><i class="fa fa-mobile"
                                                                                                    aria-hidden="true"></i>
                                                                        <span>:<?= $phone ?></span></a>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                        <?php
                                                        if (isset($phones_array[SitePhoneNumbersModel::$TYPE_WP])) {
                                                            foreach ($phones_array[SitePhoneNumbersModel::$TYPE_WP] as $phone) {
                                                                ?>
                                                                <li>
                                                                    <a href="https://api.whatsapp.com/send?phone=<?= $phone ?>"><i
                                                                                class="fa fa-whatsapp"
                                                                                aria-hidden="true"></i>
                                                                        <span><?= $phone ?></span></a>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            if (count($socials) > 0) {
                                                ?>
                                                <div class="col-md-4 col-sm-4">
                                                    <h5>Sosial şəbəkələri</h5>
                                                    <ul class="list-inline social-icons">
                                                        <?php if (isset($socials["facebook"])) { ?>
                                                            <li>
                                                                <a href="<?= $socials["facebook"] ?>"><span><i
                                                                                class="fa fa-facebook"
                                                                                aria-hidden="true"></i></span></a>
                                                            </li>
                                                        <?php } ?>

                                                        <?php if (isset($socials["instagram"])) { ?>
                                                            <li>
                                                                <a href="<?= $socials["instagram"] ?>"><span><i
                                                                                class="fa fa-instagram"
                                                                                aria-hidden="true"></i></span></a>
                                                            </li>
                                                        <?php } ?>

                                                        <?php if (isset($socials["youtube"])) { ?>
                                                            <li>
                                                                <a href="<?= $socials["youtube"] ?>"><span><i
                                                                                class="fa fa-youtube"
                                                                                aria-hidden="true"></i></span></a>
                                                            </li>
                                                        <?php } ?>


                                                        <?php if (isset($socials["twitter"])) { ?>
                                                            <li>
                                                                <a href="<?= $socials["twitter"] ?>"><span><i
                                                                                class="fa fa-twitter"
                                                                                aria-hidden="true"></i></span></a>
                                                            </li>
                                                        <?php } ?>

                                                        <?php if (isset($socials["linkedin"])) { ?>
                                                            <li>
                                                                <a href="<?= $socials["linkedin"] ?>"><span><i
                                                                                class="fa fa-linkedin"
                                                                                aria-hidden="true"></i></span></a>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                            <div class="changeable-flex">
                                                <div class="col-md-12 col-sm-12">
                                                    <h5>Ünvan</h5>
                                                    <div class="pharmacy-connect for-address">
                                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                                        <?php
                                                        foreach ($addresses as $address) {
                                                            ?>

                                                            <span><?= $address["address"] ?></span>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <!--                                                <div class="col-md-4 col-sm-4">-->
                                                <!--                                                    <h5>Veb sayt</h5>-->
                                                <!--                                                    <div class="pharmacy-connect">-->
                                                <!--                                                        <a href="www.zafaranaptek.az">www.1sayliklinika.az</a>-->
                                                <!--                                                    </div>-->
                                                <!--                                                </div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $model = new \frontend\models\EnterpriseModel();

                        $doctors = $model->Doctors($enterprise['id']);

                        //                        $doctors = \frontend\models\SiteDoctors::ResultList($list,'doctors');

                        if ($doctors) {
                            ?>
                            <div class="clinic-doctors doctor-about block-back">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Həkimlər</h5>
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <!--                                        <div class="find-doctors">-->
                                        <!--                                            <p>Tapılan həkim sayı: <span>-->
                                        <?//=count($doctors)
                                        ?><!--</span></p>-->
                                        <!--                                            <select class="form-control selectpicker" name="money" title="İxtisas seçin">-->
                                        <!--                                                <option value="allerqoloq-29">Allerqoloq</option>-->
                                        <!--                                                <option value="androloq-9">Androloq</option>-->
                                        <!--                                                <option value="anestezioloq-47">Anestezioloq</option>-->
                                        <!--                                                <option value="dermatoloq-23">Dermatoloq</option>-->
                                        <!--                                                <option value="dietoloq-43">Dietoloq</option>-->
                                        <!--                                            </select>-->
                                        <!--                                        </div>-->
                                        <div class="doctor-lists">
                                            <div class="row">
                                                <?php
                                                foreach ($doctors as $val) {
                                                    $experience = date("Y") - $val['experience1'];
                                                    if (!empty($val['photo'])) {
                                                        $photo = Functions::getUploadUrl() . Yii::$app->params['path.doctor'] . '/small/' . $val['photo'];
                                                    } else {
                                                        if (isset($val['gender']))
                                                            $photo = ($val['gender'] == 0 ? Yii::$app->params['site.defaultThumbDoctorF'] : Yii::$app->params['site.defaultThumbDoctor']);
                                                        else
                                                            $photo = Yii::$app->params['site.defaultThumbDoctor'];
                                                    }

                                                    $spc_list = null;

                                                    if (isset($specialists[$val['specialist_id']]) && !empty($specialists[$val['specialist_id']])) {
                                                        $spc_list[] = $specialists[$val['specialist_id']];
                                                        //print_r($spc_list);
                                                        $link = Yii::$app->params['site.url'] . Functions::getDoctorLink($spc_list, $val['id'], $val['slug']);
                                                    }

                                                    ?>
                                                    <div class="col-md-6">
                                                        <div class="list-element">
                                                            <div class="list-img relative">
                                                                <a href="<?= $link ?>">
                                                                    <img src="<?= $photo ?>"
                                                                         alt="<?= Functions::getCleanText($val['name']) ?>"
                                                                         class="img-responsive">
                                                                </a>
                                                                <!--                                                                    <span class="premium-doctor">Premium</span>-->
                                                            </div>
                                                            <div class="list-text">
                                                                <p class="list-name"><a
                                                                            href="<?= $link ?>"><?= Functions::getCleanText($val['name']) ?>
                                                                </p></a>
                                                                <p class="list-profession">
                                                                    <?php
                                                                    if (!isset($spc_list[1])) {
                                                                        $spc_link = Yii::$app->params['site.url'] . Functions::slugify($spc_list[0]['name']) . '-' . $spc_list[0]['id'];
                                                                        echo "<a class='info-major' href=\"{$spc_link}\">{$spc_list[0]['name']}</a> ";
                                                                    } else {
                                                                        foreach ($spc_list as $key => $valS) {
                                                                            $spc_link = Yii::$app->params['site.url'] . Functions::slugify($valS['name']) . '-' . $valS['id'];
                                                                            echo "<a class='info-major' href=\"{$spc_link}\">{$valS['name']}</a> ";
                                                                        }
                                                                    }
                                                                    ?>
                                                                </p>
                                                                <p class="list-practice"><?= $experience ?> il iş
                                                                    təcrübəsi</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="all-news">
                                            <a href="" class="btn btn-bottom">Bütün həkimlərə bax</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <?= ($class2 == 3) ? $this->render('sidebar_clinics') : "" ?>

                    <?= $this->render('_right', ["promotions" => $promotions, "enterprise" => $enterprise, "getComments" => $getComments, "certificates" => $certificates, "class" => $class2]) ?>
                </div>
            </div>
        </div>
</section>


<?php // $this->render('/layouts/partials/modals/comment_modal', ["comment" => $comment]) ?>


