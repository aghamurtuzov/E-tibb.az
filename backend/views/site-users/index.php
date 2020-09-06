<?phpuse yii\grid\GridView;use yii\helpers\Html;use yii\helpers\Url;use common\widgets\Alert;use backend\assets\AppAsset;use backend\components\FilterActionColumn;use backend\components\Functions;/* @var $this yii\web\View *//* @var $searchModel backend\models\SiteUsersSearch *//* @var $dataProvider yii\data\ActiveDataProvider */$this->title = 'Istifadəçilər';$this->params['breadcrumbs'][] = $this->title;?><div class="site-users-index moduleList">    <h1 class="font-25"><?= Html::encode($this->title) ?></h1>    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>    <div class="alert alert-info alert-dismissible fade in hidden-lg hidden-md hidden-sm" role="alert">        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>        <strong>Qeyd:</strong> Cədvəl tam görünmürsə sağa doğru sürüşdürün və ya telefonunuzun "ekran fırlanması" özəlliyini aktivləşdirin    </div>    <?= Alert::widget(); ?>    <form action="<?php echo Url::toRoute('deletemore'); ?>" method="POST">        <div class="table-responsive">            <?= GridView::widget([                'dataProvider' => $dataProvider,                'filterModel' => $searchModel,                'summary'=> "",                'tableOptions'=>[                    'class'=>'table table-striped table-bordered'                ],                'columns' => [                    [                        'attribute'=>'id',                        'enableSorting' => false,                        'contentOptions'=>['class'=>'w60']                    ],                    [                        'attribute'=>'name',                        'format'=>'raw',                        'value'=>function($data){                            return Html::a($data->name,['view','id'=>$data->id],['class'=>'titleLink','data-toggle'=>'tooltip','data-html'=>'true']);                        }                    ],                    [                        'attribute'=>'phone_number',                        'value'=>function($data)                        {                            return $data->phone_number;                        }                    ],                    [                        'attribute'=>'email',                        'value'=>function($data)                        {                            return $data->email;                        }                    ],                    [                        'attribute'=>'type',                        'value'=>function($data)                        {                            return $data->get_Type()[$data->type];                        }                    ],                    [                        'attribute'=>'last_login',                        'value'=>function($data)                        {                            return $data->last_login;                        }                    ],                    [                        'attribute'=>'status',                        'filter'=>[0=>'Təsdiq Edilməmiş', 1=>'Təsdiq Edilmiş', 2=>'Ödəniş Gözləmədədir'],                        'format'=>'raw',                        'value'=>function($data){                            if($data->type == 1){                                if($data->status == 1)                                {                                  return '<div class="tableBtn btn btn-primary btn-xs">Təsdiq Edildi</div>';                                }                                else if($data->status == 0)                                {                                    return '<div class="tableBtn btn btn-danger btn-xs"><a href="'.Yii::$app->homeUrl.'/doctors/create?id='.$data->id.'" style="color:#ffff;">Təsdiq Et</a></div>';                                }                                else if($data->status == 2){                                    return '<div class="tableBtn btn btn-warning btn-xs">Ödəniş Gözləmədədir</div>';                                }                            }else if($data->type == 2){                                if($data->status == 1)                                {                                    return '<div class="tableBtn btn btn-primary btn-xs">Təsdiq Edildi</div>';                                }                                else if($data->status == 0)                                {                                    return '<div class="tableBtn btn btn-danger btn-xs"><a href="'.Yii::$app->homeUrl.'/enterprises/create?id='.$data->id.'" style="color:#ffff;">Təsdiq Et</a></div>';                                }                                else if($data->status == 2)                                {                                    return '<div class="tableBtn btn btn-warning btn-xs">Ödəniş Gözləmədədir</div>';                                }                            }else if($data->type == 0){                                return $data->status == 1 ? '<div class="tableBtn btn btn-primary btn-xs">Təsdiq Edildi</div>':                                '<div class="tableBtn btn btn-danger btn-xs">Təsdiq Et</div>';                            }                                                    },                        'contentOptions'=>['class'=>'w60']                    ],                    [                        'class' => FilterActionColumn::className(),                        'template' => '{update} {delete} {del_check}',                        'filterContent' => Html::submitButton('Toplu sil',['class'=>'btn btn-danger button-delete-all','data-confirm'=>'Bu məlumatı silmək istədiyinizdən əminsinizmi?','data-method'=>'POST']),                        'header' => 'Əməliyyatlar',                        'buttons' => [                            'delete' => function($url, $model){                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id],[                                    'class'=>'btn button-delete',                                    'data' => [                                        'confirm' => 'Bu məlumatı silmək istədiyinizdən əminsinizmi?',                                        'method' => 'post',                                    ]                                ]);                            },                            'del_check' => function ($url, $model, $key) {                                return  "<div class=\"del_check\"><input type=\"checkbox\"  class=\"flat\" name=\"del_check[]\" value=\"{$model->id}\"></div>";                            }                        ],                        'contentOptions'=>['class'=>'ActionControl']                    ],                ],            ]);            $urlStatusAction = Url::to(['status']);            $this->registerJs("                    $(function(){                        $(\".status\").on('change',function(){                            var dataID = $(this).attr(\"data-id\");                            $.post(                            \"$urlStatusAction\",                            { id : dataID },                            function(data){                                return true;                            });                        });                    });                ");            ?>        </div>        <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />    </form></div>