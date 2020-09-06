<?PHP
use yii\helpers\Url;
use backend\components\Functions;
?>
<section class="mbr-section content19 cid-r8CfILVckJ" id="content19-4">
    <div class="mbr-container">
        <h2 class="mbr-title align-center mbr-fonts-style mbr-bold mbr-white display-1"><?=$data['news']['category']?></h2>
        <h3 class="mbr-section-subtitle align-center mbr-light mbr-fonts-style mbr-white display-5">
            <ul class="breadcrumb_1">
                <li><a href="<?=Url::base(true)?>">Ana səhifə</a></li>
                <li><?=$data['news']['category']?></li>
            </ul>
        </h3>
    </div>
</section>

<section class="content4 mbr-section article cid-r8CgwSzLYz defaultAmp" id="content4-6">
    <div class="container">
        <div class="mbr-text mbr-fonts-style mbr-light display-7">

            <h3 class="articleTitle"><?=$data['news']['headline']?></h3>

            <amp-img src="https://e-tibb.az/upload/news/<?=$data['news']['photo']?>" width="708" height="467" layout="responsive" alt="<?=Functions::getCleanText($data['news']['headline'])?>" class="articlePhoto_1"></amp-img>

            <!--
            <div class="reading_count">
                Oxunub: <?=$data['news']['news_read']?>
            </div>
            -->

            <?PHP
            $content = $data['news']['content'];
            $clearContent = preg_replace('/<iframe.*?\/iframe>/i','', $data['news']['content']);

            echo "<div class=\"content\">{$clearContent}</div>";

            preg_match_all('/src="([^"]+)"/', $content, $match);
            if(isset($match[1]))
            {
                $key = null;
                foreach($match[1] as $key => $val)
                {
                    if(strpos($val,'/'))
                    {
                        $exp = explode('/',$val);
                        $key = $exp[count($exp)-1];
                    }
                    if(!empty($key))
                    {
                        echo "<amp-youtube data-videoid=\"{$key}\" layout=\"responsive\" width=\"480\" height=\"270\"></amp-youtube><br>";
                    }
                }
            }
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