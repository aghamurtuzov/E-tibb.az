<?php
use \yii\helpers\Html;
?>
<!--say thanks modal-->
<div class="modal fade m-pad-set" id="say_thanks" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header darkblue">
                <h5 class="modal-title"><img src="assets/img/icon/heart-big.png" alt="tesekkur et"> Təşəkkür et</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="assets/img/close.png">
                </button>
            </div>
            <div class="modal-body">
                <div id="thanks_modal_body">
                    <?=$this->render('comment_form',["comment" => $comment,'save'=>false])?>
                </div>
                <?= Html::button("Göndər", ['class' => 'cb cyan w-m-100 d-inline-block text-center','id' => 'thanks_submit']); ?>
            </div>
        </div>
    </div>
</div>