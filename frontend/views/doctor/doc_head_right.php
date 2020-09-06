<?php
$buttonWidth = 150;
$style = 6;
$column = '';
$question = '<button style="width: 100%;" type="button" class="btn-effect ask-question">Sual ver
                                </button>';
$comment = '<button style="width: 100%;" type="button" class="btn btn-effect comment">Rəy bildir
                                </button>';

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
<div class="doctor-right">
    <div class="changeable-part" id="changeable-part">
        <?php if (!empty($question) || !empty($comment)){ ?>
        <div class="doctor-appointment web">
            <div class="doctor-about block-back ">
                <div class="row">
                    <div class="col-md-12">
                        <div class="doctor-buttons-list">
                            <div>
                                <?= $question ?>
                            </div>
                            <?= $column ?>
                            <div>
                                <?= $comment ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="doctor-about block-back doctor-data web web-xs">
            <div class="row">

                <div class="about-top">
                    <div class="col-md-12">
                        <h5>Təhsil</h5>
                        <p><?= isset($data['degree'][$doctor['degree']]) ? $data['degree'][$doctor['degree']] : '' ?></p>
                    </div>
                </div>
                <hr>
                <div class="about-top">
                    <div class="col-md-12">
                        <h5>Xidmətlər</h5>
                        <ul>
                            <?php
                                if(!empty($data['specialists'])) {
                                    foreach ($data['specialists'] as $val)
                                    {
                                        ?>
                                        <li><p><?=$val['name']?></p></li>
                                        <?php
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="send-question web">
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
                                    <input type="text" name="SiteConsultation[name]" class="form-control"
                                           placeholder="Ad, Soyad *">
                                </label>
                            </div>
                            <div class="input-group">
                                <label>
                                    <input type="text" name="SiteConsultation[email]" class="form-control"
                                           placeholder="E-mail *">
                                </label>
                            </div>
                            <input type="hidden" name="SiteConsultation[doctor_id]" value="<?= $doctor['id'] ?>"/>
                            <input type="hidden" name="SiteConsultation[q_datetime]"
                                   value="<?= date("Y-m-d H:i:s") ?>"/>
                            <input type="hidden" name="SiteConsultation[status]" value="0"/>
                            <div class="input-group">
                                <textarea name="SiteConsultation[question]" class="form-control"
                                          placeholder="Mövzu *"></textarea>
                            </div>
                            <button type="submit" class="btn-effect ask-question">Göndər</button>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="comments web">
            <div class="block-back doctor-about">
                <div class="row">
                    <div class="col-xs-6 text-left">
                        <button type="button" class="btn btn-all" style="border: 1px solid #00bfb2;">Bağla</button>
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

                            <?php

                            use frontend\models\SiteCalling;
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
                                    <input type="text" name="SiteComments[name]" class="form-control"
                                           placeholder="Ad, Soyad *">
                                </label>
                            </div>
                            <div class="input-group">
                                <label>
                                    <input type="text" name="SiteComments[email]" class="form-control"
                                           placeholder="E-mail *">
                                </label>
                            </div>
                            <input type="hidden" name="SiteComments[connect_id]" value="<?= $doctor['id'] ?>"/>
                            <input type="hidden" name="SiteComments[datetime]" value="<?= date("Y-m-d H:i:s") ?>"/>
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
                                            <i data-rating="<?= $i ?>" class="fa fa-star <?= $i == 0 ? "active" : "" ?>"
                                               aria-hidden="true"></i>
                                            <?php
                                        }
                                        ?>
                                    </span>
                                </p>
                            </div>
                            <input type="hidden" class="rating_val" name="SiteComments[rating]" value="0"/>
                            <button type="submit" class="btn-effect ask-question">Göndər</button>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="make-appointment web">
            <div class="block-back doctor-about">
                <div class="row">
                    <div class="col-xs-6 text-left">
                        <h5>Randevu al</h5>
                    </div>
                    <div class="col-xs-6 text-right">
                        <button class="btn btn-all" style="border: 1px solid green;">Bağla</button>
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
                            <div id="datepicker__1" data-date=" "></div>
                            <input type="hidden" id="my_hidden_input" name="reservationDate"
                                   value="" class="reservationDate">
                            <input type="hidden" class="doc_id" name="doctor_id" value="<?= $doc_id ?>">
                            <input type="hidden" name="user_id" value="<?= Yii::$app->user->id ?>">
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

                        <input type="hidden" id="my_hidden_input" name="workTime" class="reservationHour">
                    </div>
                    <div class="col-xs-12 text-right">
                        <button type="submit" class="btn btn-effect ask-question">Göndər</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>
</div>