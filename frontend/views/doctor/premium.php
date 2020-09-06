<?PHP

use yii\helpers\Url;
use backend\components\Functions;

$this->title = $data['page_title'];
?>
<section class="doctors pharmacy stocks">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="/">Ana səhifə </a></li>
                        <li class="active">Həkimlər</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="block-back">
                                    <div class="form premium-doctors-form">
                                        <div class="form-list">
                                            <select class="form-control selectpicker" name="field" title="Sahə üzrə">
                                                <?PHP
                                                foreach ($data['specialists'] as $key => $val) {
                                                    echo '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <select class="form-control selectpicker" name="ref" title="Klinika üzrə">
                                                <?PHP
                                                foreach ($data['clinics'] as $key => $val) {
                                                    echo '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <div class="input-group">
                                                <label>
                                                    <input type="text" class="form-control"
                                                           placeholder="Həkim adını qeyd edin">
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-effect">Axtarış et</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="block-back">
                                    <h4 class="heading-inner">ƏN SON MÜRACİƏT OLUNAN HƏKİMLƏR</h4>
                                </div>
                            </div>
                            <div class="block">
                                <?PHP
                                if (!empty($data['premium_doctors'])) {
                                    foreach ($data['premium_doctors'] as $key => $doctor) {
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
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <a href="<?= $link ?>">
                                                <div class="block-cover">
                                                    <div class="img-cover">
                                                        <img src="<?= $photo ?>"
                                                             alt="<?= Functions::getCleanText($doctor['name']) ?>"
                                                             class="img-responsive">
                                                    </div>
                                                    <div class="information text-center">
                                                        <p class="info-name">Dr. <?= $doctor['name']; ?></p>
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

                                                        <p class="info-practice"> <?php $exp1 = date('Y') - $doctor['experience1'];
                                                            echo $exp1; ?> il iş təcrübəsi</p>

                                                        <p class="rate">
                                                                    <span class="rating">
                                                                        <?php
                                                                        for ($i = 1; $i <= $doctor['rating']; $i++) {
                                                                            ?>
                                                                            <i class="fa fa-star active"
                                                                               aria-hidden="true"></i>
                                                                            <?php
                                                                        }
                                                                        for ($j = 1; $j <= 5 - $doctor['rating']; $j++) {
                                                                            ?>
                                                                            <i class="fa fa-star"
                                                                               aria-hidden="true"></i>
                                                                            <?php
                                                                        }
                                                                        ?>
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
                                            </a>
                                        </div>
                                        <?PHP
                                    }
                                }
                                ?>
                            </div>
                            <div class="col-xs-12 text-center">
                                <nav aria-label="Page navigation">

                                    <?= \yii\widgets\LinkPager::widget([
                                        'pagination' => $data['pages'],
                                        'maxButtonCount' => 7,
                                        'firstPageLabel' => false,
                                        'lastPageLabel' => false,
                                        'prevPageLabel' => '<span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i> Əvvəl</span>',
                                        'nextPageLabel' => '<span aria-hidden="true">Sonra<i class="fa fa-angle-right" aria-hidden="true"></i></span>',

                                    ]); ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?= $this->render('/layouts/partials/ads_block'); ?>
                        <div class="right-sidebar-images">
                            <a href="<?= Yii::$app->params['site.url'] . 'beledci/turkecare-20'; ?>">
                                <img src="<?= Yii::$app->params['site.url']; ?>assets/img/Turkecare.png" alt="Turkecare"
                                     class="img-responsive">
                            </a>
                        </div>
                        <div class="right-sidebar-images">
                            <a href="<?= Yii::$app->params['site.url'] . 'beledci/veterinar-19'; ?>">
                                <img src="<?= Yii::$app->params['site.url']; ?>assets/img/Veterinar.png" alt="Veterinar"
                                     class="img-responsive">
                            </a>
                        </div>
                        <div class="right-sidebar-images">
                            <a href="<?= Yii::$app->params['site.url'] . 'beledci/aptekler-6'; ?>">
                                <img src="<?= Yii::$app->params['site.url']; ?>assets/img/Aptekler.png" alt="Aptekler"
                                     class="img-responsive">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>