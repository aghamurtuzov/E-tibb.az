<?PHP

use backend\components\Functions;
use frontend\models\SiteGalleryModel;

$doctor = $data['doctor'];

$gallery = SiteGalleryModel::getGallery($doctor["id"],1);

$count = !empty($gallery) ? count($gallery) : 0;

$customPath = $data['customPath'];

?>
<div class="t-tab-content about-doctor">
    <?= $doctor["about"]?>
    <div class="slider-for">
        <?php
        if($count > 1)
        {
            foreach($gallery as $key => $val)
            {
        ?>
                <div class="single">
                    <a href="<?=Functions::getUploadUrl().$customPath.'/'.$val['photo']?>">
                        <img src="<?=Functions::getUploadUrl().$customPath.'/'.$val['photo']?>" class="img-fluid" alt="<?= Functions::getCleanText($doctor["name"])." | Foto qalereya ".$key?>">
                    </a>
                </div>
        <?php
            }
        }
        ?>
    </div>
    <div class="slider slider-nav">
        <?php
        if($count > 1)
        {
            foreach($gallery as $key => $val)
            {
                $photo = Functions::getUploadUrl().$customPath.'/small/'.$val['photo'];
                $alt   = Functions::getCleanText($doctor["name"])." | Foto qalereya ".$key;
                echo "<div><img src=\"{$photo}\" class=\"img-fluid\" alt=\"{$alt}\"></div>";
            }
        }
        ?>
    </div>
    <div class="clearfix"></div>
</div>
