<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\widgets\Alert;
use backend\assets\AppAsset;
use backend\components\FilterActionColumn;
use backend\components\Functions;
use backend\models\SiteAppointment;

/* @var $this yii\web\View */
/* @var $model backend\models\SiteAppointment */

$this->title = "Randevu | ".SiteAppointment::get_Connected($model->connect_id, $model->type);
$this->params['breadcrumbs'][] = ['label' => 'Site Appointments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-appointment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        
    </p>

    <?= DetailView::widget([
    
        'model' => $model,
        'attributes'=>[
            [
                'attribute'=>'patient',
                'value'=>function($data) {
                    return SiteAppointment::get_Patient($data->patient_id, $data->connect_id)['name'].' '.SiteAppointment::get_Patient($data->patient_id, $data->connect_id)['surname'];
                }
            ],
            [
                'attribute'=>'email',
                'value'=>function($data) {
                    return SiteAppointment::get_Patient($data->patient_id, $data->connect_id)['email'];;
                }
            ],
            [
                'attribute'=>'phone_number',
                'value'=>function($data) {
                    return SiteAppointment::get_Patient($data->patient_id, $data->connect_id)['phone_number'];
                }
            ],
            [
                'attribute'=>'content',
                'value'=>function($data){
                    return $data->content;
                }
            ],
            [
                'attribute'=>'date',
                'value'=>function($data){
                    return $data->time.' '.date('d-m-Y',strtotime( $data->date));
                }
            ],
            [
                'attribute'=>'type',
                'value'=>function($data){
                    return ($data->type == 1? 'Həkim':'Obyekt');
                },
            ],
            [
                'attribute'=>'connect_id',
                'value'=>function($data){
                    return SiteAppointment::get_Connected($data->connect_id, $data->type);
                }
            ],
            [
                'attribute'=>'site',
                'value'=>function($data){
                    return ( $data->site == 0 ? 'Panel':'Etibb.az');
                },
            ],
            
        ],        
    
    ])?>
    
</div>
