<div class="col-md-3 left_col">
    <div class="left_col scroll-view">

        <div class="navbar nav_title" style="border: 0;">
            <a href="<?= Yii::$app->homeUrl; ?>" class="site_title"><i class="fa fa-star"></i> <span>E-tibb.az</span></a>
        </div>
        <div class="clearfix"></div>

        <!-- menu prile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="<?= Yii::$app->homeUrl; ?>/images/default_user.png" alt="Admin photo" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Xoşgəlmisiniz,</span>
                <h2><?= Yii::$app->user->identity->name ?></h2>
            </div>
        </div>
        <!-- /menu prile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            <div class="menu_section">
                <!--<h3>General</h3>-->
                <?=
                \yiister\gentelella\widgets\Menu::widget(
                    [
                        "items" => [
                            ["label" => "Ana səhifə",   "url" => ["site/index"], "icon" => "home"],
                            ["label" => "Menyu",        "url" => ["menu/index"],  "icon" => "columns"],
                            //["label" => "İdarəçilər",   "url" => ["user/index"], "icon" => "user"],
                            ["label" => "İxtisaslar",   "url" => ["specialists/index"], "icon" => "stethoscope"],
                            ["label" => "Həkimlər",     "url" => ["doctors/index"], "icon" => "user-md"],
                            ["label" => "Mövzular",     "url" => ["packages-types/index"], "icon" => "money"],
                            ["label" => "Elanlar",      "url" => ["ads/index"], "icon" => "file-text"],
                            ["label" => "Abunəçilər",   "url" => ["services-member/index"], "icon" => "line-chart"],
                            ["label" => "Obyektlər",    "url" => ["enterprises/index"], "icon" => "plus-square"],
                            ["label" => "Xəbərlər",     "url" => ["news/index"], "icon" => "file-text"],
                            ["label" => "Aksiyalar",    "url" => ["promotions/index"], "icon" => "gift"],
                            ["label" => "Rəylər",       "url" => ["comments/index"], "icon" => "user-md"],
                            ["label" => "Randevu",      "url" => ["appointment/index"], "icon" => "trello"],
                            ["label" => "Istifadəçilər","url" => ["site-users/index"], "icon" => "users"],
                            ["label" => "Sual-Cavab",   "url" => ["consultation/index"], "icon" => "question"],
                        ],
                    ]
                )
                ?>
            </div>

        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="">
                <span class="glyphicon" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="">
                <span class="glyphicon" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="">
                <span class="glyphicon" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="">
                <span class="glyphicon" aria-hidden="true"></span>
            </a>
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>