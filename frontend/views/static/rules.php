<?php


?>

<section class="rules about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="index.html">Ana səhifə </a></li>
                        <li class="active">Apteklər</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="rules-body">
        <div class="container">
            <div class="row row_equal_height relative tets">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="web-xs h-100">
                        <div class="rules-left h-100">
                            <div class="left-head">
                                <h4>QAYDALAR</h4>
                            </div>
                            <ul class="list-unstyled">
                                <li class="<?= ($slug=='hekim') ? 'active' : '' ?>">
                                    <a href="<?=Yii::$app->params['site.url']?>qaydalar/hekim">Həkimlər</a>
                                </li>
                                <li class="<?= ($slug=='klinika') ? 'active' : '' ?>">
                                    <a href="<?=Yii::$app->params['site.url']?>qaydalar/klinika">Klinikalar</a>
                                </li>
                                <li class="<?= ($slug=='aptek') ? 'active' : '' ?>">
                                    <a href="<?=Yii::$app->params['site.url']?>qaydalar/aptek">Apteklər</a>
                                </li>
                                <li class="<?= ($slug=='aksiya') ? 'active' : '' ?>">
                                    <a href="<?=Yii::$app->params['site.url']?>qaydalar/aksiya">Aksiyalar</a>
                                </li>
                                <!--                            <li>-->
                                <!--                                <a href="">Saytdan istifadə</a>-->
                                <!--                            </li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="mobile mobile-rules">
                        <div class="rules-left">
                            <div class="left-top-mob block">
                                <div class="left-head">
                                    <h4>QAYDALAR</h4>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="active">
                                        <?php
                                        if($slug=='hekim')
                                            echo "Həkimlər";
                                        elseif($slug=='klinika')
                                            echo "Klinikalar";
                                        elseif($slug=="aptek")
                                            echo "Apteklər";
                                        elseif($slug=='aksiya')
                                            echo "Aksiyalar";
                                        else
                                            echo "Həkimlər";
                                        ?>
                                    </span>
                                        <span class="icon-dropdown"><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li class="<?= ($slug=='hekim') ? 'active' : '' ?>">
                                            <a href="<?=Yii::$app->params['site.url']?>qaydalar/hekim">Həkimlər</a>
                                        </li>
                                        <li class="<?= ($slug=='klinika') ? 'active' : '' ?>">
                                            <a href="<?=Yii::$app->params['site.url']?>qaydalar/klinika">Klinikalar</a>
                                        </li>
                                        <li class="<?= ($slug=='aptek') ? 'active' : '' ?>">
                                            <a href="<?=Yii::$app->params['site.url']?>qaydalar/aptek">Apteklər</a>
                                        </li>
                                        <li class="<?= ($slug=='aksiya') ? 'active' : '' ?>">
                                            <a href="<?=Yii::$app->params['site.url']?>qaydalar/aksiya">Aksiyalar</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              <!--  <div class="col-md-3 col-sm-4 col-xs-12 mobile-xs">
                    <div class="brands-left">
                        <div class="left-top-mob block">
                            <div class="left-head relative">
                                <h4>Köməkçi bölmələr</h4>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Qaydalar
                                    <span><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href="rules.html">Qaydalar</a></li>
                                    <li><a href="questions.html">Suallar</a></li>
                                    <li><a href="privacy.html">Gizlilik şərtləri</a></li>
                                    <li><a href="about.html">Haqqımızda</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="col-md-9 col-sm-8 col-xs-12">
                    <div class="rules-right h-100">
                        <div class="row h-100">
                            <div class="col-xs-12 h-100">
                                <div class="panel-group mb-0 h-100" id="accordion">
                                    <?php
                                        if($slug=="hekim")
                                        {
                                            ?>
                                            <div class="panel panel-default h-100">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle collapse" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                                            Həkimlərin istifadə qaydaları
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseOne" class="panel-collapse collapse in ">
                                                    <div class="panel-body">
                                                        Saytın hüquqi şəxsi ilə tərəflər arasında münasibətlər müqavilə ilə tənzimlənir. <br />
                                                        e-tibb.az saytında qeydiyyatdan keçmək istəyən həkim özü barədə məlumatları tələb
                                                        edilən qaydada yerləşdirməlidir. <br />
                                                        Həkim məlumatların doğruluğuna görə (təhsili, ixtisası, iş təcrübəsi, çalışdığı tibb
                                                        müəssisəsi) məsuliyyət daşıyır. <br />
                                                        Həkim diplom, sertifikat (şəhadətnamə) surətlərini müvafiq bölmədə yerləşdirməklə
                                                        saytda qeydiyyatdan keçə bilər. Əks təqdirdə qeydiyyat mümkün olmayacaq. <br />
                                                        Həkim məlumatlarında dəyişiklik etmək istəsə, bu barədə sistemə bildiriş göndərməsi
                                                        vacibdir. <br />
                                                        Sistemin tələb etdiyi qaydalara əməl etmək həkimin öhdəliyidir. <br />
                                                        Saytda qeydiyyat, xüsusi profil yaradılması müvafiq qaydalar əsasında həyata keçirilir. <br />
                                                        Bloq yazıları, müsahibələr, reportajlar, videobloqlar, videomüsahibələr, aksiyalarla bağlı
                                                        məlumatların yerləşdirilməsi müqavilə ilə tənzimlənir. <br />
                                                        Müqavilə şərtlərinə əməl olunmadıqda xəbərdarlıq edilmədən həkim profili bloklanır.
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        elseif($slug=="klinika")
                                        {
                                            ?>
                                            <div class="panel panel-default h-100">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle collapse" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                                            Tibb müəssisləri (Klinikalar) üçün qaydalar
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseTwo" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        Saytın hüquqi şəxsi ilə tərəflər arasında münasibətlər müqavilə ilə tənzimlənir. <br />
                                                        e-tibb.az saytında qeydiyyatdan keçmək istəyən klinika (tibb mərkəzi, xəstəxana,
                                                        poliklinika və s.) məlumatları tələb edilən qaydada yerləşdirməlidir. <br />
                                                        Tibb müəssisəsi məlumatların doğruluğuna görə məsuliyyət daşıyır (Tibb müəssisəsinin
                                                        profili, hansı xidmətləri göstərməsi, iş saatları, ünvan, əlaqə nömrələri, elektron poçt
                                                        ünvanı, sosial şəbəkə hesabları). <br />
                                                        Tibb müəssisəsi lisenziyası, rəsmi qeydiyyatı barədə məlumatları daxil etməsə saytda
                                                        qeydiyyatdan keçirilmir. <br />
                                                        Tibb müəssisəsi məlumatlarında dəyişiklik etmək istəsə, bu barədə sistemə bildiriş
                                                        göndərməsi vacibdir. <br />
                                                        Sistemin tələb etdiyi qaydalara əməl etmək tibb müəssisəsinin öhdəliyidir. <br />
                                                        Saytda qeydiyyat, müəssisənin profilinin yaradılması, müəssisə haqqında yazılar,
                                                        müsahibələr, reportajlar, videoçəkilişlər, aksiyalarla bağlı məlumatların yerləşdirilməsi
                                                        müqavilə ilə tənzimlənir. <br />
                                                        Müqavilə şərtlərinə əməl olunmadıqda xəbərdarlıq edilmədən tibb müəssisəsinin profili
                                                        bloklanır.
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        elseif($slug=="aptek")
                                        {
                                            ?>
                                            <div class="panel panel-default h-100">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle collapse" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                                            Aptek
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseThree" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        Saytın hüquqi şəxsi ilə tərəflər arasında münasibətlər müqavilə ilə tənzimlənir. <br />
                                                        e-tibb.az saytında qeydiyyatdan keçmək istəyən aptek (apteklər şəbəkəsi) barədə
                                                        məlumatlar tələb olunan qaydada yerləşdirilməlidir. <br />
                                                        Aptek məlumatların doğruluğuna görə məsuliyyət daşıyır (Lisenziya, rəsmi qeydiyyat
                                                        haqda məlumatlar, iş saatları, ünvan, əlaqə nömrələri, elektron poçt ünvanı, sosial
                                                        şəbəkə hesabları) <br />
                                                        Aptek məlumatlarında dəyişiklik etmək istəsə, bu barədə sistemə bildiriş göndərmək
                                                        vacibdir. <br />
                                                        Sistemin tələb etdiyi qaydalara əməl etmək aptekin öhdəliyidir. <br />
                                                        Saytda qeydiyyat, müəssisənin profilinin yaradılması, müəssisə haqqında yazılar,
                                                        müsahibələr, reportajlar, videoçəkilişlər, aksiyalarla bağlı məlumatların yerləşdirilməsi
                                                        müqavilə ilə tənzimlənir. <br />
                                                        Müqavilə şərtlərinə əməl olunmadıqda xəbərdarlıq edilmədən aptekin profili bloklanır.
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        elseif($slug)
                                        {
                                            ?>
                                            <div class="panel panel-default h-100">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle collapse" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                                            Aksiyalar
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseFour" class="panel-collapse collapse in">
                                                    <div class="panel-body">
                                                        Həkim, tibb müəssisəsi, şirkət, rəsmi qurumlar saytın hüquqi şəxsi ilə razılaşma,
                                                        müqavilə əsasında tibbi, tibbi-sosial aksiyalar barədə e-tibb.az saytında məlumatları
                                                        yerləşdirə bilər. <br />
                                                        Aksiya keçirən şəxs və ya müəssisə, təşkilat aksiya barədə məlumatların dəqiqliyinə görə
                                                        məsuliyyət daşıyır. <br />
                                                        Aksiyalar barədə məlumat yerləşdirməsi qaydası e-tibb.az saytı tərəfindən
                                                        müəyyənləşdirilir. <br />
                                                        Endirimli aksiyaların qiyməti, qiymət fərqi haqda məlumatlar, aksiyanın davam etmə
                                                        müddətinin saytda yerləşdirilməsi mütləqdir. <br />
                                                        Klinikaların keçirdiyi aksiyada həkim seçmək imkanı, həkim seçimində qiymət fərqi (əgər
                                                        varsa) qeyd olunmalıdır.
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
<!--                            <div class="col-xs-12 text-center">-->
<!--                                <nav aria-label="Page navigation">-->
<!--                                    <ul class="pagination">-->
<!--                                        <li class="disabled prev">-->
<!--                                            <a href="#" aria-label="Previous">-->
<!--                                                <span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i> Əvvəl</span>-->
<!--                                            </a>-->
<!--                                        </li>-->
<!--                                        <li class="active"><a href="#">1</a></li>-->
<!--                                        <li><a href="#">2</a></li>-->
<!--                                        <li><a href="#">3</a></li>-->
<!--                                        <li><a href="#">4</a></li>-->
<!--                                        <li><a href="#">5</a></li>-->
<!--                                        <li class="next">-->
<!--                                            <a href="#" aria-label="Next">-->
<!--                                                <span aria-hidden="true">Sonra<i class="fa fa-angle-right" aria-hidden="true"></i></span>-->
<!--                                            </a>-->
<!--                                        </li>-->
<!--                                    </ul>-->
<!--                                </nav>-->
<!--                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
