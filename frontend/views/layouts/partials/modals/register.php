<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use frontend\models\User;

$model = new User(); ?>

<div class="sign-modal modal fade bs-example-modal-sm-1" id="register" tabindex="-1" role="dialog"
     aria-labelledby="mySmallModalLabel2">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img src="<?=Yii::$app->params['site.url']?>assets/img/close.png" alt="modal-close"></span></button>
                <h3 class="modal-title" id="gridSystemModalLabel2">Qeydiyyat / Müraciət et</h3>
            </div>
            <div class="form-list">
                <div id="register_modal_body">
                    <?= $this->render('register_form', ["model" => $model]) ?>
                </div>

                <?php
                echo Html::button("Təsdiq et", ['class' => 'confirm btn btn-effect', 'id' => 'register_submit', 'data-toggle' => "modal", 'data-target' => "#register-reponse"]);
                ?>
            </div>
        </div>
    </div>
</div>
