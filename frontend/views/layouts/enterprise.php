<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;
use backend\components\Functions;


$getMenus = ArrayHelper::toArray(new Menu());
$data['menus'] = $getMenus['list'];
AppAsset::register($this);

$val_id = Yii::$app->request->get('id');
$checked = '';
if (empty($val_id)) {
    $val_id = Yii::$app->request->get('kateqoriya');
}
if (isset($_GET['vaxt']) and !empty($_GET['vaxt'])) {
    $checked = 'checked';
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="no-js home-page">
<?= $this->render("partials/head", ['data' => $data]) ?>
<body>
<?php $this->beginBody() ?>
<?PHP
if (isset(Yii::$app->params['article.schema']) and !empty(Yii::$app->params['article.schema'])) {
    echo Yii::$app->params['article.schema'];
}
?>
<?PHP
if (isset(Yii::$app->params['breadcrumb.schema']) and !empty(Yii::$app->params['breadcrumb.schema'])) {
    echo Yii::$app->params['breadcrumb.schema'];
}
?>
<?PHP
if (isset(Yii::$app->params['site_navigation.schema']) and !empty(Yii::$app->params['site_navigation.schema'])) {
    echo Yii::$app->params['site_navigation.schema'];
}
?>

<?PHP
if (isset(Yii::$app->params['site_google_searcbox.schema']) and !empty(Yii::$app->params['site_google_searcbox.schema'])) {
    echo Yii::$app->params['site_google_searcbox.schema'];
}


$current_id = 0;
if (Yii::$app->controller->id == 'enterprise' and Yii::$app->controller->action->id == 'index') {
    $current_id = Yii::$app->controller->actionParams["id"];
}
?>
<?= $this->render('partials/header', ['data' => $data]); ?>

<?= $content ?>

<?= $this->render('partials/footer', ['data' => $data]); ?>

<?= $this->render('partials/modals/login',['data'=>$data]); ?>
<?= $this->render('partials/modals/register',['data'=>$data]); ?>
<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
