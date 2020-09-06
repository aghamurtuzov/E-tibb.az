<?php
use backend\components\Functions;
/*echo Yii::$app->controller->action->id;*/
$current_id = 0;
if(Yii::$app->controller->id=='enterprise' and Yii::$app->controller->action->id=='index'){
    $current_id  = Yii::$app->controller->actionParams["id"];
}

?>
<div class="t-widget">
    <?php
        if(!empty($data['menus']['type'][3])){
            //echo "<pre>"; print_r($data['menus']['type'][3]);
    ?>
    <div class="t-widget-header">
        <h4>Kateqoriyalar</h4>
    </div>
    <div class="t-widget-body">

        <ul class="category-menu">
            <?php
                foreach($data['menus']['type'][2] as $menu){
                    $active_class = '';
                    if($menu["id"]==$current_id){
                        $active_class=" class='active'";
                    }
            ?>
            <li<?= $active_class?>><a href="<?="beledci/".Functions::slugify($menu['name'],['transliterate' => true])."-".$menu['id']?>"><?=$menu['name'];?></a></li>
            <?php } ?>
        </ul>
    </div>
    <?php } ?>
</div>