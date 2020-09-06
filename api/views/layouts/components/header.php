<?PHP

use yii\helpers\Html;

?>
<!-- top navigation -->
<div class="top_nav">

    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?= Yii::$app->homeUrl; ?>/images/default_user.png" alt="Admin photo"><?= Yii::$app->user->identity->name ?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li>
                            <?= Html::beginForm(['/site/logout'], 'post').Html::submitButton('Çıx').Html::endForm() ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>

</div>
<!-- /top navigation -->