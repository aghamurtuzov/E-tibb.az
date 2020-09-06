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
<?= $this->render("partials/head",['data' => $data])?>
<body>
<?php $this->beginBody() ?>
<?=$this->render('partials/header',['data' => $data]); ?>

<?= $content ?>

<?=$this->render('partials/footer',['data'=>$data]); ?>

<?= $this->render('partials/modals/login',['data'=>$data]); ?>
<?= $this->render('partials/modals/register',['data'=>$data]); ?>

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