<?PHP
use frontend\models\SearchForm;
use yii\helpers\Url;
use yii\helpers\Html;

$searchModel = new SearchForm();

$header_class = (isset(Yii::$app->view->params['header_class']) && !empty(Yii::$app->view->params['header_class'])) ? Yii::$app->view->params['header_class'] : '';
?>

<header class="<?=$header_class?>">
    <div class="menu-top">
        <div class="container">
            <div class="row">
                <div class="col-md-5 hidden-sm hidden-xs">
                    <ul class="nav navbar-nav">
                        <li><a href="<?=Yii::$app->params['site.url']?>haqqimizda">Haqqımızda</a></li>
                        <li><a href="<?=Yii::$app->params['site.url']?>qaydalar">Qaydalar</a></li>
                        <li><a href="<?=Yii::$app->params['site.url']?>qanver">Qan ver</a></li>
                        <li><a href="<?=Yii::$app->params['site.url']?>elaqe">Bizimlə əlaqə</a></li>
                    </ul>
                </div>
                <div class="col-md-7 col-xs-12 text-right">
                    <?php if(Yii::$app->user->isGuest){ ?>
                    <div class="before-register relative">
                        <div class="search-block">
                            <div class="search-part">
                                <form action="/xeberler"  method="GET">
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control" placeholder="Axtarış...">
                                    </div>
                                    <button class="transparent close-search" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
                                </form>
                            </div>
                            <button class="btn transparent search-icon" type="button"><i class="fa fa-search"></i></button>
                        </div>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/hekim-qeydiyyat">
                                    <button class="transparent" >Qeydiyyat </button><!-- data-toggle="modal" data-target=".bs-example-modal-sm-1" -->
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#"><button class="transparent" data-toggle="modal" data-target="#login">Daxil ol</button></a>
                            </li>
                        </ol>
<!--                        <a href="" class="premium-account">Premium hesab</a>-->
                    </div>
                    <?php }else{ ?>
                    <div class="user menu-bottom relative">
                        <div class="search-block">
                            <div class="search-part">
                                <form action="">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Axtarış...">
                                    </div>
                                    <button class="transparent close-search" type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
                                </form>
                            </div>
                            <button class="btn transparent search-icon" type="button"><i class="fa fa-search"></i></button>
                        </div>
                        <ul class="nav navbar-nav">
                            <li>
                                <?php
                                    if(Yii::$app->user->identity->type == 0){
                                        $tenzimlemeler = ['/tenzimlemeler'];
                                    }elseif(Yii::$app->user->identity->type == 1){
                                        $tenzimlemeler = ['/admin/doctor#/setting'];
                                    }elseif(Yii::$app->user->identity->type == 2){
                                        $tenzimlemeler = ['/admin/enterprise#/setting'];
                                    }
                                ?>
                                <a href="<?=Url::to(['/profile'])?>"><span class="new-company">Panel</span></a>
                            </li>
                            <li class="dropdown">
                                <a href="<?= Url::to(["profil/index"]) ?>" class="dropdown-toggle" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <?= Yii::$app->user->identity->name?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <?php
                                        if(!empty($pages)){
                                            foreach ($pages as $page){
                                                echo '<li><a href="/profil/'.$page["page_link"].'">'.$page["name"].'</a></li>';
                                            }
                                        }
                                        if(Yii::$app->user->identity->type == 0){
                                            $tenzimlemeler = ['/tenzimlemeler'];
                                        }elseif(Yii::$app->user->identity->type == 1){
                                            $tenzimlemeler = ['/admin/doctor#/setting'];
                                        }elseif(Yii::$app->user->identity->type == 2){
                                            $tenzimlemeler = ['/admin/enterprise#/setting'];
                                        }
                                    ?>
                                    <li><a href="<?= Url::to(['/profile']) ?>">Profil</a></li>
                                    <li><a href="<?= Url::to($tenzimlemeler) ?>">Redaktə edin</a></li>
                                    <li class="logout"><?= Html::beginForm(['/site/logout'], 'post').Html::submitButton('Çıxış',["class" => "link-button",]).Html::endForm() ?></li>
                                </ul>
                            </li>
                            <li>
                                <div class="profile-image">
                                <?php
                                    $userData = \frontend\models\User::findIdentity(Yii::$app->user->identity->getId());

                                    if(strlen($userData['photo'])>1)
                                    {
                                        $photo_exist = true;
                                        $userImage = Yii::$app->params['site.url']."upload/users/small/".$userData['photo'];
                                    }
                                    else
                                    {
                                        $photo_exist = false;
                                        $userImage = Yii::$app->params['site.url']."assets/img/user.png";
                                    }
                                ?>
                                    <img class="<?=($photo_exist==true) ? 'photo_exist_top' : ''?>" src="<?=$userImage;?>" alt="img-user">
                                </div>
                            </li>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="menu">
        <nav class="navbar">
            <div class="container">
                <div class="menu-bottom">
                    <div class="navbar-header-part">
                        <div class="nav-left">
                            <ul class="navbar-nav nav">
                                <li><a class="navbar-brand" href="<?=Yii::$app->params['site.url'];?>"><img src="<?=Url::base();?>/assets/img/logo_img.png" alt="logo" class="center-block"></a></li>
<!--                                <li><a href="#" class="btn transparent">TİBBİ MAĞAZA</a></li>-->
                            </ul>
                        </div>
                    </div>
                    <?php $current_url = Yii::$app->request->getPathInfo(); ?>
                    <ul class="nav navbar-nav nav-right web">
                        <li <?php if($current_url == "hekimler") echo 'class="active"'; ?> ><a href="<?=Yii::$app->params['site.url'].'hekimler';?>">HƏKİMLƏR</a></li>
                        <li <?php if($current_url == "beledci/klinikalar-1") echo 'class="active"'; ?> ><a href="<?=Yii::$app->params['site.url'].'beledci/klinikalar-1';?>">KLİNİKALAR</a></li>
                        <li <?php if($current_url == "beledci/aptekler-6") echo 'class="active"'; ?> ><a href="<?=Yii::$app->params['site.url'].'beledci/aptekler-6';?>">APTEKLƏR</a></li>
                        <li <?php if($current_url == "aksiyalar") echo 'class="active"'; ?> ><a href="<?=Yii::$app->params['site.url'].'aksiyalar';?>">AKSİYALAR</a></li>
                        <li <?php if($current_url == "xeberler") echo 'class="active"'; ?> ><a href="<?=Yii::$app->params['site.url'].'xeberler';?>">XƏBƏRLƏR</a></li>
                    </ul>
                    <button type="button" class="menu-btn dropdown">
                        <a href="#" class="dropdown-toggle btn transparent" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">MENU  <span><i class="fa fa-bars" aria-hidden="true"></i></span></a>
                        <ul class="dropdown-menu">
                            <li class="mobile"><a href="<?=Yii::$app->params['site.url'].'hekimler';?>">Həkimlər</a></li>
                            <li class="mobile"><a href="<?=Yii::$app->params['site.url'].'aksiyalar';?>">Aksiyalar</a></li>
                            <li class="mobile"><a href="<?=Yii::$app->params['site.url'].'xeberler';?>">Xəbərlər</a></li>
                            <?php
                            if(isset($data['menus']))
                            {
                                foreach($data['menus']['type'][2] as  $val)
                                {
                                    $target = ($val['target'] == 1 )? 'target="_blank"' : '';
                                    $link   = $val['link'];
                                    if($val['type'] == 2){
                                        $link = Yii::$app->params['site.url'].Yii::$app->params['site.enterprise_slug'].'/'.$val['link'].'-'.$val['id'];
                                        echo "<li><a href='".$link."'>".$val['name']."</a></li>";
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </button>
                </div>
            </div>
        </nav>
    </div>
</header>