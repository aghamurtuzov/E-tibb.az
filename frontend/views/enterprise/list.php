<?PHP

use yii\helpers\Url;
use backend\components\Functions;

if(isset($data['enterprises']))
{
    //print_r($data['category']);
    foreach($data['enterprises'] as $key => $val)
    {

        $premium       = false;
        $premium_class = null;
        $link          = 'javascript:void(0);';

        $catName  = isset($data['category']['name']) ? $data['category']['name'] : null;
        $catSlug  = isset($data['category']['link']) ? $data['category']['link'] : null;
        $settings = isset($data['category']['settings']) ? json_decode($data['category']['settings']) : null;
        $dataType = isset($settings->template) ? $settings->template : 0;
        //$dataType = 1;
        $defaultImage = $dataType == 0 ? Yii::$app->params['site.defaultThumb'] : Yii::$app->params['site.defaultThumb1'];

        if(Yii::$app->params['current.date'] <= $val['expires'])
        {
            $premium       = true;
            $premium_class = 'premium';
            $link          = $catSlug.'/'.$val["id"].'-'.$val["slug"];
        }

        $photo   = !empty($val['photo']) ? Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/small/'.$val['photo'] : $defaultImage;

        $enterpriseName = Functions::getCleanText($val['name']);

        ?>
        <div class="col-md-6">
            <?PHP
            if($dataType == 0){
                ?>
                <div class="doc-bl <?=$premium_class?>">
                    <div class="media">
                        <img class="mr-3 object-fit" src="<?=$photo?>" alt="<?=$enterpriseName?>" style="border:1px solid #f5efef;width: 121px;height: 111px;">
                        <div class="media-body">
                            <div class="d-information">
                                <p class="info name text-uppercase">
                                    <img src="assets/img/icon/clinic.png" alt="clinic icon">
                                    <a href="<?=$link?>" title="<?=Functions::getCleanText($val['name'])?>"><?=Functions::textLimit($val['name'],100)?></a>
                                </p>
                                <p class="info pl" title="<?=Functions::getCleanText($val['address'])?>"><img src="assets/img/icon/location.png" alt="location icon"><?=Functions::textLimit($val['address'],25);?></p>
                                <p class="info st">
                                    <?PHP
                                    if(!empty($val['promotion']))
                                    {
                                        echo '<img src="assets/img/icon/promo.png" alt="promo icon">';
                                        echo '<a href="javascript:void(0);">Aksiya</a>';
                                    }else{
                                        echo '<span class="disb">';
                                        echo '<img src="assets/img/icon/promo.png" alt="promo icon">';
                                        echo '<a href="javascript:void(0);">Aksiya</a>';
                                        echo '</span>';
                                    }
                                    ?>
                                    <span class="rating float-right"><span style="width:<?=$val['rating']?>%" class="rating-inner"></span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?PHP
            }else{
                ?>
                <div class="d-block no-shadow m-b">
                    <div class="d-image">
                        <img src="<?=$photo?>" alt="<?=$enterpriseName?>">
                        <?PHP if($premium){ echo '<span class="badge badge-top">Premium</span>'; }; ?>
                    </div>
                    <div class="d-information">
                        <p class="info name"><img src="assets/img/icon/merkez.png" alt="merkez icon"> <a href="<?=$link?>" title="<?=Functions::getCleanText($val['name'])?>"><?=Functions::textLimit($val['name'],100)?></a></p>
                        <p class="info pl" title="<?=Functions::getCleanText($val['address'])?>"><img src="assets/img/icon/location.png" alt="location icon"><?=Functions::textLimit($val['address'],45);?></p>
                        <p class="info st">
                            <?PHP
                            if(!empty($val['promotion']))
                            {
                                echo '<img src="assets/img/icon/promo.png" alt="promo icon">';
                                echo '<a href="javascript:void(0);">Aksiya</a>';
                            }else{
                                echo '<span class="disb">';
                                echo '<img src="assets/img/icon/promo.png" alt="promo icon">';
                                echo '<a href="javascript:void(0);">Aksiya</a>';
                                echo '</span>';
                            }
                            ?>
                            <span class="rating float-right"><span style="width:<?=$val['rating']?>%" class="rating-inner"></span></span>
                        </p>
                    </div>
                </div>
                <?PHP
            }; ?>
        </div>
        <?PHP
    }
}
?>