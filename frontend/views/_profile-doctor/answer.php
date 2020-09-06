<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\helpers\Url;
use frontend\models\SiteCommentsModel;
use frontend\models\SiteConsultationModel;
use backend\components\Functions;
$form = ActiveForm::begin(['enableClientScript' => false,
    'options'=>[
        'enctype' => 'multipart/form-data',
    ]]);
$this->title = 'Sual-Cavab';
$isCurrentDoctor = !Yii::$app->user->isGuest && (Yii::$app->user->identity->id == $doctor_id) ? true : false;
$classNoBorder   = $isCurrentDoctor ? 'no-border': null;
$checkStatus     = !$isCurrentDoctor ? true : false;
?>

<div  class="doc-log">
    <div class="row">
        <div class="col-12">
            <?= $this->render('_tabs', ['model' => $model,"pages" => $pages,"page_type" => $page_type]) ?>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel"aria-labelledby="nav-home-tab">
                    <div class="hint_info">
                    </div>
                    <div class="t-card mini3 -h-top">
                        <?php
                        if(!empty($qa)) {
                            foreach ($qa as $key => $value) {
                                ?>
                                <div class="full-comment" row-id="<?= $value['question_id']; ?>">
                                    <div class="comment" row-id="<?= $value['question_id']; ?>">
                                        <div class="comment-head">
                                            <p class="name"><?= strtoupper($value['user']); ?></p>
                                            <span><?= $value['time']; ?></span>
                                        </div>
                                        <div class="comment-text">
                                            <p><?= $value['question']; ?></p>
                                            <div class="append-comment-input"></div>
                                            <?php if(empty($value['answer'])){ ?>
                                                <button type="button" class="cb shadow cyan d-inline-block reply"
                                                        data-id="<?= $value['question_id']; ?>">Cavabla
                                                </button>
                                                <button type="button" class="cb shadow d-inline-block pink delete"
                                                        data-id="<?= $value['question_id']; ?>">Sil
                                                </button>
                                            <?php }else{ ?>
                                            <button type="button" class="cb shadow d-inline-block pink delete"
                                                    data-id="<?= $value['question_id']; ?>" style="margin-left: 0;">Sil
                                            </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php
                                    //if ($value['a_status']==1) {
                                    if (!empty($value['answer'])) {
                                        ?>
                                        <div class="comment reply-doc">
                                            <div class="comment-head">
                                                <span><?= $value['time2']; ?></span>
                                                <p class="doc-name"><?= strtoupper($value['doctor']); ?></p>
                                            </div>
                                            <div class="comment-text">
                                                <p><?= $value['answer']; ?></p>
                                                <div class="append-comment-input"></div>
                                                <button type="button"
                                                        class="cb shadow cyan d-inline-block edit"
                                                        data-id="<?= $value['question_id']; ?>">
                                                    Redaktə et
                                                </button>
                                                <button type="button"
                                                        class="cb shadow d-inline-block pink delete-doc"
                                                        data-id="<?= $value['question_id']; ?>">
                                                    Sil
                                                </button>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    //}
                                    ?>
                                </div>

                                <?php
                                //}
                            }
                        }else{
                            echo Functions::getFlash("error_message","danger");
                        }
                        ?>
                    </div>
                    <div class="row -h-top-2">
                        <div class="col-md-12 text-center">
                            <div class="t-pagination">
                                <?= \yii\widgets\LinkPager::widget([
                                    'pagination'=>$pagination,
                                    'maxButtonCount' => 7,
                                    'firstPageLabel' => false,
                                    'lastPageLabel' => false,
                                    'prevPageLabel' =>  '<img src="assets/img/arrow-left.png"> Əvvəl',
                                    'nextPageLabel' => 'Sonra <img src="assets/img/arrow-right.png">' ,
                                ]);?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>