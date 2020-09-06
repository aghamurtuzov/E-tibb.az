<?PHP
use yii\helpers\Url;
use backend\components\Functions;
use frontend\models\SiteCalling;

$doctor        = $data['doctor'];
$classPremium  = null;

$currentLink = Functions::getDoctorLink($data['specialist'],$doctor['id'],$doctor['name']);

Yii::$app->view->registerLinkTag(["rel" => "canonical","href" => $currentLink]);

$this->title = $doctor['name'].' - '.$currentTab['name'];

if(!empty($doctor['photo'])) $photo = Functions::getUploadUrl().Yii::$app->params['path.doctor'] . '/small/' . $doctor['photo'];
else {
    $photo = ($doctor['gender'] == 0 ? Yii::$app->params['site.defaultThumbDoctorF'] : Yii::$app->params['site.defaultThumbDoctor']);
}

if(Yii::$app->params['current.date'] <= $doctor['expires']){ $classPremium = 'premium'; }

$experience = date('Y') - $doctor['experience1'];

$fb_link = 'https://www.facebook.com/sharer/sharer.php?u='.Yii::$app->params["site.url"].$currentLink;

$gp_link = 'https://plus.google.com/share?url='.Yii::$app->params["site.url"].$currentLink;

$tw_link = 'https://twitter.com/home?status='.Yii::$app->params["site.url"].$currentLink;

$title       = $doctor['name'];
$lead        = $data['specialist'][0]['name'];
$imageInfo   = @getimagesize($photo);
$imageWidth  = isset($imageInfo[0]) ? $imageInfo[0] : 200;
$imageHeight = isset($imageInfo[1]) ? $imageInfo[1] : 200;
$catName     = $data['specialist'][0]['name'];
$appointment    = new SiteCalling();
?>
<div class="t-card mini3 -h-top">
    <div class="row">
        <div class="col col-md-6 text-left d-none d-md-block">
            <h2 class="breadcrumb-title"><?=$doctor['name']?></h2>
        </div>
        <div class="col col-md-6 text-left text-md-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="padding:15px 0 8px;">
                    <?PHP
                    if(isset($data['breadcrumb']))
                    {
                        $count = count($data['breadcrumb']);
                        foreach($data['breadcrumb'] as $key => $val)
                        {
                            $bActive = ($key+1) == $count ? 'active' : null;
                            if(empty($bActive))
                            {
                                echo "<li class=\"breadcrumb-item\"><a href=\"{$val['link']}\">{$val['name']}</a></li>";
                            }else{
                                echo "<li class=\"breadcrumb-item {$bActive}\" aria-current=\"page\">{$val['name']}</li>";
                            }
                        }
                    }
                    ?>
                </ol>
            </nav>
        </div>
    </div>
