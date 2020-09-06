<?php
$static=$data['static'];
?>
<section class="contact about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?=Yii::$app->params['site.url']?>">Ana səhifə </a></li>
                        <li class="active">Əlaqə</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="contact-content">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="heading">ƏLAQƏ VASİTƏLƏRİ</h4>
                            <div class="row">
                                <div class="address">
                                    <div class="contactIconContainer">
                                        <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                    </div>
                                    <div>
                                        <p>Nəcəfqulu Rəfiyev küçəsi 11/45</p>
                                    </div>
                                </div>
                                <div class="address">
                                    <div class="contactIconContainer">
                                        <span><i class="fa fa-phone" aria-hidden="true"></i></span>
                                    </div>
                                    <div>
                                        <p> 012 488 35 79</p>
                                    </div>
                                </div>
                                <div class="address">
                                    <div class="contactIconContainer">
                                        <span><i class="fa fa-mobile" aria-hidden="true"></i></span>
                                    </div>
                                    <div>
                                        <p>077 564 38 88</p>
                                    </div>
                                </div>
                                <div class="address">
                                    <div class="contactIconContainer">
                                        <span><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                    </div>
                                    <div>
                                        <p>info@e-tibb.az</p>
                                    </div>
                                </div>
                                <div class="work-hours">
                                    <h4>İş saatları</h4>
                                    <div class="address">
                                        <div class="contactIconContainer">
                                            <span><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                                        </div>
                                        <div>
                                            <p>Həftə içi - 10:00-19:00</p>
                                        </div>
                                    </div>
                                    <div class="address">
                                        <div class="contactIconContainer">
                                            <span><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                                        </div>
                                        <div>
                                            <p>Şənbə - 10:00-15:00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 contact-form">
                            <h4 class="heading">BİZƏ YAZIN</h4>

                            <div class="alert alert-danger" style="display: none;">
                                <ul>

                                </ul>
                            </div>

                            <div class="alert alert-success" style="display: none;">
                            </div>

                            <?php
                            $form = \yii\widgets\ActiveForm::begin([
                                'action' => ['ajax/contact'],
                                'options'=>[
                                    'id' => "contact_form_modal",
                                ]
                            ]);
                            ?>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="input-group input-border">
                                        <input type="text" name="SiteContact[name]" class="form-control" placeholder="Ad, Soyad *">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="input-group input-border">
                                        <input type="text" name="SiteContact[email]" class="form-control" placeholder="E-mail *">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group input-border">
                                        <input type="text" name="SiteContact[title]" class="form-control" placeholder="Mövzu *">
                                    </div>
                                    <div class="input-group input-border">
                                        <textarea name="SiteContact[text]" id="message" class="form-control" cols="30" rows="10" placeholder="Mesaj *"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-effect">GÖNDƏR</button>
                                </div>
                            </div>
                            <?php
                            \yii\widgets\ActiveForm::end();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="map">

        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3038.965862507148!2d49.8746768!3d40.3874492!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d9abae43b43%3A0x1b5607673c8ac513!2sE-tibb.az!5e0!3m2!1sen!2s!4v1580912826547!5m2!1sen!2s" width="100%" height="430" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
    </div>
</section>