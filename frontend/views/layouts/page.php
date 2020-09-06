<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;

$getMenus      = ArrayHelper::toArray(new Menu());
$data['menus'] = $getMenus['list'];
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="no-js home-page">
<?= $this->render("partials/head",['data' => $data])?>
<body>
<?php $this->beginBody() ?>
<?=$this->render('partials/header',['data' => $data]); ?>
<div class="container-fluid inner-menu">
    <div class="container">
        <div class="row">
            <div class="col-md d-none d-lg-block">
                <?=$this->render('partials/nav',['data' => $data]); ?>
            </div>
        </div>
    </div>
</div>
<div class="container content-side">
    <div class="row">
        <div class="col-md-12 col-lg-9 custom-col-9 col-12">
            <?=$this->render('partials/search',['data' => $data]); ?>
            <?= $content ?>
        </div>
        <div class="col-md-3 custom-col-3 d-none d-lg-block">
            <?=$this->render('partials/sidebar_nav',['data' => $data]); ?>
            <?=$this->render('partials/sidebar',['data' => $data]); ?>
        </div>
    </div>
</div>

<?=$this->render('partials/footer',['data'=>$data]); ?>

<?PHP //$this->render('partials/modals/login',['data'=>$data]); ?>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
