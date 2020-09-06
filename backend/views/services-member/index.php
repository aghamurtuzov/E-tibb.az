<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use backend\assets\AppAsset;
use backend\components\FilterActionColumn;
use backend\components\Functions;
use backend\models\SiteServicesMember;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SiteServicesMemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Abunəçilər';
$this->params['breadcrumbs'][] = $this->title;
//$con_name = SiteServicesMember::getServices(2);
//print_r($con_name); exit();
?>
<div class="site-services-member-index moduleList">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-info alert-dismissible fade in hidden-lg hidden-md hidden-sm" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <strong>Qeyd:</strong> Cədvəl tam görünmürsə sağa doğru sürüşdürün və ya telefonunuzun "ekran fırlanması"
        özəlliyini aktivləşdirin
    </div>
    <?= Alert::widget(); ?>
    <form action="<?php echo Url::toRoute('deletemore'); ?>" method="POST">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => "",
                'tableOptions' => [
                    'class' => 'table table-striped table-bordered'
                ],
                'rowOptions'=>function($data){
                    if(Functions::diffExpireDate($data->expires_date) < 0 ){
                        return ['class' => 'danger'];
                    }
                },
                'columns' => [
                    [
                        'attribute' => 'order_id',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Html::a($data->order_id, ['view', 'id' => $data->id], ['class' => 'titleLink', 'data-toggle' => 'tooltip', 'data-html' => 'true']);
                        },
                        'contentOptions' => ['class' => 'w80'],
                    ],
                    [
                        'attribute' => 'type',
                        'filter' => SiteServicesMember::getType(),
                        'value' => function ($data) {
                            $types = $data->getType();
                            return isset($types[$data->type]) ? $types[$data->type] : 'Yoxdur';
                        },
                        'contentOptions' => ['class' => 'w80'],
                    ],
                    [
                        'attribute' => 'connect_id',
                        'format' => 'raw',
                        'value' => function ($data) {
                            $con_name = $data->getConnect($data->type, $data->connect_id);
                            return $con_name;
                        },
                        'contentOptions' => ['class' => 'w80']
                    ],
                    [
                        'attribute' => 'service_id',
                        'filter' => [
                            1 => 'Premium paket',
                            6 => 'Facebbok paket',
                            7 => 'Instagram paket',
//                                8 => 'Youtube paket',
                            9 => 'Video sujet'
                        ],
                        'format' => 'raw',
                        'value' => function ($data) {
                            $con_name = $data->getServices($data->service_id);
                            return $data->type == 0 ? '<div class="tableBtn btn btn-primary btn-xs">' . $con_name . '</div>' : '<div class="tableBtn btn btn-danger btn-xs">' . $con_name . '</div>';
                        },
                        'contentOptions' => ['class' => 'w80']
                    ],
                    [
                        'attribute' => 'payment_date',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return !empty($data->payment_date) ? '<div class="tableBtn btn btn-primary btn-xs">' . date('d-m-Y', strtotime($data->payment_date)) . '</div>' : '<div class="tableBtn btn btn-danger btn-xs">----</div>';
                        },
                        'contentOptions' => ['class' => 'w80']
                    ],
                    [
                        'attribute' => 'expires_date',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return !empty($data->expires_date) ? '<div class="tableBtn btn btn-primary btn-xs">' . date('d-m-Y', strtotime($data->expires_date)) . '</div>' : '<div class="tableBtn btn btn-danger btn-xs">----</div>';
                        },
                        'contentOptions' => ['class' => 'w80']
                    ],
                    [
                        'class' => FilterActionColumn::className(),
                        'template' => '{view}',
                        'header' => 'Əməliyyatlar',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id], ['class' => 'btn button-update']);
                            },
                        ],
                        'contentOptions' => ['class' => 'w80']
                    ],
                ],
            ]);
            $urlStatusAction = Url::to(['status']);

            $this->registerJs("
            $(function(){
            $(\".status\").on('change',function(){
            var dataID = $(this).attr(\"data-id\");
            $.post(
            \"$urlStatusAction\",
            { id : dataID },
            function(data){
            return true;
            });
            });
            });
            ");

            ?>
        </div>
        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
               value="<?= Yii::$app->request->getCsrfToken(); ?>"/>
    </form>
</div>
