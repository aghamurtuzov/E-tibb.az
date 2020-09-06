<?php

use backend\components\Functions;

if(Yii::$app->session->hasFlash("success")){
    echo '<div class="alert alert-success">'.Yii::$app->session->getFlash("success").'</div>';
}
$data0 = $data[0];
$this->title = 'Profil';
//$photo = (!empty($data0['profile_photo']) ? Functions::getUploadUrl() . $customPath . '/small/' . $data0['profile_photo'] : Yii::$app->params['site.url']."assets/img/user.png");

if(!empty($data0['profile_photo'])) {
    $photoExist = true;
    $photo = Functions::getUploadUrl() . $customPath . '/' . $data0['profile_photo'];
} else {
    $photoExist = false;
    $photo = Yii::$app->params['site.url']."assets/img/user.png";
}
?>

<section class="doctor-page about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="/">Ana səhifə </a></li>
                        <li><a href=""><?= $this->title?> </a></li>
                        <li class="active"><?= $data0['name']?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <div class="user-profile-content doctor-page">
                            <div class="block-back">
                                <div class="profile-top preferences">
                                    <div class="profile-image">
                                        <img class="img-responsive <?=($photoExist==true ? 'photo_exist' : '')?>" src="<?=$photo?>" alt="user-profile">
                                    </div>
                                    <h4><?= $data0['name']?></h4>
                                </div>
                                <div class="profile-bottom doctor-about">
                                    <div class="about-bottom">
                                        <span><i class="fa fa-phone" aria-hidden="true"></i></span><p class="profile-text">Telefon</p>
                                        <p><?=$data0['phone_number']?></p>
                                    </div>
                                    <div class="about-bottom">
                                        <span><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                        <p class="profile-text">E-mail</p>
                                        <p><?=$data0['email']?></p>
                                    </div>
                                    <div class="about-bottom">
                                        <ul class="list-unstyled">
                                            <?php if($data0['birthday']!=null){ ?>
                                            <li>
                                                <span><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                                <p class="profile-text">Doğum tarixi</p>
                                                <p><?=$data0['birthday']?></p>
                                            </li>
                                            <?php }if($data0['gender']!=null){ ?>
                                            <li>
                                                <span><i class="fa fa-user" aria-hidden="true"></i></span>
                                                <p class="profile-text">Cinsi</p>
                                                <p><?=Functions::getGender($data0['gender'])?></p>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <a href="<?=Yii::$app->params['site.url']?>tenzimlemeler" class="btn btn-all">Redaktə et</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 user-profile-body">
                        <div class="doctor-about block-back ">
                            <?php
                                $promocodes = [];
                                foreach ($used_promotions as $promocode) {
                                    $promocodes[] = $promocode['promocode'];
                                }

                                $i=1;
                                $j=0;
                                foreach ($promotions2 as $val) {
                                    if(in_array($val['id']."xx".Yii::$app->user->id,$promocodes)) {
                                        $j++;
                                        continue;
                                    }

                                    ?>
                                    <div class="row <?=$i>2 ? 'more_display_promo' : ''?>">
                                        <div class="col-md-4">
                                            <div class="about-body">
                                                <h5>Promo kod</h5>
                                                <div class="copy-to-clipboard">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="linkToCopy" value="<?=$val['id']."xx".Yii::$app->user->id?>" aria-describedby="basic-addon2" readonly>
                                                        <span class="input-group-addon" id="basic-addon2"><i class="fa fa-files-o" aria-hidden="true"></i> Copy</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8" style="text-align: right;">
                                            <h5 style="padding: 30px; font-size: 15px;"><a target="_blank" href="/<?=Yii::$app->params['site.aksiya'].Functions::slugify($val['headline'],['transliterate' => true]).'-'.$val['id']?>"><?=$val['headline']?></a></h5>
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                }

                                if($j==count($promotions)) {
                                    ?>
                                    <div class='row'><div class='col-md-12'><div class='alert alert-danger' style='margin: 10px;'>Hal-hazırda aktiv promo kodunuz yoxdur</div></div></div>
                                    <?php
                                }
                            ?>
                            <?php
                                if(count($promotions)>2) {
                                    ?>
                                    <div class="all-questions text-center">
                                        <a href="javascript:void(0);" onclick="readMore('read_more_promo', 'more_display_promo', 'Hamısına bax', 'Bağla', 'user-profile-body');" class="btn transparent btn-bottom read_more_promo">Hamısına bax</a>
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>

                        <?php
                            
                            if(count($ownPromotions)>0)
                            {
                                ?>
                                <div class="companies block-back">
                                    <div class="about-top">
                                        <p class="profile-text">Kampaniya və aksiyalar</p>
                                        <span class="right-item"><i class="fa fa-th-large" aria-hidden="true"></i></span>
                                    </div>
                                    <?php
                                    $i=1;
                                    foreach ($ownPromotions as $val)
                                    {
                                        $time = true;
                                        if($val['date_end']>date('Y-m-d')) $time = false;
                                        ?>
                                        <div class="companies-body pharmacy-sales <?=$i>2 ? 'more_display_promo_all' : ''?>">
                                            <div class="user-stocks">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="pharmacy-about">
                                                            <h5><?=$val['headline']?></h5>
                                                            <p>Aksiya etibarlıdır:</p>
                                                            <span class="date-gray"> <?=$val['date_end']?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if($time==true){ ?>
                                                <div class="sale-flag stock-close">
                                                    <div class="flag-content">
                                                        <span>KOMPANİYA BİTİB</span>
                                                    </div>
                                                </div>
                                            <?php }
                                            elseif($val['discount']!=null) { ?>
                                                <div class="sale-flag">
                                                    <div class="flag-content">
                                                        <h3><?=$val['discount']?> <sup>%</sup></h3>
                                                        <span>ENDİRİM</span>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                    <?php
                                        if(count($ownPromotions)>2)
                                        {
                                            ?>
                                            <div class="all-questions text-center">
                                                <a href="javascript:void(0);" onclick="readMore('read_more_promoall', 'more_display_promo_all', 'Hamısına bax', 'Bağla', 'companies');" class="btn transparent btn-bottom read_more_promoall">Hamısına bax</a>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <?php
                            }
                        ?>

                        <div class="companies block-back">
                            <div class="about-top">
                                <p class="profile-text">Randevularım</p>
                                <span class="right-item"><i class="fa fa-table" aria-hidden="true"></i></span>
                            </div>
                            <?php
                                if(count($reserves) > 0)
                                {
                                    $i=1;
                                    foreach ($reserves as $val)
                                    {
                                        $doctorSpec = $val['doctorSpec'];
                                        ?>
                                        <div class="doctor-profile doctor-page <?=$i>2 ? 'more_display_reserve_all' : ''?>">
                                            <div class="row relative">
                                                <div class="col-md-2 col-sm-2 col-xs-3">
                                                    <p><?=$val['date'][0]?></p>
                                                    <h5><?=$val['date'][1]?></h5>
                                                    <span class="date-gray"><?=$val['time']?></span>
                                                </div>
                                                <div class="col-md-10 col-sm-10 col-xs-9">
                                                    <div class="doctor-specialty" onclick="window.location.href='<?=Functions::getDoctorLink($doctorSpec,$val["doctor_id"],$val["slug"])?>'">
                                                        <img src="<?=Yii::$app->params['site.url']?>upload/doctors/small/<?=$val['photo']?>" alt="<?=$val['doctor_name']?>" class="img-responsive">
                                                        <div class="preferences">
                                                            <h4><?=$val['doctor_name']?></h4>
                                                            <p><?=$doctorSpec[0]['name']?></p>
                                                            <h5><span><i class="fa fa-map-marker" aria-hidden="true"></i></span><?=$val['workPlace']?></h5>
                                                        </div>
                                                    </div>
                                                    <div class="view-doctor-profile hidden-xs">
                                                        <a href="<?=Functions::getDoctorLink($doctorSpec,$val['doctor_id'],$val['slug'])?>" class="btn-bottom">Profilinə keç</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                    if(count($reserves)>2) {
                                        ?>
                                        <div class="all-questions text-center">
                                            <a href="javascript:void(0);" onclick="readMore('read_more_reserve_profile', 'more_display_reserve_all', 'Bütün randevulara bax', 'Bağla', 'companies');" class="btn transparent btn-bottom read_more_reserve_profile">Bütün randevulara bax</a>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "<div class='row'><div class='col-md-12'><div class='alert alert-danger' style='margin: 10px;'>Hal-hazırda randevunuz yoxdur</div></div></div>";
                                }
                            ?>
                        </div>
                        <?php
                            if(count($questions))
                            {
                                ?>
                                <div class="doctor-about block-back doctor-questions">
                                    <div class="companies">
                                        <div class="about-top">
                                            <p class="profile-text">Həkimlərə verilən suallarım</p>
                                            <span class="right-item"><i class="fa fa-question-circle" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                    $i=1;
                                                    foreach ($questions as $val)
                                                    {
                                                        ?>
                                                        <div class="doctor-answer <?=$i>2 ? 'more_display_questions_all' : ''?>">
                                                            <div class="doctor-answer-body">
                                                                <div class="about-body">
                                                                    <h5><?=$data0['name']?></h5>
                                                                    <div class="user-question">
                                                                        <p><?=$val['question']?></p>
                                                                        <div class="question-bottom">
                                                                            <span class="date"><i class="fa fa-calendar" aria-hidden="true"></i><?= Functions::getDatetime($val['q_datetime']) ?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="doctor-answer-inner text-right">
                                                                    <button class="transparent show-message ">
                                                                        <h5><?=$val['doctor_name']?></h5>
                                                                        <span class="replied">cavab verdi</span>
                                                                        <span class="date"><i class="fa fa-calendar" aria-hidden="true"></i><?= Functions::getDatetime($val['a_datetime']) ?></span>
                                                                    </button>
                                                                    <div class="message-doctor">
                                                                        <div class="message">
                                                                            <h5><?=$val['doctor_name']?></h5>
                                                                            <span class="replied">cavab verdi</span>
                                                                            <span class="date"><i class="fa fa-calendar" aria-hidden="true"></i><?= Functions::getDatetime($val['a_datetime']) ?></span>
                                                                            <p><?=$val['answer']?></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $i++;
                                                    }
                                                ?>
                                                <?php
                                                    if(count($questions)>2)
                                                    {
                                                        ?>
                                                        <div class="all-questions text-center" style="border-top: 0;">
                                                            <a href="javascript:void(0);" onclick="readMore('read_more_questions_profile', 'more_display_questions_all', 'Bütün sullara bax', 'Bağla', 'doctor-questions');" class="btn transparent btn-bottom read_more_questions_profile">Bütün sullara bax</a>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
