<?php
use yii\helpers\Url;
use backend\components\Functions;
$degrees = \common\models\SiteDoctors::getDegree();
$specialist_name = '';
$specialists = \frontend\models\SiteDoctorsModel::getDoctorSpecialist($model->id);
if(!empty($specialists)){
    $i=1;
    foreach ($specialists as $specialist){
        $sep = '';
        if($i>1) $sep = ',';
        $specialist_name .= $sep.$specialist["name"];
        $i++;
    }
}
$photo = !empty($model['photo']) ? Functions::getUploadUrl().Yii::$app->params['path.doctor'].'/'.$model['photo'] : Yii::$app->params['site.defaultThumbDoctor'];
?>
<?php if(Yii::$app->session->hasFlash("register_success")){
    echo '<div class="alert alert-success">'.Yii::$app->session->getFlash("register_success").'</div>';
}?>
<div class="all-block">
    <div class="t-card mini3 block profile">

        <div class="profile-tab">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <img class="img-fluid" src="<?= $photo?>" alt="<?= $model->name?>">
                </div>
                <div class="right-text col-md-8 col-sm-8 col-xs-12">
                    <a href="/hekim-tenzimlemeler" class="edit">Tənzimləmələr <i class="fa fa-pen"></i></a>
                    <ul class="doc-info">
                        <li><?php echo  isset($degrees[$model->degree])?$degrees[$model->degree]:'Həkim'; ?></li>
                        <li><?= $model->name?></li>
                        <li><?= $specialist_name?></li>
                        <li><?PHP if(!empty($model->experience)){ echo $model->experience.' il iş təcrübəsi'; }; ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <nav>
            <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
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
                <!--<a class="nav-item nav-link active" id="nav-home-tab" href="<?/*=Url::base().'/profile/sual-cavab';*/?>">Suallara cavab ver</a>
                <a class="nav-item nav-link " id="nav-profile-tab" href="<?/*=Url::base().'/profile/aksiyalar';*/?>">Kampaniyalarını əlavə et</a>
                <a class="nav-item nav-link" id="nav-contact-tab" href="<?/*=Url::base().'/profile/qebul-gunleri';*/?>">Qəbul günlərini düzəlt</a>-->
            </div>
        </nav>
    </div>
</div>
