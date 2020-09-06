<?php

use \frontend\models\LoginForm;
use \yii\helpers\Html;

$model = new LoginForm();
?>

<div class="sign-modal login modal fade" tabindex="-1" id="login" role="dialog" aria-labelledby="mySmallModalLabel3">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img src="<?=Yii::$app->params['site.url']?>assets/img/close.png" alt="modal-close"></span></button>
                <h3 class="modal-title" id="gridSystemModalLabel3">Hesabınıza daxil olun</h3>
            </div>
            <div class="form-list">
                <div id="modal_body">
                    <?= $this->render('login_form', ["model" => $model]) ?>
                </div>
                <?= Html::button("Daxil ol", ['class' => 'confirm btn btn-effect', 'id' => 'login_submit']); ?>
<!--                <hr>-->
<!--                <ul class="list-inline social">-->
<!--                    <li>-->
<!--                        <a href="#" class="btn btn-fb">-->
<!--                            <span class="social-item"><i class="fa fa-facebook" aria-hidden="true"></i></span>-->
<!--                            <span>Facebook ilə</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="#" class="btn btn-gmail">-->
<!--                            <span class="social-item"><i class="fa fa-google-plus" aria-hidden="true"></i></span>-->
<!--                            <span>Google+ ilə</span>-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->
            </div>
        </div>
    </div>
</div>
