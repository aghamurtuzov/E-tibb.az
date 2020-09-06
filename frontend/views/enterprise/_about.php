<?php
use backend\components\Functions;
use frontend\models\SiteGalleryModel;
// Page structure
$gallery = SiteGalleryModel::getGallery($enterprise["id"],2);
?>
<!---------about tab------------>
<div class="t-tab-content about-doctor">
    <?= $enterprise["about"]?>
    <div class="slider-for">
        <?php
        if(count($gallery)>1){
            foreach($gallery as $item){
                ?>
                <div class="single">
                    <a href="<?=Functions::getUploadUrl().$customPath.'/'.$item['photo']?>">
                        <img src="<?=Functions::getUploadUrl().$customPath.'/'.$item['photo']?>" class="img-fluid" alt="<?= $enterprise["name"]." sekil".$item["id"]?>">
                    </a>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div class="slider slider-nav">
        <?php
        if(count($gallery)>1){
            foreach($gallery as $item){
                ?>
                <div>
                    <img src="<?=Functions::getUploadUrl().$customPath.'/small/'.$item['photo']?>" class="img-fluid" alt="<?= $enterprise["name"]." sekil".$item["id"]?>">
                </div>
                <?php
            }
        }
        ?>
    </div>
    <div class="clearfix"></div>
</div>
<!---------about tab------------>