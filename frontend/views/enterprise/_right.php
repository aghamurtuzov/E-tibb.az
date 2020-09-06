<div class="col-md-<?=$class?> col-xs-12 doctor-right">
    <?php

    use api\models\SiteDoctorFilesModel;

    if ($promotions) {

            ?>
            <div class="stocks-here">
                <div class="block-back pharmacy-sales">
                    <div class="stocks-sale-head">
                        <p>Aksiyalar</p>
                        <div class="percent">
                            <h6>Aksiya sayı:</h6>
                            <?php
                                if (count($promotions)>=1)
                                {
                                    ?>
                                    <span><?= count($promotions) ?></span> <img src="<?=Yii::$app->params['site.url'];?>/assets/img/percent.png" alt="percent">
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    <?php
                        foreach ($promotions as $promotion) {

                            $href = \yii\helpers\Url::to(["aksiya/" . \backend\components\Functions::slugify($promotion["headline"], ['transliterate' => true]) . "-" . $promotion["id"]]);
                            if (date("Y-m-d") > $promotion["date_end"]) {
                                $href = 'javascript:void(0);';
                            }

                            ?>
                            <div class="sales-content">
                                <div class="row ">
                                    <div class="col-xs-12">
                                        <div class="pharmacy-about">
                                            <h5><a href="<?= $href ?>"><?= $promotion["headline"] ?></a></h5>
                                            <p>Aksiya etibarlıdır:</p>
                                            <span class="date-gray"> <?= date("d.m.Y", strtotime($promotion["date_end"])) ?></span>
                                            <div class="coin">
                                                <?php if($promotion['discount']){ ?>
                                                    <h4><?= $promotion["price"] - ($promotion["price"] * $promotion["discount"] / 100) ?> <sup>M</sup></h4>
                                                    <h6><?= $promotion["price"] ?> <sup>M</sup></h6>
                                                <?php } else{ ?>
                                                    <h4><?= $promotion["price"] ?> <sup>M</sup></h4>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    if (date("Y-m-d") <= $promotion["date_end"]) {
                                        $promotionExp = true;
                                    }
                                    else
                                    {
                                        $promotionExp = false;
                                    }
                                ?>
                                <div class="sale-flag <?=$promotionExp == false ? "stock-close" : ""?>">
                                    <div class="flag-content">
                                        <?php
                                        if ($promotionExp==true) {
                                            ?>
                                            <h3><?= $promotion["discount"] ?> <sup>%</sup></h3>
                                            <span>ENDİRİM</span>
                                        <?php } else {
                                            ?>
                                            <span>AKSİYA BİTİB</span>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    ?>
                    <div class="all-news">
                        <a href="<?=Yii::$app->params['site.url']?>aksiyalar" class="btn btn-bottom">Bütün aksiyalara bax</a>
                    </div>
                </div>
            </div>
            <?php
        }

    ?>
</div>
<?php

    if(count($getComments)>0)
    {
        ?>
        <div class="col-md-4 col-xs-12 doctor-right">
            <div class="clinic-review doctor-about block-back">
                <div class="clinic-review-head">
                    <div class="row">
                        <div class="col-xs-8">
                            <h5>Klinika haqqında rəylər</h5>
                        </div>
                        <div class="col-xs-4  text-right">
                            <a href="" class="btn btn-bottom">Rəy yaz</a>
                        </div>
                    </div>
                </div>
                <div class="clinic-review-body" style="max-height:250px;">
                    <?php
                    foreach ($getComments as $val)
                    {
                        ?>
                        <div class="clinic-review-text">
                            <p class="clinic-review-heading"><?=$val['name']?></p>
                            <p><?=$val['comment']?></p>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <!--        <div class="all-news">-->
                <!--            <a href="" class="btn btn-bottom">Bütün həkimlərə bax</a>-->
                <!--        </div>-->
            </div>
        </div>
        <?php
    }

    if(count($certificates)>0)
    {
        ?>
        <div class="col-md-4 col-xs-12 doctor-right">
            <div class="doctor-about block-back clinic-doctors">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Sertifikat ve lisenziya</h5>
                        <hr>

                        <?php
                            foreach ($certificates as $val)
                            {
                                ?>
                                <div class="row certificate">
                                    <div class="col-md-4 col-xs-3">
                                        <img src="<?=$val['file_photo_thumb']?>" alt="certificate" class="img-responsive">
                                    </div>
                                    <div class="col-md-8 col-xs-9">
                                        <h5>Sertifikat</h5>
<!--                                        <p>Verilmə tarixi: 2012 avqust</p>-->
                                    </div>
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
