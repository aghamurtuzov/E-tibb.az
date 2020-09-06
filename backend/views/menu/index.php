<?php
 
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use backend\assets\AppAsset;
use backend\components\FilterActionColumn;
use backend\components\Functions;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SiteMenusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Menyu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-menus-index moduleList">

    <h1 class="font-25"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Əlavə Et', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="alert alert-info alert-dismissible fade in hidden-lg hidden-md hidden-sm" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <strong>Qeyd:</strong> Cədvəl tam görünmürsə sağa doğru sürüşdürün və ya telefonunuzun "ekran fırlanması" özəlliyini aktivləşdirin
    </div>

    <?= Alert::widget(); ?>

    <form action="<?php echo Url::toRoute('deletemore'); ?>" method="POST">
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary'=> "",
                'tableOptions'=>[
                    'class'=>'table table-striped table-bordered'
                ],
                'columns' => [
                    [
                        'attribute'=>'id',
                        'enableSorting' => false,
                        'contentOptions'=>['class'=>'w60']
                    ],
                    [
                        'attribute'=>'name',
                        'format'=>'raw',
                        'value'=>function($data){
                            return Html::a($data->name,['update','id'=>$data->id],['class'=>'titleLink','data-toggle'=>'tooltip','data-html'=>'true']);
                        }
                    ],
                    [
                        'attribute'=>'menu_order',
                        'value'=>function($data)
                        { 
                            return $data->menu_order;
                        }
                    ],
//                    [
//                        'attribute'=>'link',
//                        'value'=>function($data)
//                        {
//                            return $data->link;
//                        }
//                    ],
                    [
                        'attribute'=>'type',
                        'value'=>function($data)
                        {
                            $types = $data->get_Type();
                            return isset($types[$data->type]) ? $types[$data->type] : null;
                        }
                    ],
                    [
                        'attribute'=>'position',
                        'filter'=>[1=>'Üst', 2=>'Alt'],
                        'format'=>'raw',
                        'value'=>function($data){
                            return ($data->position == 1? '<div class="tableBtn btn btn-primary btn-xs">Üst</div>':'<div class="tableBtn btn btn-danger btn-xs">Alt</div>');
                        },
                        'contentOptions'=>['class'=>'w80']
                    ],
                    [
                        'attribute'=>'hidden',
                        'filter'=>[0=>'Görünən', 1=>'Görünməz'],
                        'format'=>'raw',
                        'value'=>function($data){
                            return $data->hidden == 0 ? '<div class="tableBtn btn btn-primary btn-xs">Görünən</div>': '<div class="tableBtn btn btn-danger btn-xs">Görünməz</div>';
                        },
                        'contentOptions'=>['class'=>'w80']
                    ],
                    [
                        'attribute'=>'status',
                        'filter'=>[0=>'Passiv', 1=>'Aktiv'],
                        'format'=>'raw',
                        'value'=>function($data){
                            return $data->status == 1 ? '<div class="tableBtn btn btn-primary btn-xs">Aktiv</div>': '<div class="tableBtn btn btn-danger btn-xs">Passiv</div>';
                        },
                        'contentOptions'=>['class'=>'w80']
                    ],
                    [
                        'class' => FilterActionColumn::className(),
                        'template' => '{update} {delete} {del_check}',
                        'filterContent' => Html::submitButton('Toplu sil',['class'=>'btn btn-danger button-delete-all','data-confirm'=>'Bu məlumatı silmək istədiyinizdən əminsinizmi?','data-method'=>'POST']),
                        'header' => 'Əməliyyatlar',
                        'buttons' => [
                            'update' => function($url, $model){
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>',['update','id'=>$model->id],['class'=>'btn button-update']);
                            },
                            'delete' => function($url, $model){
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id],[
                                    'class'=>'btn button-delete',
                                    'data' => [
                                        'confirm' => 'Bu məlumatı silmək istədiyinizdən əminsinizmi?',
                                        'method' => 'post',
                                    ]
                                ]);
                            },
                            'del_check' => function ($url, $model, $key) {
                                return  "<div class=\"del_check\"><input type=\"checkbox\"  class=\"flat\" name=\"del_check[]\" value=\"{$model->id}\"></div>";
                            }
                        ],
                        'contentOptions'=>['class'=>'ActionControl']
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
        <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
    </form>
</div>

