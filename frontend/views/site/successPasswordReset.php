<?php

use backend\components\Functions;

use yii\helpers\Url;

?>

<div class="t-card mini3 -h-top">

    <div class="row">

        <div class="col col-md-6 text-left d-none d-md-block">

            <h2 class="breadcrumb-title">ŞİFRƏNİZ YENİLƏNDİ</h2>

        </div>

        <div class="col col-md-6 text-left text-md-right">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb" style="padding:15px 0 8px;">

                    <li class="breadcrumb-item"><a href="#">Ana səhifə</a></li>

                    <li class="breadcrumb-item active" aria-current="page">Şifrəniz yeniləndi</li>

                </ol>

            </nav>

        </div>

    </div>

</div>

<div class="row -h-top">

    <div class="col-md-12 col-12">

        <div class="t-card">

            <div class="blog-inner">

                <?PHP

                if(Yii::$app->session->hasFlash('success-pass-reset')){

                    $msg = Yii::$app->session->getFlash('success-pass-reset');

                    echo "<div class=\"alert alert-success\" role=\"alert\">{$msg}</div>";

                };

                ?>

            </div>

        </div>

    </div>

</div>

