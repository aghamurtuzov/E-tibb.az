<?php
if(count($comments)>0) {
    foreach ($comments as $comment) {
        ?>
        <div class="question-answer-block no-border">
            <div class="header">
                <a href="javascript:void(0);" class="float-left cyan-color"><img
                        src="assets/img/icon/user-c.png"> <?= $comment["name"] ?></a>
                <div class="clearfix"></div>
            </div>
            <div class="body">
                <p><?= $comment["comment"]; ?> </p>
            </div>
        </div>
        <?php
    }
}
?>