</div>
<?php
if(Yii::$app->session->hasFlash("success")){
    ?>
    <br />
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?= Yii::$app->session->getFlash("success"); ?>
    </div>
    <?php
}
?>
<div class="t-card mini -h-top premium">

    <div class="row">
        <div class="col-md-auto col-auto padding-clear padding-minimal-m">
            <div class="d-image big-pr">
                <img src="<?=$photo?>" alt="<?=Functions::getCleanText($doctor['name'])?>" class="object-fit m-doctor-image-size" style="width: 185px;height: 185px;">
                <!--<span class="crown"></span>-->
                <?PHP if($classPremium == 'premium'){ echo '<span class="badge">Premium</span>'; }; ?>
            </div>
        </div>
        <div class="col-md col">
            <div class="profile-information">
                <p class="p-link p-cat d-lg-none"><img src="assets/img/icon/name.png"><?=$doctor['name']?></p>
                <p class="p-link p-cat" href="doctors">
                    <img src="assets/img/icon/p-pl.png">
                    <?PHP
                    if(isset($data['specialist']))
                    {
                        foreach($data['specialist'] as $key => $val)
                        {
                            $spcLink = Functions::slugify($val['name']).'-'.$val['id'];
                            echo "<a href=\"{$spcLink}\">{$val['name']}</a> ";
                        }
                    }
                    ?>
                </p>
                <p class="p-link p-cat"><img src="assets/img/icon/p-lp.png"> <?PHP if(!empty($experience)){ echo $experience.' il iş təcrübəsi'; }; ?></p>
                <p class="info st">
                    <span class="cho">
                    <?PHP
                    if($doctor['feature'] == 1 || $doctor['feature'] == 3)
                    {
                        echo "<img src=\"assets/img/icon/p-b.png\"> <span class=\"d-none d-sm-inline-block bold disb\">Çağırış</span>";
                    }elseif($doctor['feature'] == 2 || $doctor['feature'] == 3){
                        echo "<img src=\"assets/img/icon/p-baby.png\"> <span class=\"d-none d-sm-inline-block bold\">Uşaq həkimi</span>";
                    }
                    ?>
                    </span>
                    <span class="rating float-right"><span style="width:<?=$doctor['rating']?>%" class="rating-inner"></span></span>
                </p>
                <?PHP if($doctor['promotion']){ ?>
                <div class="profile-discount d-none d-sm-block">
                    <p class="count"><?=intval($doctor['promotion'])?> <img src="assets/img/icon/p-discount.png"></p>
                    <p class="text">Aksiya sayı</p>
                </div>
                <?PHP }; ?>
            </div>
            <!--desktop version social and button-->
            <div class="row align-items-center d-none d-sm-flex">
                <div class="col-md-auto">
                    <div class="social-share text-center mini-margin margin-clear">
                        <a target="_blank" href="<?=$fb_link?>">
                            <img src="assets/img/icon/fb.png">
                        </a>
                        <a target="_blank" href="<?=$gp_link?>">
                            <img src="assets/img/icon/gp.png">
                        </a>
                        <a target="_blank" href="<?=$tw_link?>">
                            <img src="assets/img/icon/tw.png">
                        </a>
                    </div>
                </div>
                <div class="col-md text-right button-group">
                    <button class="cb mini2 gray shadow modal-buttons inner-shadow" data-toggle="modal" data-target="#say_thanks"><img src="assets/img/icon/heart.png" alt="tesekkur et"> Təşəkkür et</button>
                    <?php if(Yii::$app->user->isGuest){ ?>
                        <button class="cb mini2 pink shadow modal-buttons inner-shadow" data-toggle="modal" data-target="#login"><img src="assets/img/icon/edit.png"> Qəbula yazıl</button>
                    <?php }else{ ?>
                        <button class="cb mini2 pink shadow modal-buttons inner-shadow" data-toggle="modal" data-target="#write_to_the_reception"><img src="assets/img/icon/edit.png"> Qəbula yazıl</button>
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
    <!--mobile version social and buttons-->
    <div class="row align-self-center d-flex d-sm-none">
        <div class="col-auto">
            <div class="social-share text-center mini-margin margin-clear">
                <a class="click-share" target="_blank" href="javascript:void(0);">
                    <img src="assets/img/icon/share.png">
                </a>
                <a class="hidden-share" target="_blank" href="<?=$fb_link?>">
                    <img src="assets/img/icon/fb.png">
                </a>
                <a class="hidden-share" target="_blank" href="<?=$gp_link?>">
                    <img src="assets/img/icon/gp.png">
                </a>
                <a class="hidden-share" target="_blank" href="<?=$tw_link?>">
                    <img src="assets/img/icon/tw.png">
                </a>
<!--                <a class="hidden-share" href="javascript:void(0);">-->
<!--                    <img src="assets/img/icon/in.png">-->
<!--                </a>-->
            </div>
        </div>
        <div class="col text-right text-sm-center button-group hover-share">
            <!--<button class="cb mini2 gray shadow" data-toggle="modal" data-target="#say_thanks"><img src="assets/img/icon/heart.png" class="d-none d-sm-inline-block"> Təşəkkür et</button>-->
            <!--<button class="cb mini2 pink shadow" data-toggle="modal" data-target="#write_to_the_reception"><img src="assets/img/icon/edit.png" class="d-none d-sm-inline-block"> Qəbula yazıl</button>-->
        </div>
    </div>
</div>

<?PHP if(isset($data['promotions'])){ echo $this->render('_promotions',['data'=>$data]); }; ?>

<?PHP if(isset($tabPages)){ ?>
<div class="-h-top tabs">
    <?PHP if(isset($tabPages[1])){ ?>
    <div class="tab-header">
        <nav class="nav nav-fill t-tab">
            <?PHP
            foreach($tabPages as $key => $val)
            {
                $active = $val['name'] == $currentTab['name'] ? 'active' : null;
                $link = $currentLink.'/'.$val['link'];
                echo "<a onclick=\"tab(this)\" class=\"nav-item nav-link {$active}\" href=\"{$link}\">{$val['name']}</a>";
            }
            ?>
        </nav>
    </div>
    <?PHP }; ?>
    <div class="tab-body t-card"><?=$this->render('_'.$currentTab['type'],['data'=>$data])?></div>
</div>
<?PHP }; ?>

<?PHP
echo $this->render('/layouts/partials/modals/comment_modal', ["comment" => $data['comment']]);
echo $this->render('/layouts/partials/modals/appointment_modal',["appointment" => $appointment]);
?>

