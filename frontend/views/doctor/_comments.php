<?php
//use frontend\models\SiteCommentsModel;
//$doctor = $data['doctor'];
//$commentsCount = SiteCommentsModel::getCommentsCount($doctor["id"],1);
//$comments = SiteCommentsModel::getComments($doctor["id"],1);
//$count = count($comments);


use frontend\models\SiteCommentsModel;
use backend\components\Functions;

?>

<div class="row">
    <div class="col-md-12 doctor-left">
        <div class="doctor-about block-back doctor-questions doctor-comment">
            <div class="doctor-reviews">
                <div class="row">
                    <div class="about-top">
                        <div class="col-xs-7">
                            <h5>Həkimə yazılan rəylər</h5>
                        </div>
                        <div class="col-xs-5 text-right">
                            <a href="<?=Yii::$app->request->url?>#changeable-part" class="btn btn-all comment-footer">
                               İndi təşəkkür et
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
                <?php
                    if($data['commentsCount']>0)
                    {
                        ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="doctor-answer">
                                    <?php
                                        $i = 1;
                                        foreach($data['comments'] as $val)
                                        {
                                            ?>
                                            <div class="doctor-answer-body <?=$i>2 ? 'more_display_comment_all' : ''?>">
                                                <div class="about-body">
                                                    <h5><?=$val['name']?></h5>
                                                    <div class="user-question">
                                                        <p class="rate">
                                                            <span class="rating">
                                                                <?php
                                                                for($k=1;$k<=$val['rating'];$k++)
                                                                {
                                                                    ?>
                                                                    <i class="fa fa-star active" aria-hidden="true"></i>
                                                                    <?php
                                                                }
                                                                for($j=1;$j<=5-$val['rating'];$j++)
                                                                {
                                                                    ?>
                                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </span>
                                                        </p>
                                                        <p><?=$val['comment']?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $i++;
                                        }
                                    ?>
                                </div>
                                <?php
                                    if($data['commentsCount'] > 2)
                                    {
                                        ?>
                                        <div class="all-questions text-center">
                                            <a href="javascript:void(0);" onclick="readMore('read_more_commentall', 'more_display_comment_all', 'Bütün rəylərə bax', 'Bağla', 'doctor-comment');" class="btn transparent btn-bottom read_more_commentall">Bütün rəylərə bax</a>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                    else
                    {
                        ?>
                        <div class="quick_chat_container">
                            <div class="alert alert-warning">Hal hazırda rəy yoxdur.</div>
                        </div>
                        <hr />
                        <?php
                    }
                ?>

            </div>
        </div>
    </div>
</div>
<!---------comment tab------------>