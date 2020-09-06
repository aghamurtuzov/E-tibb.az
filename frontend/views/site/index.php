<?PHP

use backend\components\Functions;
use yii\helpers\Url;


$this->title = Yii::$app->params['site.title'];
?>

<section class="carousel-slider">
    <div class="container">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner" role="listbox">
                <?php
                $i = 1;
                foreach ($data['sliders'] as $slider) {
                    ?>
                    <div class="item <?= ($i == 1) ? 'active' : '' ?>">
                        <img class="d-block img-responsive"
                             src="<?= Yii::$app->params['site.url'] ?>upload/slider/<?= $slider['photo'] ?>"
                             alt="<?= $slider['name'] ?>">
                        <a href="<?= $slider['url'] ?>">
                            <div class="carousel-caption">
                                <h2><?= $slider['text1'] ?></h2>
                                <h3><?= $slider['text2'] ?></h3>
                                <p><?= $slider['text3'] ?></p>
                            </div>
                        </a>
                    </div>
                    <?php

                    $i++;
                }
                ?>
                <!--                <div class="item active">-->
                <!--                    <img class="d-block img-responsive" src="assets/img/doctor_img.png" alt="First slide">-->
                <!--                    <div class="carousel-caption">-->
                <!--                        <h2>PROFESSOR</h2>-->
                <!--                        <h3>MURAD <span>ƏLƏKBƏROV</span></h3>-->
                <!--                        <p>Xətai klinikasına Almaniyadan dəvət olunmuş mütəxəssis</p>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--                <div class="item">-->
                <!--                    <img class="d-block img-responsive" src="assets/img/doctor_img.png" alt="First slide">-->
                <!--                    <div class="carousel-caption">-->
                <!--                        <h2>PROFESSOR</h2>-->
                <!--                        <h3>MURAD <span>ƏLƏKBƏROV</span></h3>-->
                <!--                        <p>Xətai klinikasına Almaniyadan dəvət olunmuş mütəxəssis</p>-->
                <!--                    </div>-->
                <!--                </div>-->
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="arrow-left" aria-hidden="true"><img src="assets/img/arrow_left.png"
                                                                 alt="arrow_left"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="arrow-right" aria-hidden="true"><img src="assets/img/arrow_right.png"
                                                                  alt="arrow_right"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</section>
