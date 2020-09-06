<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use backend\components\Functions;
use common\widgets\Alert;
use frontend\components\Menu;
use yii\helpers\ArrayHelper;
use backend\models\SiteSpecialists;
use frontend\models\MainModel;
use frontend\models\NewsModel;
use frontend\models\SearchModel;
use frontend\models\PromotionModel;
use frontend\models\SiteAdressesModel;
$request       = Yii::$app->request;
$model         = new MainModel();
$getMenus      = ArrayHelper::toArray(new Menu());
$data['menus'] = $getMenus['list'];
$form_cat      = ['Seçin','Xəbərlər','Həkimlər','Obyektlər'];

if ($request->isGet)
{   $formdata = $request->get('SearchForm');
    if(isset($formdata) and !empty($formdata)){
        if ( (isset($formdata['cs']) && !empty($formdata['cs'])) && (isset($formdata['q']) && !empty($formdata['q']))  ) {
            $cat_c      = $formdata['c'];
            $like_q     = $formdata['q'];
            $sub_c      = $formdata['cs'];
        }else if( empty($formdata['cs']) and !empty($formdata['q']) and  !empty($formdata['c'])){
            $cat_c      = $formdata['c'];
            $like_q     = $formdata['q'];
        }else if( ( isset($formdata['cs']) and !empty($formdata['cs']) ) and empty($formdata['q']) ){
            $cat_c      = $formdata['c'];
            $sub_c      = $formdata['cs'];
        }else if(empty($formdata['cs']) and !empty($formdata['q']) and empty($formdata['c'])){
            $cat_c      = 3;
            $sub_c      = 33;
            $like_q     = $formdata['q'];
        }else if( (empty($formdata['cs']) and empty($formdata['q']) ) and  !empty($formdata['c'])){
            $cat_c = $formdata['c'];
        }
        if(!empty($cat_c)){
            switch ($cat_c) {
                case 1:
                    $sublist = $model->get_Category(3);
                    break;
                case 2:
                    $sublist  = SiteSpecialists::find()->orderBy('name','ASC')->all();
                    break;
                case 3:
                    $sublist = $model->get_Category(2);
                    break;
                default:
                    break;
            }
        }

    }
    else
    {
        $cat_c = 3;
        $sub_c = 33;
        $sublist = $model->get_Category(2);
    }
}

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="no-js home-page">
<?= $this->render("partials/head",['data' => $data])?>
<body>
<?php $this->beginBody() ?>
<?=$this->render('partials/header',['data' => $data]); ?>
<div class="container-fluid inner-menu">
    <div class="container">
        <div class="row">
            <div class="col-md d-none d-lg-block">
                <?=$this->render('partials/nav',['data' => $data]); ?>
            </div>
        </div>
    </div>
</div>
<div class="container content-side">
    <div class="row">
        <div class="col-md-12 col-lg-9 custom-col-9 col-12">
            <div class="t-card mini3 search-box">
                <form id="search-form" action="/axtar" method="get">
                     <div class="row">
                        <div class="form-group custom-group">
                            <img src="assets/img/icon/axtaris.png" alt="axtarish">
                            <input placeholder="Axtar" class="form-control" type="text" name="SearchForm[q]" value="<?PHP if(!empty($like_q)) echo $like_q; ?>">
                        </div>
                        <div class="form-group">
                            <img src="assets/img/icon/novu.png" alt="novu">
                            <select  class="selectpicker form-control" onchange="list_select(this.value)" title="Seçin" name="SearchForm[c]">
                                <?php
                                foreach ($form_cat as $key=>$item) {
                            ?>
                                <option value="<?=$key;?>" <?php if(!empty($cat_c) and $key==$cat_c) echo'selected'; ?> > <?=$item;?> </option>
                             <?php
                                }
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <img src="assets/img/icon/obj%20-%20Copy.png" alt="obj">
                            <select id="searchform-ls" class="selectpicker form-control" title="Bütün Kateqoriyalar" name="SearchForm[cs]">
                            <?php
                                if(!empty($sublist)){
                                    foreach ($sublist as $item) {
                            ?>
                               <option value="<?=$item['id']; ?>" <?php if ( !empty($sub_c) and $item['id'] == $sub_c) echo 'selected'; ?> > <?= $item['name']; ?> </option>
                            <?php
                                    }
                                }
                            ?>
                            </select>
                        </div>
                        <button type="submit" class="cb orange orange-hover shadow inner-shadow">Axtar</button>
                    </div>
                </form>
                </div>
            <?= $content ?>
            </div>
            <div class="col-md-3 custom-col-3 d-none d-lg-block">
                <?=$this->render('partials/sidebar_nav',['data' => $data]); ?>
                <?=$this->render('partials/sidebar',['data' => $data]); ?>
            </div>
        </div>
    </div>
</div>

<?=$this->render('partials/footer',['data'=>$data]); ?>

<?PHP //$this->render('partials/modals/login',['data'=>$data]); ?>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
