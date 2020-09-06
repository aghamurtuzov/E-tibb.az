<div class="t-card -h-top">
    <div class="row">
        <div class="col-md-12 col-12">
            <h2 class="ms-title margin-clear">Profil</h2>
        </div>
    </div>
    <div class="row -h-top">
        <div class="col-md-auto col-12">
            <?php
                if(Yii::$app->session->hasFlash("warning")){
                    echo "<p>".Yii::$app->session->getFlash("warning")."</p>";
                }
            ?>
        </div>
    </div>
</div>

