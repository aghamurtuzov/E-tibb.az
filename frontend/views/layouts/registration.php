<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<?= $this->render("partials/head")?>
<body>
<?php $this->beginBody() ?>


<?= $content ?>



<?php $this->endBody() ?>
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