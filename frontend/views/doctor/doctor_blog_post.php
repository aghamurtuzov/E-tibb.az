<?php

    if($data['total_count']>0)
    {
        ?>
        <div class="col-md-12">
            <h4 class="head-text">BLOQ YAZILARI</h4>
            <div class="row block">
                <?php
                    foreach ($data['news_list'] as $val)
                    {
                        ?>
                        <div class="col-md-3 col-12">
                            <a href="<?= Yii::$app->params['site.url'] . "xeber/". $val['slug'] . '-' . $val['id'] ?>">
                                <div class="block-cover">
                                    <div class="img-cover">
                                        <img src="<?= Functions::getUploadUrl()  . 'news/small/' . $val['photo'] ?>" class="img-responsive cover" alt="health1">
                                        <div class="image-over">
                                            <p class="left-item">
                                                <img src="<?=Yii::$app->params['site.url']?>assets/img/eye.png" alt="eye"> <span><?=$val['news_read'];?></span>
                                            </p>
                                            <p class="right-item">
                                                <img src="<?=Yii::$app->params['site.url']?>assets/img/date.png" alt="date"> <span><?= Functions::getDatetime($val['datetime'], ['type' => 'date']) ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="information">
                                        <p class="information-text"><?= Functions::getCleanText($val['headline']) ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                ?>
            </div>
            <div class="col-xs-12 text-center">
                <nav aria-label="Page navigation">
                    <?= \yii\widgets\LinkPager::widget([
                        'pagination' => $data['pagination'],
                        'maxButtonCount' => 7,
                        'firstPageLabel' => false,
                        'lastPageLabel' => false,
                        'prevPageLabel' => '<span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i> Əvvəl</span>',
                        'nextPageLabel' => '<span aria-hidden="true">Sonra<i class="fa fa-angle-right" aria-hidden="true"></i></span>',
                    ]); ?>
                </nav>
            </div>
        </div>
        <?php
    }
?>
