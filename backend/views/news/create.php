<?php
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SiteNews */

$this->title = 'Xəbər | Əlavə et';
$this->params['breadcrumbs'][] = ['label' => 'Site News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::csrfMetaTags() ?>
<div class="site-news-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
  