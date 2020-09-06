<?PHP
use yii\helpers\ArrayHelper;
use frontend\components\Menu;
$getMenus      = ArrayHelper::toArray(new Menu());
$data['menus'] = $getMenus['list'];
echo $this->render('partials/amp/header',['data'=>$data]);
echo $content;
echo $this->render('partials/amp/footer',['data'=>$data]);
?>
