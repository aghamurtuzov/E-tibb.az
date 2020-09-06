<?PHP

use yii\helpers\Url;
use backend\components\Functions;
use frontend\models\SiteCalling;
use api\models\SiteSocialLinksModel;

$doctor = $data['doctor'];
$classPremium = null;

$currentLink = Functions::getDoctorLink($data['specialist'], $doctor['id'], $doctor['name']);

Yii::$app->view->registerLinkTag(["rel" => "canonical", "href" => $currentLink]);

$this->title = $doctor['name'] . ' - ' . $currentTab['name'];

if (!empty($doctor['photo'])) $photo = Functions::getUploadUrl() . Yii::$app->params['path.doctor'] . '/small/' . $doctor['photo'];
else {
    $photo = ($doctor['gender'] == 0 ? Yii::$app->params['site.defaultThumbDoctorF'] : Yii::$app->params['site.defaultThumbDoctor']);
}

if (Yii::$app->params['current.date'] <= $doctor['expires']) {
    $classPremium = 'premium';
}

$experience = date('Y') - $doctor['experience1'];

$fb_link = 'https://www.facebook.com/sharer/sharer.php?u=' . Yii::$app->params["site.url"] . $currentLink;

$gp_link = 'https://plus.google.com/share?url=' . Yii::$app->params["site.url"] . $currentLink;

$tw_link = 'https://twitter.com/home?status=' . Yii::$app->params["site.url"] . $currentLink;

$title = $doctor['name'];
$lead = $data['specialist'][0]['name'];
$imageInfo = @getimagesize($photo);
$imageWidth = isset($imageInfo[0]) ? $imageInfo[0] : 200;
$imageHeight = isset($imageInfo[1]) ? $imageInfo[1] : 200;
$catName = $data['specialist'][0]['name'];
$appointment = new SiteCalling();
?>

<?php
$buttonWidth = 150;
$style = 6;
$column = '';

if (!Yii::$app->user->isGuest) {
    if (Yii::$app->request->get('id') == Yii::$app->session->get('userID')) {
        $buttonWidth = 100;
        $style = 6;
        $column = '';
        $question = '';
        $comment = '';
    }
    else{
        $buttonWidth = 150;
        $style = 4;
        $column = '<div>
                                <button type="button" style="background: #7ace7a;" class="btn btn-effect rendezvous">Görüş al</button>
                            </div>';
    }
} else {
    $buttonWidth = 100;
    $style = 4;
    $column = '<div>
                                <button type="button" style="background: #7ace7a;" class="btn btn-effect" data-toggle="modal" data-target="#login">Görüş al</button>
                            </div>';
}


?>
<section class="doctor-page about">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <?PHP
                        if (isset($data['breadcrumb'])) {
                            $count = count($data['breadcrumb']);
                            foreach ($data['breadcrumb'] as $key => $val) {
//                                $bActive = ($key+1) == $count ? 'active' : null;
//                                if(empty($bActive))
//                                {
//                                    echo "<li><a href=\"{$val['link']}\">{$val['name']}</a></li>";
//                                }else{
//                                    echo "<li>{$val['name']}</li>";
//                                }

                                echo "<li><a href=\"{$val['link']}\">{$val['name']}</a></li>";
                            }
                        }
                        ?>
                        <li class="active"><?= $doctor['name']; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-7 col-xs-12">
                        <div class="doctor-right mobile">
                            <div class="changeable-part" id="changeable-part">
                                <div class="doctor-appointment ">
                                    <div class="doctor-about block-back ">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="doctor-buttons-list">
                                                    <div>
                                                        <button type="button" class="btn-effect ask-question">Sual ver</button>
                                                    </div>
                                                    <?= $column ?>
                                                    <div>
                                                        <button type="button" class="btn btn-effect comment">Rəy bildir</button>
                                                    </div>
                                                </div>

