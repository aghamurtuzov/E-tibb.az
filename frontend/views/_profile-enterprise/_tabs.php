<?php
use backend\components\Functions;
use yii\helpers\Url;

if(!empty($model->photo)){
//$enterprise['photo'] ? Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/small/'.$enterprise['photo'] : Yii::$app->params['site.defaultThumb1'];
    $src = Functions::getUploadUrl().Yii::$app->params['path.enterprises'].'/small/'.$model['photo'];
}else{
    $src = Yii::$app->params['site.defaultThumb1'];
}
?>
<?php if(Yii::$app->session->hasFlash("register_success")){
    echo '<div class="alert alert-success">'.Yii::$app->session->getFlash("register_success").'</div>';
}?>
<div class="all-block">
    <div class="t-card mini3 -h-top block profile">
        <div class="profile-tab">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <img class="img-fluid" src="<?=$src;?>" alt="<?= $model["name"]?>">
                </div>
                <div class="right-text col-md-8 col-sm-8 col-xs-12">
                    <a href="/obyekt-tenzimlemeler" class="edit">Tənzimləmələr <i class="fa fa-pen"></i></a>
                    <ul class="doc-info">
                        <li class="object-ic"><?= $model["name"]?></li>
                    </ul>
                    <div class="profile-information margin-clear">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="infos ext">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <p><img src="assets/img/icon/date-d.png" alt="date">
                                            </p>
                                            <p><img src="assets/img/icon/time.png" alt="time">
                                            </p>
                                        </td>
                                        <td>
                                            <p><span class="lb-tit">B.e - Cümə</span></p>
                                            <p><span class="lb-val"><?= $model["weekdays"]?></span></p>
                                        </td>
                                        <td>
                                            <p><span class="lb-tit">Şənbə</span></p>
                                            <p><span class="lb-val"><?= $model["saturday"]?></span></p>
                                        </td>
                                        <td>
                                            <p><span class="lb-tit">Bazar</span></p>
                                            <p><span class="lb-val"><?= $model["sunday"]?></span></p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs nav-fill">
                <?php
                    if(isset($pages)){
                        foreach ($pages as $page){
                            $class_active = '';
                            if(isset($page_type) and $page["page_type"] == $page_type){
                                $class_active = ' active';
                            }
                            echo '<a class="nav-item nav-link'.$class_active.'" href="/profil/'.$page["page_link"].'">'.$page["name"].'</a>';
                        }
                    }
                ?>
            </div>
        </nav>
    </div>
</div>