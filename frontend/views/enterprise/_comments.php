<?php
use frontend\models\SiteCommentsModel;
$commentsCount = SiteCommentsModel::getCommentsCount($enterprise["id"],2);
$comments = SiteCommentsModel::getComments($enterprise["id"],2);
?>
<!---------comment tab------------>
<div class="t-tab-content">
    <div  id="commentBlock">

    <?php
    if(count($comments)>0) {
        foreach ($comments as $comment) {
            ?>
            <div class="question-answer-block no-border">
                <div class="header">
                    <a href="javascript:void(0);" class="float-left cyan-color"><img
                                src="assets/img/icon/user-c.png" alt="<?= $comment["name"] ?>"> <?= $comment["name"] ?></a>
                    <div class="clearfix"></div>
                </div>
                <div class="body">
                    <p><?= $comment["comment"]; ?> </p>
                </div>
            </div>
            <?php
        }
    }   else{
        echo '<div class="alert alert-danger">Rəy yoxdur</div>';
    }
    ?>
    </div>
    <?php
    if($commentsCount>count($comments)){
    ?>
    <div class="row -h-top-2">
        <div class="col-md-12 text-center">
            <div class="col-md  button-group padding-clear">
                <button class="cb mini2 gray shadow modal-buttons inner-shadow" id="showMoreComment" data-id="<?= $enterprise["id"]?>" data-type-id="2" data-limit="<?= $commentsCount?>" data-page-size="<?= Yii::$app->params["site.hospital_comment_count"]?>"> Daha çox</button>
                <div id="loading" style="display: none">Yüklənir...</div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<!---------comment tab------------>