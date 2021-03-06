<?PHP
use yii\helpers\Url;
use backend\components\Functions;

$doctor = $data['doctor'];
$photo  = $data['doctor']['photo_url'];
$currentLink = Functions::getDoctorLink($data['specialist'],$doctor['id'],$doctor['name']);
$specialists = null;
if(isset($data['specialist']))
{
    foreach($data['specialist'] as $lkey => $val)
    {
        $specialists .= $val['name'].' ';
    }
}
?>
<section class="mbr-section content19 cid-r8CfILVckJ" id="content19-4">
    <div class="mbr-container">
        <h2 class="mbr-title align-center mbr-fonts-style mbr-bold mbr-white display-1"><?=$specialists?></h2>
        <h3 class="mbr-section-subtitle align-center mbr-light mbr-fonts-style mbr-white display-5">
            <ul class="breadcrumb_1">
                <li><a href="<?=Url::base(true)?>">Ana səhifə</a></li>
                <!--<li><?=$data['doctor']['name']?></li>-->
                <li><?=$specialists?></li>
            </ul>
        </h3>
    </div>
</section>
<section class="content4 mbr-section article cid-r8CgwSzLYz defaultAmp" id="content4-6">
    <div class="container">
        <div class="mbr-text mbr-fonts-style mbr-light display-7">

            <amp-img src="<?=$photo?>" width="185" height="185" layout="responsive" alt="<?=Functions::getCleanText($data['doctor']['name'])?>" class="articlePhoto_1"></amp-img>

            <h3 class="articleTitle m20 mb0"><?=$data['doctor']['name']?></h3>

            <div class="specialist"><?=$specialists?></div>

            <br>

            <?PHP
            if(isset($tabPages))
            {
                echo '<nav class="menuList">';
                foreach($tabPages as $key => $val)
                {
                    $class = $val['name'] == 'Haqqında' ? 'active': null;
                    $link = $currentLink.'/'.$val['link'];
                    echo "<a class=\"{$class}\" href=\"/{$link}\">{$val['name']}</a>";
                }
                echo '</nav>';
            }
            ?>

            <div class="content"><?=$data['doctor']['about']?></div>

            <?PHP if(isset($data['promotions'])){ ?>
            <h4 class="sectionTitle">AKSİYALAR</h4>
            <div class="promotionList">
                <?PHP
                foreach($data['promotions'] as $key => $val)
                {
                    $link = Yii::$app->params['site.aksiya'].Functions::slugify($val['headline']).'-'.$val['id'];
                ?>
                <div class="item">
                    <div class="prmTitle"><a href="/<?=$link?>"><?=$val['headline']?></a></div>
                    <div class="prmPrice">
                        <?PHP
                            if(!empty($val['discount'])){ echo "<span class=\"discount\">{$val['discount']} azn</span> "; }
                            if(!empty($val['price'])){ echo $val['price']." azn"; }
                        ?>
                    </div>
                    <div class="prmExpires">Aksiya etibarlıdır: <?=Functions::getDatetime($val['date_end'])?></div>
                </div>
                <?PHP
                };
                ?>
            </div>
            <?PHP }; ?>

            <?PHP
//            if(isset($data['promotions']))
//            {
//                echo '<h4 class="sectionTitle">AKSİYALAR</h4>';
//                echo '<div class="promotionList">';
//                foreach($data['promotions'] as $key => $val)
//                {
//                    $link = '';
//                    echo "<div class=\"item\"><a href=\"{$link}\">{$val['headline']}</a></div>";
//                }
//                echo '</div>';
//            }
            ?>

        </div>
    </div>
</section>

<section class="social-follow cid-qP6waPBqRw" id="social-share1-44">
    <div class="align-center">
        <div class="wrapper">
            <amp-social-share class="rounded"
                              type="email"
                              width="50"
                              height="50"></amp-social-share>
            <amp-social-share class="rounded"
                              type="facebook"
                              data-param-app_id="592902254440829"
                              width="50"
                              height="50"></amp-social-share>
            <amp-social-share class="rounded"
                              type="gplus"
                              width="50"
                              height="50"></amp-social-share>
<!--            <amp-social-share class="rounded"-->
<!--                              type="linkedin"-->
<!--                              width="50"-->
<!--                              height="50"></amp-social-share>-->
            <amp-social-share class="rounded"
                              type="pinterest"
                              data-param-media="https://ampbyexample.com/img/amp.jpg"
                              width="50"
                              height="50"></amp-social-share>
<!--            <amp-social-share class="rounded"-->
<!--                              type="tumblr"-->
<!--                              width="50"-->
<!--                              height="50"></amp-social-share>-->
            <amp-social-share class="rounded"
                              type="twitter"
                              width="50"
                              height="50"></amp-social-share>
            <amp-social-share class="rounded"
                              type="whatsapp"
                              width="50"
                              height="50"></amp-social-share>
<!--            <amp-social-share class="rounded"-->
<!--                              type="line"-->
<!--                              width="50"-->
<!--                              height="50"></amp-social-share>-->
        </div>
    </div>
</section>