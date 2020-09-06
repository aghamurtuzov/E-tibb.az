<?php
use backend\components\Functions;
use \yii\widgets\ActiveForm;
?>
<div class="row">
    <!-- sual cavab -->
    <div class="col-xs-12">
        <div class="doctor-about block-back doctor-questions">
            <div class="about-top">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Həkimə verilən sullar</h5>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <?php if($data['questionsCount']>0) {
                                $i=1;
                                foreach ($data['questions'] as $val) {
                                    ?>
                                    <div class="doctor-answer <?=$i>2 ? 'more_display_question_all' : ''?>">
                                        <div class="doctor-answer-body">
                                            <div class="about-body">
                                                <h5><?= $val['name'] ?></h5>
                                                <div class="user-question">
                                                    <p><?= $val['question'] ?>
                                                    </p>
                                                    <div class="question-bottom">
                                                        <span class="date"><i class="fa fa-calendar"
                                                                              aria-hidden="true"></i> <?= Functions::getDatetime($val['q_datetime']) ?></span>
                                                        <?php
                                                        if ($data['isCurrentDoctor'] && empty($val['answer'])) {
                                                            ?>
                                                            <button class="btn transparent user-answer-btn">Cavab yaz
                                                            </button>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            if (!empty($val['answer'])) {
                                                ?>
                                                <div class="doctor-answer-inner text-right">
                                                    <button class="transparent show-message ">
                                                        <h5><?= $doctor['name'] ?> </h5>
                                                        <span class="replied">cavab verdi</span>
                                                        <span class="date"><i class="fa fa-calendar"
                                                                              aria-hidden="true"></i> <?= Functions::getDatetime($val['a_datetime']) ?></span>
                                                    </button>
                                                    <div class="message-doctor">
                                                        <div class="message">
                                                            <h5><?= $doctor['name'] ?> </h5>
                                                            <span class="replied">cavab verdi</span>
                                                            <span class="date"><i class="fa fa-calendar"
                                                                                  aria-hidden="true"></i> <?= Functions::getDatetime($val['a_datetime']) ?></span>
                                                            <p><?= $val['answer'] ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>
                                            <style>
                                                div.user-answer-inner div.alert {
                                                    display: none;
                                                }
                                            </style>
                                            <div class="user-answer-inner user-answer-inner_<?= $val['id'] ?>">
                                                <div class="user-message"></div>
                                                <div class="alert alert-danger"></div>
                                                <div class="alert alert-success"></div>
                                                <?PHP
                                                $form = ActiveForm::begin([
                                                    'action' => ['ajax/answer'],
                                                    'options' => [
                                                        'id' => "consultation_answer_form",
                                                        'class' => "relative"
                                                    ]
                                                ]);
                                                ?>
                                                <div class="input-group">
                                                    <textarea name="answer"
                                                              class="form-control answer_input"></textarea>
                                                    <input type="hidden" name="q_id" value="<?= $val['id'] ?>"/>
                                                </div>
                                                <button type="submit" class="btn">Göndər</button>
                                                <?php ActiveForm::end(); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                }
                                ?>
                                <?php
                                    if(count($data['questions'])>2)
                                    {
                                        ?>
                                        <div class="all-questions text-center">
                                            <a href="javascript:void(0);" onclick="readMore('read_more_questionall', 'more_display_question_all', 'Bütün sullara bax', 'Bağla', 'doctor-questions');" class="btn transparent btn-bottom read_more_questionall">Bütün sullara bax</a>
                                        </div>
                                        <?php
                                    }
                            }
                            else { ?>
                                <div class="quick_chat_container">
                                    <div class="alert alert-warning">Hal hazırda sual yoxdur.</div>
                                </div>
                            <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /sual cavab -->
    <!-- sertifikatlar ve ish-->
    <?PHP echo $this->render('certificates',['doctor' => $doctor, 'data' => $certificates]); ?>
    <!-- /sertifikatlar -->
</div>