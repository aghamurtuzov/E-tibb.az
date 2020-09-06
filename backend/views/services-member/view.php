<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\widgets\Alert;
use backend\assets\AppAsset;
use backend\components\FilterActionColumn;
use backend\components\Functions;
use backend\models\SiteServicesMember;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteServicesMember */

$this->title = SiteServicesMember::getConnect($model->type,$model->connect_id);
$this->params['breadcrumbs'][] = ['label' => 'Abunəçi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="site-services-member-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'order_id',
                'format'=>'raw',
                'value'=>function($data){
                    return Html::a($data->order_id,['view','id'=>$data->id],['class'=>'titleLink','data-toggle'=>'tooltip','data-html'=>'true']);
                }
            ],
            [
                'attribute'=>'type',
                'value'=>function($data)
                {
                    $types = $data->getType();
                    return isset($types[$data->type]) ? $types[$data->type]: 'Yoxdur';
                }
            ],
            [
                'attribute'=>'connect_id',
                'format'=>'raw',
                'value'=>function($data){
                    $con_name = $data->getConnect($data->type,$data->connect_id);
                    return $con_name;
                }
            ],
            [
                'attribute'=>'service_id',
                'format'=>'raw',
                'value'=>function($data){
                    $con_name = $data->getServices($data->service_id);
                    return $data->type == 0 ? '<div class="tableBtn btn btn-primary btn-xs">'.$con_name.'</div>': '<div class="tableBtn btn btn-danger btn-xs">'.$con_name.'</div>';
                },
            ],
            [
                'attribute'=>'payment_date',
                'format'=>'raw',
                'value'=>function($data){
                    return !empty($data->payment_date)? '<div class="tableBtn btn btn-primary btn-xs">'.date('d-m-Y',strtotime($data->payment_date)).'</div>': '<div class="tableBtn btn btn-danger btn-xs">----</div>';
                },
            ],
            [
                'attribute'=>'expires_date',
                'format'=>'raw',
                'value'=>function($data){
                    return !empty($data->expires_date)? '<div class="tableBtn btn btn-success btn-xs">'.date('d-m-Y',strtotime($data->expires_date)).'</div>': '<div class="tableBtn btn btn-danger btn-xs">----</div>';
                },
            ],
        ],
    ]) ?>

</div>
