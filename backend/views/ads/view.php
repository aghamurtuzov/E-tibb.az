<?php

use yii\helpers\Html;
use \backend\components\Functions;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteAds */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Site Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-ads-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <table id="w0" class="table table-striped table-bordered detail-view">
        <tr>
            <td>Başlıq</td>
            <td><?= $model->title ?></td>
        </tr>
        <tr>
            <td>Slug</td>
            <td><?= $model->slug ?></td>
        </tr>
        <tr>
            <td>Mətn</td>
            <td><?= $model->text ?></td>
        </tr>
        <tr>
            <td>Isifasəçi Tipi</td>
            <td><?php  if($model->type == 0) echo'İstifadəçi'; elseif($model->type == 1) echo'Həkim'; else echo'Obyekt' ?></td>
        </tr>
        <tr>
            <td>Premium bitmə vaxtı</td>
            <td><?= $model->premium_expiry ?></td>
        </tr>
        <tr>
            <td>Premium tipi</td>
            <td><?php
                if ($model->premium_type == 0)
                    echo 'Yoxdur';
                elseif ($model->premium_type == 1)
                    echo 'Hamı üçün';
                else
                    echo 'Limitli';
                ?>
            </td>
        </tr>
        <tr>
            <td>Reyting dəyəri</td>
            <td><?= $model->rating_value ?></td>
        </tr>
        <tr>
            <td>Baxış sayı</td>
            <td><?= $model->review_count ?></td>
        </tr>
        <tr>
            <td>Qan axtarışı</td>
            <td><?= ($model->is_blood == 1 ? 'Bəli' : 'Xeyr'); ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td><?= ($model->status == 0 ? 'Aktiv deyil' : 'Aktivdir') ?></td>
        </tr>
        <tr>
            <td>Şəkil</td>
            <td>
                <img width="200" src="<?=Functions::getUploadUrl() . '/ads/small/' . $model->image ?>">
            </td>
        </tr>
    </table>
</div>
