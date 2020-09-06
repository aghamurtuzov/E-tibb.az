<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use backend\assets\AppAsset;
use backend\components\FilterActionColumn;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'İdarəçilər';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-users-index moduleList">

    <h1 class="font-25"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Əlavə et', ['create'], ['class' => 'btn btn-success']) ?>
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
                    //['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute'=>'id',
                        'enableSorting' => false,
                        'contentOptions'=>['class'=>'w60']
                    ],
                    [
                        'attribute'=>'name',
                        'enableSorting' => false,
                        'format'=>'raw',
                        'value'=>function($data)
                        {
                            return Html::a($data->name,['update','id'=>$data->id],['class'=>'titleLink','title'=>'Duzəliş et','data-toggle'=>'tooltip']);
                        }
                    ],
                    [
                        'attribute'=>'username',
                        'enableSorting' => false
                    ],
                    [
                        'attribute'=>'status',
                        'filter'=>Yii::$app->params['filter.global.status'],
                        'format'=>'raw',
                        'value'=>function($data)
                        {
                            $checked = !empty($data->status) ? 'checked': '';
                            $title = !empty($data->status) ? 'Deaktiv et': 'Aktiv et';
                            return "<div class=\"pull-right\" data-toggle=\"tooltip\" title=\"{$title}\"><input type=\"checkbox\" data-id=\"{$data->id}\" name=\"status\" class=\"js-switch status\" {$checked} /></div>";
                        },
                        'contentOptions'=>['class'=>'w110']
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

            $this->registerCssFile("@web/vendor/switchery/dist/switchery.min.css",['depends' => [AppAsset::className()]]);
            $this->registerJsFile("@web/vendor/switchery/dist/switchery.min.js",['depends' => [AppAsset::className()]]);

?>
        </div>
        <input type="hidden" name="<?=Yii::$app->request->csrfParam?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
    </form>
</div>