<!--                                                <div class="row row-doctor-mobile-buttons">-->
<!--                                                    <div class="col-sm---><?//= $style ?><!-- col-xs-12 text-left">-->
<!--                                                        <button style="width: 100%;" type="button"-->
<!--                                                                class="btn-effect ask-question">Sual ver-->
<!--                                                        </button>-->
<!--                                                    </div>-->
<!--                                                    --><?//= $column ?>
<!--                                                    <div class="col-sm---><?//= $style ?><!-- col-xs-12 text-right">-->
<!--                                                        <button style="width: 100%;" type="button"-->
<!--                                                                class="btn btn-effect comment">Rəy bildir-->
<!--                                                        </button>-->
<!--                                                    </div>-->
<!--                                                </div>-->

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="send-question ">
                                    <div class="block-back doctor-about">
                                        <div class="row">
                                            <div class="col-xs-6 text-left">
                                                <button type="button" class="btn btn-all">Bağla</button>
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <h5>Sual göndərin</h5>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-list">
                                                    <div class="alert alert-danger">
                                                        <ul>

                                                        </ul>
                                                    </div>

                                                    <div class="alert alert-success">
                                                    </div>
                                                    <?PHP

                                                    use \yii\widgets\ActiveForm;

                                                    $form = ActiveForm::begin([
                                                        'action' => ['ajax/consultation'],
                                                        'options' => [
                                                            'id' => "consultation_form_modal",
                                                        ]
                                                    ]);
                                                    ?>

                                                    <style>
                                                        div.send-question div.alert, div.send-question div.success {
                                                            display: none;
                                                        }

                                                        div.send-question div.alert-danger ul {
                                                            color: #721c24;
                                                        }
                                                    </style>

                                                    <div class="input-group">
                                                        <label>
                                                            <input type="text" name="SiteConsultation[name]"
                                                                   class="form-control" placeholder="Ad, Soyad *">
                                                        </label>
                                                    </div>
                                                    <div class="input-group">
                                                        <label>
                                                            <input type="text" name="SiteConsultation[email]"
                                                                   class="form-control" placeholder="E-mail *">
                                                        </label>
                                                    </div>
                                                    <input type="hidden" name="SiteConsultation[doctor_id]"
                                                           value="<?= $doctor['id'] ?>"/>
                                                    <input type="hidden" name="SiteConsultation[q_datetime]"
                                                           value="<?= date("Y-m-d H:i:s") ?>"/>
                                                    <input type="hidden" name="SiteConsultation[status]" value="0"/>
                                                    <div class="input-group">
                                                        <textarea name="SiteConsultation[question]" class="form-control"
                                                                  placeholder="Mövzu *"></textarea>
                                                    </div>
                                                    <button type="submit" class="btn-effect ask-question">Göndər
                                                    </button>
                                                    <?php ActiveForm::end(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="comments ">
                                    <div class="block-back doctor-about">
                                        <div class="row">
                                            <div class="col-xs-6 text-left">
                                                <button type="button" class="btn btn-all"
                                                        style="border: 1px solid #00bfb2;">Bağla
                                                </button>
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <h5>Rəy bildirin</h5>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="form-list">
                                                    <div class="alert alert-danger">
                                                        <ul>

                                                        </ul>
                                                    </div>

                                                    <div class="alert alert-success">
                                                    </div>
                                                    <?PHP

                                                    $form = ActiveForm::begin([
                                                        'action' => ['ajax/thanks'],
                                                        'options' => [
                                                            'id' => "comment_form_modal",
                                                        ]
                                                    ]);
                                                    ?>

                                                    <style>
                                                        div.comments div.alert, div.comments div.success {
                                                            display: none;
                                                        }

                                                        div.comments div.alert-danger ul {
                                                            color: #721c24;
                                                        }

                                                        form#comment_form_modal p.rate i {
                                                            cursor: pointer;
                                                        }
                                                    </style>

                                                    <div class="input-group">
                                                        <label>
                                                            <input type="text" name="SiteComments[name]"
                                                                   class="form-control" placeholder="Ad, Soyad *">
                                                        </label>
                                                    </div>
                                                    <div class="input-group">
                                                        <label>
                                                            <input type="text" name="SiteComments[email]"
                                                                   class="form-control" placeholder="E-mail *">
                                                        </label>
                                                    </div>
                                                    <input type="hidden" name="SiteComments[connect_id]"
                                                           value="<?= $doctor['id'] ?>"/>
                                                    <input type="hidden" name="SiteComments[datetime]"
                                                           value="<?= date("Y-m-d H:i:s") ?>"/>
                                                    <input type="hidden" name="SiteComments[status]" value="0"/>
                                                    <div class="input-group">
                                                        <textarea name="SiteComments[comment]" class="form-control"
                                                                  placeholder="Rəy *"></textarea>
                                                    </div>
                                                    <div class="input-group">
                                                        <p class="rate">
                                    <span class="rating">
                                        <?php
                                        for ($i = 1; $i <= 5; $i++) {
                                            ?>
                                            <i data-rating="<?= $i ?>" class="fa fa-star <?= $i == 1 ? "active" : "" ?>"
                                               aria-hidden="true"></i>
                                            <?php
                                        }
                                        ?>
                                    </span>
                                                        </p>
                                                    </div>
                                                    <input type="hidden" class="rating_val" name="SiteComments[rating]"
                                                           value="1"/>
                                                    <button type="submit" class="btn-effect ask-question"
                                                            id="btn_comment">Göndər
                                                    </button>
                                                    <?php ActiveForm::end(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php

                                use \frontend\models\WorkDaysModel;

                                $doc_id = Yii::$app->request->get('id');
                                $date_y = date('Y-m-d');
                                $w_model = new WorkDaysModel();
                                if (!empty($doc_id)) {
                                    $workdays = $w_model->getUserWorkday($doc_id, $date_y);
                                    if (!empty($workdays))
                                        $workdays = explode(',', $workdays['workdays']);
                                    else
                                        $workdays = array();
                                    $closed_times = SiteCalling::getSuitTimes($doc_id, $date_y);

                                    $closed_time = array();
                                    if (!empty($closed_times)) {
                                        foreach ($closed_times as $ct) {
                                            $closed_time[] = $ct->time;
                                        }
                                    }
                                } else {
                                    $workdays = array();
                                    $closed_times = array();
                                }


                                $template = ['template' => "{label}{input}{hint}<div class=\"help-block help-block-error\">{error}</div>"];
                                $hiddenTemplate = ['template' => "{input}"];
                                ?>
                                <div class="make-appointment">
                                    <div class="block-back doctor-about">
                                        <div class="row">
                                            <div class="col-xs-6 text-left">
                                                <h5>Randevu al</h5>
                                            </div>
                                            <div class="col-xs-6 text-right">
                                                <button class="btn btn-all" style="border: 1px solid green;">Bağla
                                                </button>
                                            </div>

                                            <style>
                                                div.make-appointment div.alert, div.make-appointment div.success {
                                                    display: none;
                                                }

                                                div.make-appointment div.alert-danger ul {
                                                    color: #721c24;
                                                }
                                            </style>

                                            <div class="col-xs-12">
                                                <div class="alert alert-danger">
                                                    <ul>

                                                    </ul>
                                                </div>

                                                <div class="alert alert-success">
                                                </div>

                                                <?php
                                                $form = ActiveForm::begin([
                                                    'action' => ['ajax/save-appoint'],
                                                    'enableClientScript' => false,
                                                    'options' => [
                                                        'id' => "appointment_form_modal",
                                                        'data-type-id' => $doc_id
                                                    ]
                                                ]);
                                                ?>
                                                <div class="get-date">
                                                    <div id="datepicker" data-date=" "></div>
                                                    <input type="hidden" id="my_hidden_input" name="reservationDate"
                                                           value="<?= date("m/d/Y") ?>" class="reservationDate">
                                                    <input type="hidden" class="doc_id" name="doctor_id"
                                                           value="<?= $doc_id ?>">
                                                    <input type="hidden" name="user_id"
                                                           value="<?= Yii::$app->user->id ?>">
                                                </div>
                                                <?php
                                                echo '<div class="get-hours">';

                                                if (empty($workdays)) {
                                                    echo '<p style="text-align: center; font-size: 20px; color: red;">İş günü deyil</p>';
                                                } else {

                                                    foreach ($workdays as $val) {
                                                        if (in_array($val, $closed_times)) {
                                                            echo '<span class="disabled">' . $val . '</span>';
                                                        } else {
                                                            echo '<span>' . $val . '</span>';
                                                        }
                                                    }
                                                }

                                                echo '</div>';
                                                ?>

                                                <input type="hidden" id="my_hidden_input" name="workTime"
                                                       class="reservationHour">
                                            </div>
                                            <div class="col-xs-12 text-right">
                                                <button type="submit" class="btn btn-effect ask-question">Göndər
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                ActiveForm::end();
                                ?>
                            </div>
                        </div>
                        <div class="doctor-about block-back ">
                            <!-- haqqinda -->
                            <div class="about-top">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="doctor-specialty user">
                                            <img src="<?= Yii::$app->params['site.url'] ?>upload/doctors/small/<?= $data['doctor']['photo'] ?>"
                                                 alt="doctor-speciality" class="img-responsive">
                                            <div class="preferences">
                                                <h4>Dr. <?= $doctor['name']; ?></h4>
                                                <p><?= $lead; ?></p>
                                                <p><?= $experience; ?> il iş təcrübəsi</p>
                                                <?php
                                                $ratings = \frontend\models\SiteDoctorsModel::getRatingDoctor($doctor['id']);

                                                if ($ratings) {
                                                    $rating_val = round($ratings['sum_rating'] / $ratings['count_rating']);
                                                } else {
                                                    $rating_val = 1;
                                                }
                                                ?>
                                                <p class="rate">
                                                    <span class="rating">
                                                        <?php
                                                        for ($i = 1; $i <= $rating_val; $i++) {
                                                            ?>
                                                            <i class="fa fa-star active" aria-hidden="true"></i>
                                                            <?php
                                                        }
                                                        for ($j = 1; $j <= 5 - $rating_val; $j++) {
                                                            ?>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <?php
                                                        }
                                                        ?>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr class="mb-0">
                            <!-- Kontakt -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="about-body">
                                        <h5>İş yerləri</h5>
<!--                                        <div class="doctor-apply doctor-workplaces">-->
<!--                                            <div class="addressContainer">-->
<!--                                                <div class="address">-->
<!--                                                    <div class="contactIconContainer">-->
<!--                                                        <span><i class="fa fa-briefcase" aria-hidden="true"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <div>-->
<!--                                                        <p>Ish yerim 1</p>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                                <div class="address">-->
<!--                                                    <div class="contactIconContainer">-->
<!--                                                        <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <div>-->
<!--                                                        <p>Nəcəfqulu Rəfiyev küçəsi 11/45</p>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="addressContainer">-->
<!--                                                <div class="address">-->
<!--                                                    <div class="contactIconContainer">-->
<!--                                                        <span><i class="fa fa-briefcase" aria-hidden="true"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <div>-->
<!--                                                        <p>Ish yerim 1</p>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                                <div class="address">-->
<!--                                                    <div class="contactIconContainer">-->
<!--                                                        <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <div>-->
<!--                                                        <p>Nəcəfqulu Rəfiyev küçəsi 11/45</p>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                            <div class="addressContainer">-->
<!--                                                <div class="address">-->
<!--                                                    <div class="contactIconContainer">-->
<!--                                                        <span><i class="fa fa-briefcase" aria-hidden="true"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <div>-->
<!--                                                        <p>Ish yerim 1</p>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                                <div class="address">-->
<!--                                                    <div class="contactIconContainer">-->
<!--                                                        <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>-->
<!--                                                    </div>-->
<!--                                                    <div>-->
<!--                                                        <p>Nəcəfqulu Rəfiyev küçəsi 11/45</p>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div class="doctor-apply">
                                            <div class="online-pharmacy">
                                                <!--                                                <a href="" class="btn btn-all">-->
                                                <!--                                                    Online-->
                                                <!--                                                </a>-->
                                                <?php
                                                $workPlaces = \frontend\models\SiteDoctorsModel::getWorkplaces($doctor['id']);

                                                $i = 1;
                                                $workName = $workAdress = '';
                                                foreach ($workPlaces as $val) {
                                                    $workName .= "<p>" . $val['name'];
                                                    if (count($workPlaces) > 1 && $i < count($workPlaces))
                                                        $workName .= ", ";

                                                    $workName .= "</p>";

                                                    $workAdress .= "<span>" . $val['address'];
                                                    if (count($workPlaces) > 1 && $i < count($workPlaces))
                                                        $workAdress .= ", ";

                                                    $workAdress .= "</span>";

                                                    $i++;
                                                }

                                                echo $workName;
                                                ?>
                                            </div>
                                            <?php
                                            echo "<span>" . $workAdress . "</span>";
                                            ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="about-body">
                                        <h5>Haqqında</h5>
                                        <?= $doctor['about'] ?>
                                    </div>
                                    <hr>
                                    <div class="doctor-phone">
                                        <h5>Telefon</h5>
                                        <ul class="list-inline">
                                            <?php
                                            $phones = \frontend\models\SitePhoneNumbersModel::getPhones($doctor['id'], 1);
                                            foreach ($phones as $val) {
                                                if ($val['number_type'] == 2)
                                                    continue;
                                                ?>
                                                <?php if ($val['number_type'] == 1) { ?>
                                                <li class="mobile_phone">
                                                    <div>
                                                        <div class="contactIconContainer">
                                                            <span><i class="fa fa-mobile" aria-hidden="true"></i></span>
                                                        </div>
                                                        <a href="tel:<?= $val['number'] ?>"><?= $val['number'] ?></a>
                                                    </div>
                                                </li>
                                                <?php } ?>
                                                <?php if ($val['number_type'] == 0) { ?>
                                                    <li>
                                                        <div>
                                                            <div class="contactIconContainer">
                                                                <span><i class="fa fa-phone" aria-hidden="true"></i></span>
                                                            </div>
                                                            <a href="tel:<?= $val['number'] ?>"><?= $val['number'] ?></a>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <!-- /Kontakt -->
                            <!-- Sosial -->
                            <div class="row">
                                <div class="about-bottom">
                                    <?php
                                    $getSocialLinks = SiteSocialLinksModel::getSocialLinks($doctor['id'], 1);
                                    $wp_phone = \frontend\models\SitePhoneNumbersModel::getPhones($doctor['id'], 1);

                                    if (count($getSocialLinks) > 0 || count($wp_phone) > 0) {
                                        ?>
                                        <div class="col-md-6 col-sm-6">
                                            <h5>Sosial şəbəkəsi</h5>

                                            <ul class="list-inline social-icons">
                                                <?php
                                                $socialArr = [0 => 'facebook', 1 => 'instagram', 2 => 'youtube', 3 => 'twitter', 4 => 'linkedin'];

                                                foreach ($getSocialLinks as $links) {
                                                    $socialLink = trim($links['link']);
                                                    if (!empty($socialLink)) {
                                                        ?>
                                                        <li>
                                                            <a target="_blank" href="<?= trim($links['link']) ?>"><span><i
                                                                            class="fa fa-<?= $socialArr[$links['link_type']] ?>"
                                                                            aria-hidden="true"></i></span></a>
                                                        </li>
                                                        <?php
                                                    }

                                                }

                                                $wp_phone = \frontend\models\SitePhoneNumbersModel::getPhones($doctor['id'], 1);

                                                foreach ($wp_phone as $val) {
                                                    if ($val['number_type'] == 2) {
                                                        ?>
                                                        <li>
                                                            <a target="_blank"
                                                               href="https://api.whatsapp.com/send?phone=<?= $val['number'] ?>"><span><i
                                                                            class="fa fa-whatsapp"
                                                                            aria-hidden="true"></i></span></a>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="col-md-6 col-sm-6">
                                        <h5>E-mail</h5>
                                        <div class="doctor-mail">
                                            <div class="contactIconContainer">
                                                <span><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                            </div>
                                            <a href="mailto:<?= $doctor['email'] ?>"><?= $doctor['email'] ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Sosial -->
                        </div>

                        <div class="mobile">
                            <div class="doctor-about block-back doctor-data">
                                <div class="row">
                                    <?php
                                    $degree = \frontend\models\SiteDoctors::getDegree();
                                    ?>
                                    <div class="about-top">
                                        <div class="col-md-12">
                                            <h5>Təhsil</h5>
                                            <p><?= $degree[$doctor['degree']] ?></p>
                                        </div>
                                    </div>
                                    <!--                <hr>-->
                                    <!--                <div class="about-top">-->
                                    <!--                    <div class="col-md-12">-->
                                    <!--                        <h5>Kurs</h5>-->
                                    <!--                        <p>Lorem ipsum dolor sit</p>-->
                                    <!--                    </div>-->
                                    <!--                </div>-->
                                    <hr>
                                    <div class="about-top">
                                        <div class="col-md-12">
                                            <h5>Xidmətlər</h5>
                                            <ul>
                                                <?php
                                                $specialists = \frontend\models\SiteDoctorsModel::getDoctorSpecialist($doctor['id']);

                                                foreach ($specialists as $val) {
                                                    ?>
                                                    <li><p><?= $val['name'] ?></p></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <?PHP echo $this->render('quick_chat', ['doctor' => $doctor, 'data' => $quickChat, 'certificates' => $certificates_data]); ?>

                        <!-- reyler -->
                        <?PHP echo $this->render('_comments', ['doctor' => $doctor, 'data' => $comments_data]); ?>

                    </div>
                    <div class="col-md-5 col-xs-12">
                        <?PHP echo $this->render('doc_right', ['doctor' => $doctor, 'data' => $sidebar_data]); ?>
                        <!-- sag ust -->

                        <?PHP echo $this->render('doc_head_right', ['doctor' => $doctor, 'data' => $sidebar_data]); ?>
                    </div>

                </div>
                <!-- /sag ust -->


                <!-- /reyler -->
            </div>
            <!-- blog -->
            <?PHP echo $this->render('doctor_blog_post', ['doctor' => $doctor, 'data' => $blog_data]); ?>
            <!-- blog -->
        </div>
    </div>
</section>

