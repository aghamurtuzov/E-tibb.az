<section class="success donate-inner about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="/">Ana səhifə </a></li>
<!--                        <li><a href="index.html">Aksiyalar </a></li>-->
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-9 success-left">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block-back">
                                    <img src="<?=Yii::$app->params['site.url']?>/assets/img/success.png" alt="success" class="img-responsive">
                                    <div class="success-text">
                                        <h3>Təşəkkürlər!</h3>
                                        <?php if(Yii::$app->session->hasFlash("register_success")){
                                            echo '<p>'.Yii::$app->session->getFlash("register_success").'</p>';
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="advert block-back">
                            <h3>Burda reklam
                                yeri</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>