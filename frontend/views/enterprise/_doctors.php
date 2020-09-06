<?php
use frontend\models\SiteDoctorsModel;
use \backend\components\Functions;
use yii\helpers\Url;

$doctors = SiteDoctorsModel::getHospitalDoctors($enterprise["id"]);
$doctorsCount = SiteDoctorsModel::getCountHospitalDoctors($enterprise["id"]);
/*$specialists = SiteSpecialistsModel::getSpecialists();*/
?>
<!---------doctorlist------------>
<div class="t-tab-content padding-minus doctors-list">
    <div class="row align-self-center">
        <div class="col-md-6">
            <p class="darkblue-color">Tapılan həkim sayı: <span class="cyan-color bold"><?= $doctorsCount?></span></p>
        </div>
        <!--<div class="col-md-6 text-md-right">
            <select select2 class="w-100" data-placeholder="İxtisas seçin">
                <option value="0">Bütün həkimlər</option>
                <?php
/*                    foreach ($specialists as $specialist){
                        */?>
                        <option value="<?/*= $specialist["id"]*/?>"><?/*= $specialist["name"]*/?></option>
                <?php
/*                    }
                */?>
            </select>
        </div>-->
    </div>
    <div class="row -h-top" id="doctorBlock">
        <?php
        //print_r($doctor); exit();
            foreach ($doctors as $doctor){
//                $premium = false;
//                $premium_class = '';
//                if(date("Y-m-d")<$doctor["expires"]){
//                    $premium = true;
//                    $premium_class = ' premium';
//                }
//
//                $usaq  = " disb";
//                $eve_cagiris = " disb";
//                $feature = intval($doctor["feature"]);
//
//                if($feature==1){
//                    $eve_cagiris = "";
//                }elseif($feature == 2) {
//                    $usaq = "";
//                }elseif ($feature ==3){
//                    $eve_cagiris = "";
//                    $usaq = "";
//                }

                $specialist_name = $doctor["specialty"];
                ?>
                <div class="col-md-6">
                        <div class="doc-bl">
                            <div class="media">
                                <img class="mr-3 m-doctor-image-size" src="<?= $doctor['photo'] ? "/upload/enterprises/small/".$doctor['photo'] : Yii::$app->params['site.defaultThumbDoctor'] ?>" alt="<?= $doctor["name"]?>">
                                <div class="media-body">
                                    <div class="d-information">
                                        <p class="info name"><img src="assets/img/icon/name.png"><?= $doctor["name"]?></p>
                                        <p class="info pl"><img src="assets/img/icon/pl.png"> <?= $specialist_name?></p>
                                        <p class="info st">
                                            <img src="assets/img/icon/st.png" alt="is tecrubesi">
                                            <?= $doctor["experiance"]?> il iş təcrübəsi
                                            <span class="rating float-right"><span style="width:<?= intval($doctor["rating"])?>%" class="rating-inner"></span></span>
                                        </p>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
        <?php
            }
        ?>

    </div>


    <?php
        if($doctorsCount>count($doctors)){
    ?>
    <div class="row -h-top-2">
        <div class="col-md-12 text-center">
            <div class="col-md  button-group padding-clear">
                <button class="cb mini2 gray shadow modal-buttons inner-shadow" id="showMoreDoctor" data-id="<?= $enterprise["id"]?>" data-limit="<?= $doctorsCount?>" data-page-size="<?= Yii::$app->params["site.hospital_doctor_count"]?>"> Daha çox</button>
                <div id="loading" style="display: none">Yüklənir...</div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<!---------doctorlist------------>