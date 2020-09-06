<div class="login-menu">
    <nav class="navbar navbar-default">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?=Yii::$app->params['site.url']?>"><img src="https://new.e-tibb.az/assets/img/logo_img_register.png" alt="logo"></a>
        </div>

        <?php
            $isUserResiter = false;
            $enterpriseClinic = $enterpriseAptech = $doctor = false;
            if(Yii::$app->controller->id=="auth-enterprise" && Yii::$app->controller->action->id=="register")
            {
                $id = Yii::$app->request->get('id');

                if($id==6)
                    $enterpriseAptech = true;
                else
                    $enterpriseClinic = true;
            }

            if(Yii::$app->controller->id=="auth-doctor" && Yii::$app->controller->action->id=="register")
            {
                $doctor = true;
            }

            if(Yii::$app->controller->id=="auth" && Yii::$app->controller->action->id=="register3")
            {
                $isUserResiter = true;
            }
        ?>

        <div class="text-center">
            <h3 class="register_title_rp">Qeydiyyatdan keç</h3>
        </div>

        <ul class="nav navbar-nav navbar-right navbar-register">
            <li <?= ($doctor==true) ? "class='active'" : ""?>><a href="<?=Yii::$app->params["site.url"];?>hekim-qeydiyyat">Həkim <?= ($doctor==true) ? '<span class="sr-only">(current)</span>' : '' ?></a></li>
            <li <?= ($isUserResiter==true) ? "class='active'" : ""?>><a href="<?=Yii::$app->params["site.url"];?>istifadeci-qeydiyyat">İstifadəçi <?= ($isUserResiter==true) ? '<span class="sr-only">(current)</span>' : '' ?></a></li>
            <li <?= ($enterpriseClinic==true) ? "class='active'" : ""?>><a href="<?=Yii::$app->params["site.url"];?>obyekt-qeydiyyat/1">Klinika <?= ($enterpriseClinic==true) ? '<span class="sr-only">(current)</span>' : '' ?></a></li>
            <li <?= ($enterpriseAptech==true) ? "class='active'" : ""?>><a href="<?=Yii::$app->params["site.url"];?>obyekt-qeydiyyat/6">Aptek <?= ($enterpriseAptech ==true) ? '<span class="sr-only">(current)</span>' : '' ?></a></li>
        </ul>
    </nav>
</div>