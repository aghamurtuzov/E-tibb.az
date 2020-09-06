<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SitePackagesTypes */

$this->title = 'Yeni Mövzü Yarat';
$this->params['breadcrumbs'][] = ['label' => 'Site Packages Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-packages-types-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
