<?PHP
use backend\components\Functions;
$function = new Functions();
$menus = $function->makeMenu($data['menus']['parent'],0);
if(!empty($menus)){ echo $menus; }
?>