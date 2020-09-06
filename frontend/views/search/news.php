<?php 
use backend\components\Functions;
use yii\helpers\Url;
?>
<div class="t-card mini3 -h-top">
    <div class="row">
        <div class="col col-md-6 text-left d-none d-md-block">
            <h2 class="breadcrumb-title">Axtar</h2>
        </div>
        <div class="col col-md-6 text-left text-md-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="padding:15px 0 8px;">
                    <li class="breadcrumb-item"><a href="#">Ana səhifə</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Axtar</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="row -h-top">
    <?php
        if(!empty($searchData)){
            foreach($searchData as $value){
    ?>
    <div class="col-md-4 col-12 lslide">
        <a href="<?=Yii::$app->params['site.post_uri'].Functions::slugify($value['headline'],['transliterate' => true]).'-'.$value['id']?>">
            <div class="d-block m-b">
				<div class="d-image" style="width: 100% !important;height: 134px;">
                    <img src="<?=Functions::getUploadUrl().$customPath.'/small/'.$value['photo']?>" style="width: 100% !important;height: 134px;" alt="<?=$value['photo']?>">
                </div>
                <div class="d-information">
                    <p class="info name"><a href="<?=Yii::$app->params['site.post_uri'].Functions::slugify($value['headline'],['transliterate' => true]).'-'.$value['id']?>"><?=Functions::getCleanText($value['headline'])?></a></p>
                    <div class="-h-top">
                        <p class="float-left margin-clear">
                            <img src="assets/img/icon/view-d.png"> <span class="dc dark font-weight-normal"><?=$value['news_read']?></span>
                        </p>
                        <p class="float-right margin-clear">
                            <img src="assets/img/icon/date-d.png"> <span class="dc dark font-weight-normal" style="text-transform: capitalize ! important;"><?=Functions::getDatetime($value['datetime'])?></span>
                        </p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </a>
    </div>
    <?php 
            }
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