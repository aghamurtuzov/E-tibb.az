<?php
use yii\helpers\Url;
$static=$data['static'];
?>
<section class="about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?=Url::base('https');?>">Ana səhifə </a></li>
                        <li class="active">Donor olmaq üçün nə etməli?</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="about-content">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="heading">Donor olmaq üçün nə etməli?</h4>
                            <p class="premium-text">
                                Müvafiq tibbi qaydalara görə, qan donoru olmaq istəyən şəxsin çəkisi 50 kq-dan artıq olmalıdır.
                                Aşağı yaş həddi – 18, yuxarı yaş həddi – 65 sayılır. İstənilən halda bu barədə konkret qərarı həkim qəbul edir.
                                Qan verməmişdən 72 saat əvvəl aspirin, analgin No-Şpa və s. kimi qan durulducu dərmanları qəbul etmək tövsiyə edilmir.
                                48 saata kimi alkoqol qəbul etmək olmaz.
                            </p>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-right-2 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="about-infor">
                                                    <div class="col-md-3 col-xs-2">
                                                        <img src="assets/img/medical-history.png" alt="medical-history" class="img-responsive">
                                                    </div>
                                                    <div class="col-md-9 col-xs-10">
                                                        <p>Rahat formada qəbula yazılmaq</p>
                                                    </div>
                                                </div>
                                                <div class="about-infor">
                                                    <div class="col-md-3 col-xs-2">
                                                        <img src="assets/img/medical-history.png" alt="medical-history" class="img-responsive">
                                                    </div>
                                                    <div class="col-md-9 col-xs-10">
                                                        <p>Onlayn konsultasiya</p>
                                                    </div>
                                                </div>
                                                <div class="about-infor">
                                                    <div class="col-md-3 col-xs-2">
                                                        <img src="assets/img/medical-history.png" alt="medical-history" class="img-responsive">
                                                    </div>
                                                    <div class="col-md-9 col-xs-10">
                                                        <p>Həkimə məxsus bloq</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="about-infor">
                                                    <div class="col-md-3 col-xs-2">
                                                        <img src="assets/img/hospital-sign.png" alt="hospital-sign">
                                                    </div>
                                                    <div class="col-md-9 col-xs-10">
                                                        <p>Həkim haqqında geniş məlumat almaq</p>
                                                    </div>
                                                </div>
                                                <div class="about-infor">
                                                    <div class="col-md-3 col-xs-2">
                                                        <img src="assets/img/hospital-sign.png" alt="hospital-sign">
                                                    </div>
                                                    <div class="col-md-9 col-xs-10">
                                                        <p>VİP elan yerləşdirmək</p>
                                                    </div>
                                                </div>
                                                <div class="about-infor">
                                                    <div class="col-md-3 col-xs-2">
                                                        <img src="assets/img/hospital-sign.png" alt="hospital-sign">
                                                    </div>
                                                    <div class="col-md-9 col-xs-10">
                                                        <p>Həkim haqqında geniş məlumat almaq</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="content-img">
                            <img src="assets/img/about-doctors.png"  alt="about-doctors" class="center-block img-responsive">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="about-count">
        <div class="container">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-md-4">
                        <span class="numscroller count-val" data-min='1' data-max='<?=$counts["clinics"]?>' data-delay='8' data-increment='10'><?=$counts["clinics"]?></span>
                        <span class="count-text">KLİNİKALAR</span>
                    </div>
                    <div class="col-md-4">
                        <span class="numscroller count-val" data-min='1' data-max='<?=$counts["doctors"]?>' data-delay='8' data-increment='10'><?=$counts["doctors"]?></span>
                        <span class="count-text">PEŞƏKAR HƏKİM</span>
                    </div>
                    <div class="col-md-4">
                        <span class="numscroller count-val" data-min='1' data-max='<?=$counts["news"]?>' data-delay='8' data-increment='10'><?=$counts["news"]?></span>
                        <span class="count-text">TİBBİ MƏQALƏ</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="overlay-count"></div>
    </div>
    <!-- <div class="container">
        <div class="feedback">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h2 class="heading">BİZƏ TƏŞƏKKÜR EDƏNLƏR</h2>
                </div>
                <?php /*foreach ($thanks as $item){ */?>
                    <div class="col-md-6 col-xs-12">
                        <div class="feedback-body">
                            <span><i class="fa fa-quote-left" aria-hidden="true"></i></span>
                            <p><?/*=$item['comment']*/?></p>
                            <div class="feedback-user relative">
                                <img src="assets/img/user_img.png" alt="user">
                                <div class="feedback-infor">
                                    <h5><?/*=$item['name']*/?></h5>
                                    <span><?/*=$item['specialist_name']*/?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php /*} */?>
            </div>
        </div>
    </div>-->
</section>