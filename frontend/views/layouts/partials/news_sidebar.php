<?php

use backend\components\Functions;

?>


<div class="col-md-3">
    <div class="rules" style="margin-bottom: 25px;">
        <div class="rules-left">
            <div class="left-head">
                <h4>KATEQORİYALAR</h4>
            </div>
            <ul class="list-unstyled">
                <?php
                $categories = \frontend\models\SiteMenus::NewsCategories(3);

                foreach ($categories as $category)
                {
                    ?>
                    <li <?=($category['id']==$cat_id) ? 'class=active' : ''?>>
                        <a href="<?=Yii::$app->params['site.url']?>xeberler/<?=$category['link']."-".$category['id']?>"><?=$category['name']?></a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    
    <div class="healthy-right">
        <div class="right-top">
            <h4 class="heading-inner">ƏN ÇOX OXUNANLAR</h4>
            <?php foreach ($most_read as $value) {
                    echo "
                        <a href='".Yii::$app->params['site.url'].Yii::$app->params['site.post_uri'] . $value['slug'] . '-' . $value['id']."'>
                            <div class='right-content'>
                                <p>$value[headline]</p>
                                <span>".Functions::getDatetime($value['datetime'], ['type' => 'date'])."</span>
                            </div>
                        </a>";

            } ?>
        </div>
<!--        <div class="right-bottom">-->
<!--            <h4 class="heading-inner">TAG BULUDU</h4>-->
<!--            <div class="tags">-->
<!--                --><?php //foreach ($tags as $tag) {
//                    if ($tag['keywords1'] != '')
//                        echo "<a href='&keyword=".$tag['keywords1']."'><span>$tag[keywords1]</span></a>";
//
//                } ?>
<!---->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>