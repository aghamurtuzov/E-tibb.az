<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use backend\components\Functions;
use common\widgets\Alert;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;

$getMenus = ArrayHelper::toArray(new Menu());
$data['menus'] = $getMenus['list'];
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="no-js home-page">
<?= $this->render("partials/head", ['data' => $data]) ?>
<body>
<?php $this->beginBody() ?>
<?= $this->render('partials/header', ['data' => $data]); ?>

<?= $content ?>


<?= $this->render('partials/footer', ['data' => $data]); ?>

<?= $this->render('partials/modals/login',['data'=>$data]); ?>
<?= $this->render('partials/modals/register',['data'=>$data]); ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>