<section class="doctors-infor">
    <div class="container">
        <div class="col-xs-12 filter-part">
            <div class="filter">
                <div class="row">
                    <div class="col-sm-3">
                        <button class="online-advice">
                            <img src="assets/img/camera.png" alt="camera">
                            <span>ONLAYN MƏSLƏHƏTLƏR</span>
                        </button>
                    </div>
                    <form action="<?= Yii::$app->params['site.url'] ?>hekimler" id="search">
                        <div class="col-sm-4 select-part">
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control keyword-selectPart"
                                       placeholder="Xəstəlik və ya açar söz">
                            </div>
                            <!--                            <select class="form-control selectpicker" name="money" title="Xəstəlik və ya açar söz">-->
                            <!--                                <option value="allerqoloq-29" class="">Allerqoloq</option>-->
                            <!--                                <option value="androloq-9">Androloq</option>-->
                            <!--                                <option value="anestezioloq-47">Anestezioloq</option>-->
                            <!--                                <option value="dermatoloq-23">Dermatoloq</option>-->
                            <!--                                <option value="dietoloq-43">Dietoloq</option>-->
                            <!--                            </select>-->
                        </div>
                        <div class="col-sm-3 select-part">
                            <select class="form-control selectpicker" name="category" title="Sahə üzrə">
                                <?PHP
                                foreach ($data['specialists'] as $key => $val) {
                                    echo '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
                                }
                                ?>
                            </select>
                            <!--                            <select class="form-control selectpicker" name="money" title="Şəhər">-->
                            <!--                                <option value="allerqoloq-29">Allerqoloq</option>-->
                            <!--                                <option value="androloq-9">Androloq</option>-->
                            <!--                                <option value="anestezioloq-47">Anestezioloq</option>-->
                            <!--                                <option value="dermatoloq-23">Dermatoloq</option>-->
                            <!--                                <option value="dietoloq-43">Dietoloq</option>-->
                            <!--                            </select>-->
                        </div>
                    </form>
                    <div class="col-sm-2 text-right search_form_submit">
                        <a class="btn search">AXTAR</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center">
                <h2 class="heading">Ən çox müraciət olunan həkimlər</h2>
                <p class="premium-text">Professional həkimləri burada axtarın, qəbula yazılın, sual verin</p>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="sliderBlocks">
                <?php
                //echo '<pre>'; print_r($data['specialists']); exit();
                foreach ($data['vip_doctors'] as $key => $doctor) {
                    $classPremium = null;
                    $spc_names = null;
                    $spc_list = null;
                    $link = 'javascript:void(0);';

                    if (Yii::$app->params['current.date'] <= $doctor['expires']) {
                        $classPremium = 'premium';
                    }
                    /** Doctor Specialists */
                    if (isset($data['specialists'][$doctor['specialist_id']]) && !empty($data['specialists'][$doctor['specialist_id']])) {
                        $spc_list[] = $data['specialists'][$doctor['specialist_id']];
                        //print_r($spc_list);
                        $link = Functions::getDoctorLink($spc_list, $doctor['id'], $doctor['slug']);
                    }
                    /** Photos */
                    if (!empty($doctor['photo'])) $photo = Functions::getUploadUrl() . Yii::$app->params['path.doctor'] . '/' . $doctor['photo'];
                    else {
                        if (isset($doctor['gender']))
                            $photo = ($doctor['gender'] == 0 ? Yii::$app->params['site.defaultThumbDoctorF'] : Yii::$app->params['site.defaultThumbDoctor']);
                        else
                            $photo = Yii::$app->params['site.defaultThumbDoctor'];
                    }
                    /** Feature */
                    $classHomeDoctor = empty($doctor['feature']) || $doctor['feature'] == 1 ? 'Çağırış' : null;
                    $classChildDoctor = empty($doctor['feature']) || $doctor['feature'] == 2 ? 'class="disb"' : null;
                    $classPromotion = empty($doctor['promotion']) ? 'class="disb"' : null;

                    $experience = $doctor['experience'];
                    ?>
                    <div class="block">
                        <div class="slider-all">
                            <div class="slider-image">
                                <img src="<?= $photo ?>" alt="<?= Functions::getCleanText($doctor['name']) ?>">
                            </div>
                            <div class="information text-center">
                                <p class="info-name"><a class="info-name"
                                                        href="<?= $link ?>"> <?= $doctor['name'] ?></a></p>
                                <?PHP
                                if (!empty($spc_list)) {
                                    echo '<p class="info-major">';
                                    if (!isset($spc_list[1])) {
                                        $spc_link = Functions::slugify($spc_list[0]['name']) . '-' . $spc_list[0]['id'];
                                        echo "<a class='info-major' href=\"{$spc_link}\">{$spc_list[0]['name']}</a> ";
                                    } else {
                                        foreach ($spc_list as $key => $val) {
                                            $spc_link = Functions::slugify($val['name']) . '-' . $val['id'];
                                            echo "<a class='info-major' href=\"{$spc_link}\">{$val['name']}</a> ";
                                        }
                                    }
                                    echo '</p>';
                                }
                                ?>
                                <p class="info-practice"><?php $exp1 = date('Y') - $doctor['experience1'] ?> <?= $exp1; ?>
                                    il iş təcrübəsi</p>

                                <p class="rate">
                                        <span class="rating">
                                            <?php
                                            for ($i = 1; $i <= $doctor['rating']; $i++) {
                                                ?>
                                                <i class="fa fa-star active" aria-hidden="true"></i>
                                                <?php
                                            }
                                            for ($j = 1; $j <= 5 - $doctor['rating']; $j++) {
                                                ?>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <?php
                                            }
                                            ?>
<!--                                            <i class="fa fa-star active" aria-hidden="true"></i>-->
                                            <!--                                            <i class="fa fa-star" aria-hidden="true"></i>-->
                                            <!--                                            <i class="fa fa-star" aria-hidden="true"></i>-->
                                            <!--                                            <i class="fa fa-star" aria-hidden="true"></i>-->
                                            <!--                                            <i class="fa fa-star" aria-hidden="true"></i>-->
                                        </span>
                                </p>

                                <h6>
                                    <?php

                                    if ($doctor['feature'] == 1) {
                                       echo $classHomeDoctor;
                                        ?>
                                        <img src="assets/img/emergency.png" alt="emergency">

                                    <?php } ?>

                                </h6>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>

<?PHP

$categID = 37;
$categLimit = 4;

if (isset($data['newsList']['categ'][$categID])) {
    $catName = isset($data['menus']['id'][$categID]['name']) ? $data['menus']['id'][$categID]['name'] : null;
    $newsList = $data['newsList']['categ'][$categID];
    ?>
    <section class="health">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h2 class="heading">Xəbərlər</h2>
                    <p class="premium-text">Tibbə aid bütün yeniliklər, ən son xəbərlər, müsahibələr, reportajlar,
                        mütəxəssis rəyləri</p>
                </div>
                <div class="block col-xs-12 flex-wrap main_page_news">
                    <?PHP
                    for ($x = 0; $x < $categLimit; $x++) {
                        if (isset($newsList[$x])) {
                            $link = yii::$app->params['site.post_uri'] . $newsList[$x]['slug'] . '-' . $newsList[$x]['id'];
                            $photo = !empty($newsList[$x]['photo']) ? Functions::getUploadUrl() . Yii::$app->params['path.news'] . '/small/' . $newsList[$x]['photo'] : Functions::getUploadUrl('assets/img') . Yii::$app->params['news.defaultThumb'];
                            ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <a href="<?= $link ?>">
                                    <div class="block-cover">
                                        <div class="img-cover">
                                            <img src="<?= $photo; ?>" class="img-responsive cover"
                                                 alt="<?= Functions::getCleanText($newsList[$x]['headline']); ?>">
                                            <div class="image-over">
                                                <!--                                                <p class="left-item">-->
                                                <!--                                                    <img src="assets/img/eye.png" alt="eye"> <span>-->
                                                <?//=$newsList[$x]['news_read'];
                                                ?><!--</span>-->
                                                <!--                                                </p>-->
                                                <p class="right-item">
                                                    <img src="assets/img/date.png" alt="date">
                                                    <span><?= Functions::getDatetime($newsList[$x]['datetime'], ['type' => 'date']); ?></span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="information">
                                            <p class="information-text"><?= $newsList[$x]['headline']; ?></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?PHP
                        }
                    }
                    ?>
                </div>
                <div class="col-12 text-center">
                    <a href="<?= yii::$app->params['site.news_uri'] ?>" class="btn btn-all">
                        BÜTÜN YAZILAR
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?PHP
}
?>

<section class="review">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h2 class="heading">ƏN SON RƏYLƏR</h2>
                <p class="premium-text">Həkimdən razı qaldınızmı? Narazılığınız varmı?
                    Həkimlə bağlı təəssüratınız necədir? Rəyinizi yazın</p>
            </div>
            <div class="col-xs-12 relative">
                <div class="row">
                    <?PHP
                    $categID = 34;
                    $categLimit = 5;

                    if ($data['newsListBlog']) {
                        $catName = isset($data['menus']['id'][$categID]['name']) ? $data['menus']['id'][$categID]['name'] : null;
                        $newsList = $data['newsList']['categ'][$categID];
                        ?>
                        <div class="col-md-6 position-left">
                            <div class="articles">
                                <div class="articles-top">
                                    <h3>Bloq</h3>
                                    <a href="<?= yii::$app->params['site.url'] ?>xeberler/bloq-34" class="btn-all">Daha
                                        çox</a>
                                </div>
                                <div class="row">
                                    <div class="article-inner-block main_page_blogs_list">
                                        <?PHP
                                        foreach ($data['newsListBlog'] as $blogValue) {
                                            $link = yii::$app->params['site.post_uri'] . $blogValue['slug'] . '-' . $blogValue['id'];
                                            $photo = !empty($blogValue['photo']) ? Functions::getUploadUrl() . Yii::$app->params['path.news'] . '/small/' . $blogValue['photo'] : Functions::getUploadUrl('assets/img') . Yii::$app->params['news.defaultThumb'];
                                            ?>
                                            <div class="list-articles">
                                                <a href="<?= $link; ?>" class="d-block">
                                                    <div class="col-md-4 col-xs-3">
                                                        <img src="<?= $photo; ?>"
                                                             alt="<?= Functions::getCleanText($blogValue['headline']); ?>"
                                                             class="img-responsive center-block">
                                                    </div>
                                                    <div class="col-md-8 col-xs-9">
                                                        <h4><?= $blogValue['headline']; ?></h4>
                                                    </div>
                                                </a>
                                            </div>
                                            <?PHP
                                        }
                                        ?>
                                    </div>
                                </div>
                                <!--
                                <div class="col-12 text-center health" style="padding: 0;">
                                    <a href="<?= yii::$app->params['site.url'] ?>xeberler/bloq-34" class="btn btn-all">
                                        BÜTÜN BLOQLAR
                                    </a>
                                </div>
                                -->
                            </div>
                        </div>
                        <?PHP
                    }
                    ?>
                    <div class="col-md-6 right-item">
                        <div class="last-reviews">
                            <h3>Ən son rəylər</h3>
                            <ul class="list-unstyled">
                                <?php
                                foreach ($data['comments'] as $comment) { ?>
                                    <li class="review-content">
                                        <p>
                                            <span>
                                                <img src="<?= Yii::$app->params['site.url'] ?>upload/doctors/small/<?= $comment['doctor_image'] ?>"
                                                     alt="<?= $comment['name'] ?>"><?= $comment['doctor_name'] ?>
                                            </span>
                                            <span class="rating">
                                                <?php
                                                for ($i = 1; $i <= $comment['rating']; $i++) {
                                                    ?>
                                                    <i class="fa fa-star active" aria-hidden="true"></i>
                                                    <?php
                                                }
                                                for ($i = 1; $i <= 5 - $comment['rating']; $i++) {
                                                    ?>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <?php
                                                }
                                                ?>
                                            </span>
                                        </p>
                                        <h4>
                                            <?= "<b>" . $comment['name'] . ":</b> " . $comment['comment'] ?>
                                        </h4>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php if (!empty($data['videos'])) { ?>
    <section class="video-interview">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h2 class="heading">VİDEO MÜSAHİBƏ</h2>
                    <!--                <p class="premium-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor-->
                    <!--                    incididunt ut labore et dolore magna aliqua.</p>-->
                </div>
                <div class="col-xs-12 relative">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="video-content">
                                <div class="video-back"
                                     style="background: url(<?= Functions::getUploadUrl() . '/news/' . $data['videos'][0]['photo'] ?>)">
                                    <span class="play"><i class="fa fa-play" aria-hidden="true"></i></span>
                                </div>
                                <h4><?= $data['videos'][0]['headline'] ?></h4>
                                <div class="view-date">
                                    <span><img src="assets/img/eye_gray.png"
                                               alt="eye_gray"><?= $data['videos'][0]['news_read'] ?></span>
                                    <span><img src="assets/img/date_gray.png"
                                               alt="date_gray"><?= $data['videos'][0]['datetime'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 position-right col-xs-12">
                            <div class="video-right">
                                <?php for ($i = 1; $i < count($data['videos']); $i++) { ?>
                                    <div class="list-articles">
                                        <div class="row relative">
                                            <a href="<?= Yii::$app->params['site.post_uri'] . $data['videos'][$i]['slug'] . '-' . $data['videos'][$i]['id'] ?>"
                                               class="d-block">
                                                <div class="col-md-4 col-xs-5">
                                                    <div class="img-block">
                                                        <img src="<?= Functions::getUploadUrl() . '/news/small/' . $data['videos'][$i]['photo'] ?>"
                                                             alt="articles-1" class="img-responsive center-block">
                                                        <span class="play"><i class="fa fa-play" aria-hidden="true"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-xs-7">
                                                    <h4><?= $data['videos'][$i]['headline'] ?></h4>
                                                    <div class="view-date">
                                                        <span><img src="assets/img/eye_gray.png"
                                                                   alt="eye_gray"><?= $data['videos'][$i]['news_read'] ?></span>
                                                        <span><img src="assets/img/date_gray.png"
                                                                   alt="date_gray"><?= Functions::getDatetime($data['videos'][$i]['datetime'], ['type' => 'date']) ?></span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 text-center">
                    <a href="/videolar" class="btn btn-all">
                        BÜTÜN MÜSAHİBƏLƏR
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<section class="sign-in">
    <div class="container">
        <div class="row relative">
            <div class="col-md-6 position-left col-xs-12">
                <div class="sign-left">
                    <h2>Qeydiyyatdan keç</h2>
                    <!--                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>-->
                    <a href="<?= Yii::$app->params['site.url'] ?>hekim-qeydiyyat" class="btn btn-all">Həkim</a>
                    <a href="<?= Yii::$app->params['site.url'] ?>istifadeci-qeydiyyat"
                       class="btn btn-all">İstifadəçi</a>
                    <a href="<?= Yii::$app->params['site.url'] ?>obyekt-qeydiyyat/1" class="btn btn-all">Klinika</a>
                    <a href="<?= Yii::$app->params['site.url'] ?>obyekt-qeydiyyat/6" class="btn btn-all">Aptek</a>
                </div>
            </div>
            <div class="col-md-6 right-item col-xs-12">
                <div class="img-doctors">
                    <img src="assets/img/doctors.png" alt="doctors" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
</section>