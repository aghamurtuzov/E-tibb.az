<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;
use frontend\models\MainModel;

$getMenus      = ArrayHelper::toArray(new Menu());
$data['menus'] = $getMenus['list'];
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<?= $this->render("../layouts/partials/head",['data' => $data])?>
<body>
<?php $this->beginBody() ?>
<?=$this->render('../layouts/partials/header',['data' => $data]); ?>

<section class="not-found">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="<?=Yii::$app->params['site.url']?>">Ana səhifə </a></li>
                        <li><a href="#">Səhifə mövcud deyildir </a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="notfound-body text-center">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 ">
                    <img src="<?=Yii::$app->params['site.url']?>assets/img/404.png" alt="not-found" class="img-responsive center-block">
                    <h3>Təəssüf ki, axtardığınız səhifə mövcud deyildir.</h3>
                    <p>Zəhmət olmasa, linkin düzgünlüyünü bir daha yoxlayın və yaxud ana səhifəyə geri dönün.</p>
                    <a href="<?=Yii::$app->params['site.url']?>" class="btn btn-effect">Ana səhifə </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?=$this->render('../layouts/partials/footer',['data'=>$data]); ?>

<?= $this->render('../layouts/partials/modals/login',['data'=>$data]); ?>
<?= $this->render('../layouts/partials/modals/register',['data'=>$data]); ?>

<?php $this->endBody() ?>
<script>
    $(document).ready(function () {
        $('.doctor-page .make-appointment .get-hours span').click(function () {
            let self=$(this);
            self.addClass('active ');
            self.siblings().removeClass('active');
            console.log(self.siblings())
        })
    })

</script>
</body>
</html>
<?php $this->endPage() ?>
<style>
    .form-group{
        margin: 0 !important;
    }
    .help-block {
        margin: 0 !important;
    }
</style>