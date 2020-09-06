<?php
    use yii\helpers\Url;
    use backend\components\Functions;
    //$this->title = $page_title;
?>
<div class="t-card mini3 -h-top">
    <div class="row">
        <div class="col col-md-6 text-left d-none d-md-block">
            <h2 class="breadcrumb-title">Axtar</h2>
        </div>
        <div class="col col-md-6 text-left text-md-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="padding:15px 0 8px;">
                    <li class="breadcrumb-item"><a href="<?= Url::base()?>">Ana səhifə</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Axtar</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="row -h-top">
    <?php
    //echo"<pre>";print_r($searchData); 
    $page_link = $link;
    //die();
    foreach($searchData as $enterprise){

        $saat24  = false;
        $catdirilma = false;
        $eve_cagiris = false;
        $features = json_decode($enterprise["feature"],true);

        $premium = false;
        $premium_class ='';
        $link = 'javascript:void(0);';

        if(date("Y-m-d")<=$enterprise["expires"]){
            $premium = true;
            $premium_class = 'premium';

            $link = Url::to([Functions::slugify($page_link,['transliterate'=>true]).'/'.$enterprise["id"].'-'.Functions::slugify($enterprise["name"],['transliterate'=>true])]);
        }

        if(is_array($features) and count($features)>0){
            if(key_exists("saat24",$features)){
                $saat24 = true;
            }

            if(key_exists("catdirilma",$features)){
                $catdirilma = true;
            }

            if(key_exists("eve_caqiris",$features)){
                $eve_cagiris = true;
            }
        }


        ?>
        <div class="col-md-6">
            <a href="<?= $link?>">
                <div class="doc-bl <?=$premium_class?>">
                    <div class="media">
                        <?php

                        ?>
                        <img class="mr-3 m-doctor-image-size" src="<?= $enterprise['photo'] ? Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/small/'.$enterprise['photo'] : Yii::$app->params['site.defaultThumb'] ?>" style="width: 121px;height: 111px;">
                        <div class="media-body">
                            <div class="d-information">
                                <p class="info name">
                                    <img src="assets/img/icon/pharmacies.png" alt="icon">
                                    <a href="<?= $link?>" class="text-uppercase">
                                        <?= $enterprise["name"]?>
                                    </a>
                                </p>
                                <p class="info pl"><img src="assets/img/icon/location.png" alt="<?= $enterprise["address"]?>"> <a href="<?= $link?>"><?= $enterprise["address"]?></a></p>
                            </div>
                            <div class="row -h-top-h">
                                <div class="col-md-12 text-left">
                                    <?php if($catdirilma){ ?>
                                    <div class="d-inline-block">
                                        <p class="blue-color">
                                            <img src="assets/img/icon/delivery.png" alt="catdirilma">
                                            <span class="h1n">Çatdırılma</span>
                                        </p>
                                    </div>
                                    <?php } ?>
                                    <?php if($saat24){ ?>

                                    <div class="d-inline-block">
                                        <p class="blue-color">
                                            <img src="assets/img/icon/24hours.png" alt="24 saat">
                                            <span class="h1n">Saat</span>
                                        </p>
                                    </div>
                                    <?php } ?>

                                    <?php  if($premium == false){   ?>
                                    <div class="d-inline-block float-right">
                                        <button class="cb border-orange mini d-inline-block" style="margin-left: 15px;">Premium et</button>
                                    </div>
                                    <?php }else{
                                        ?>
                                        <span class="rating float-right"><span style="width:<?= intval($enterprise["rating"])?>%" class="rating-inner"></span></span>
                                        <?php
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php
    }


    ?>

</div>
<?php
    if (isset($pagination) and !empty($pagination)) {
?>
<div class="row -h-top-2">
    <div class="text-center">
        <div class="t-pagination">
            <?= \yii\widgets\LinkPager::widget([
                'pagination'=>$pagination,
                'maxButtonCount' => 7,
                'firstPageLabel' => false,
                'lastPageLabel' => false,
                'prevPageLabel' =>  '<img src="assets/img/arrow-left.png"> Əvvəl',
                'nextPageLabel' => 'Sonra <img src="assets/img/arrow-right.png">' ,

            ]);?>
        </div>
    </div>
</div>
<?php } ?>