<?php if(!empty($data['certificatesList']) || !empty($data['diplomsList'])) { ?>
<div class="col-md-5 col-xs-12 doctor-right">
    <div class="doctor-about block-back doctor-practice">
        <div class="row">
            <?php if(!empty($data['certificatesList'])) { ?>
            <div class="about-top">
                <div class="col-md-12">
                    <h5>Sertifikatları</h5>
                    <hr>
                    <?php foreach($data['certificatesList'] as $val) { ?>
                        <div class="row certificate">
                            <div class="col-md-4 col-xs-3">
                                <img src="<?=$val['file_photo_thumb']?>" alt="certificate" class="img-responsive">
                            </div>
<!--                                <div class="col-md-8 col-xs-9">-->
<!--                                    <h5>Burda Sertifikatin adı yazılacaq</h5>-->
<!--                                    <p>Verilmə tarixi:  2012 avqust</p>-->
<!--                                </div>-->
                        </div>
                    <?php } ?>
<!--                                <div class="all-questions">-->
<!--                                    <a href="" class="btn transparent btn-bottom">Bütün sertifikatlara bax</a>-->
<!--                                </div>-->
                </div>
            </div>
            <hr>
            <?php } ?>

            <?php if(!empty($data['diplomsList'])) { ?>
                <!-- sertifikatlar -->
                <div class="about-top">
                    <div class="col-md-12">
                        <h5>Diplomlar</h5>
                        <hr>
                        <?php foreach($data['diplomsList'] as $val) { ?>
                            <div class="row certificate">
                                <div class="col-md-4 col-xs-3">
                                    <img src="<?=$val['file_photo_thumb']?>" alt="certificate" class="img-responsive">
                                </div>
<!--                                    <div class="col-md-8 col-xs-9">-->
<!--                                        <h5>Burda Sertifikatin adı yazılacaq</h5>-->
<!--                                        <p>Verilmə tarixi:  2012 avqust</p>-->
<!--                                    </div>-->
                            </div>
                        <?php } ?>
<!--                                <div class="all-questions">-->
<!--                                    <a href="" class="btn transparent btn-bottom">Bütün sertifikatlara bax</a>-->
<!--                                </div>-->
                    </div>
                </div>
                <!-- sertifikatlar -->
                <?php } ?>

<!--                            <div class="about-top">-->
<!--                                <div class="col-md-12">-->
<!--                                    <h5>İş təcrübəsi</h5>-->
<!--                                    <ul>-->
<!--                                        <li><p>ONMAR Klinikasi  2019</p></li>-->
<!--                                        <li><p>ÖMÜR KLİNİKASI  2017</p></li>-->
<!--                                        <li><p>Özel Hasthane  2016</p></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <hr>-->
<!--                            <div class="about-top">-->
<!--                                <div class="col-md-12">-->
<!--                                    <h5>İştirak etdiyi tədbirlər</h5>-->
<!--                                    <ul>-->
<!--                                        <li><p>Tədbirin adı ili burda yazılacaq</p></li>-->
<!--                                        <li><p>Lorem ipsum burda tedbir adi</p></li>-->
<!--                                        <li><p>Həmçinin burda da tədbirin adı yazılacaq</p></li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
        </div>
    </div>
</div>
<?php } ?>