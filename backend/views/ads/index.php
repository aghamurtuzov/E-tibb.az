<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SiteAdsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Elanlar';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-ads-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            'id',
            'user_id',
            'title:ntext',
            'slug:ntext',
            'text',
            //'image:ntext',
            //'type',
            //'premium_expiry',
            //'premium_type',
            //'rating_value',
            //'review_count',
            //'is_blood',
            //'status',
            //'created_at',
            //'updated_at',
            [
                'attribute'=>'status',
                'format'=>'raw',
                'value'=>function($data)
                {
                    $checked = ($data->status == 0 ? '' : 'checked');
                    $title = ($data->status == 0 ? 'Aktiv et' : 'Deaktiv et');
                    return '<div class="pull-right load_after_'.$data->id.'" data-toggle="tooltip" title="'.$title.'">
                                    <input type="checkbox" data-id="'.$data->id.'" data-status="'.$data->status.'" name="status" class="js-switch status_ads" '.$checked.' />
                            </div>';
                },
                'contentOptions'=>['class'=>'w110']
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    $this->registerCssFile("@web/vendor/switchery/dist/switchery.min.css",['depends' => [AppAsset::className()]]);
    $this->registerJsFile("@web/vendor/switchery/dist/switchery.min.js",['depends' => [AppAsset::className()]]);
    ?>

</div>
