<?php
use \yii\helpers\Html;
?>
<!--say thanks modal-->
<div class="modal fade m-pad-set" id="write_to_the_reception" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header pink">
                <h5 class="modal-title"><img src="assets/img/icon/edit-big.png"> Qəbula yazıl</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="assets/img/close.png">
                </button>
            </div>
            <div class="modal-body">
                <div id="appoint_modal_body">
                    <?=$this->render('appointment',['appointment'=>$appointment,'save'=>false])?>
                </div>
                <?= Html::button("Təsdiq et", ['class' => 'cb pink w-m-100 d-inline-block text-center','id' => 'appoint_submit']); ?>
            </div>
        </div>
    </div>
</div>