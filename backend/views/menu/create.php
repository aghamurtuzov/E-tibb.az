<?php
 
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SiteMenus */

$this->title = 'Menu | Əlavə et';
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-menus-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
 