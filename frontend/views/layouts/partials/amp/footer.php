<?PHP
use yii\helpers\Url;
$menus = $data['menus']['parent'][0];
$bottomMenus = isset($data['menus']['position'][2]) ? $data['menus']['position'][2] : [];
if(!empty($bottomMenus))
{
    foreach($bottomMenus as $key => $val)
    {
        $menus[] = $val;
    }
}
$count = count($menus);
$notShow = [3,5];
$notStaticPrefix = [38,39];
?>
<section class="footer1 cid-qN7nkHa8tL" id="footer1-9d">
    <div class="container">

        <div class="mbr-row mbr-justify-content-center main-row">

            <?PHP if($menus){ ?>
            <div class="mbr-col-lg-6 mbr-col-md-12 mbr-col-sm-12">
                <div class="items mbr-white">

                    <?PHP
                    for($x=0;$x<7;$x++)
                    {
                        $val  = $menus[$x];
                        if(!in_array($val['id'],$notShow))
                        {
                            $link = $val['link'];

                            if($val['type'] == 2){
                                $link = Yii::$app->params['site.enterprise_slug'].'/'.$val['link'].'-'.$val['id'];
                            }elseif($val['type'] == 3){
                                $link = 'kateqoriya/'.$val['link'];
                            }else if($val['type'] == 1){
                                if(!in_array($val['id'],$notStaticPrefix))
                                {
                                    $link = Yii::$app->params['site.static_slug'].'/'.$val['link'];
                                }
                            }
                            $link = Url::base(true).'/'.$link;
//                          $link = isset($data[$val['id']]) ? "javascript:void(0);" : $link;
                    ?>
                            <div class="list-item">
                                <span class="mbr-iconfont fa-chevron-right fa"></span>
                                <h5 class="mbr-fonts-style text display-7">
                                    <a href="<?=$link?>"><?=$menus[$x]['name']?></a>
                                </h5>
                            </div>
                    <?PHP
                        };
                    };
                    ?>

                </div>
            </div>

            <div class="mbr-col-lg-6 mbr-col-md-12 mbr-col-sm-12">
                <div class="items mbr-white">

                    <?PHP
                    for($x = 7;$x<$count;$x++)
                    {
                        $val  = $menus[$x];
                        if(!in_array($val['id'],$notShow))
                        {
                            $link = $val['link'];

                            if($val['type'] == 2){
                                $link = Yii::$app->params['site.enterprise_slug'].'/'.$val['link'].'-'.$val['id'];
                            }elseif($val['type'] == 3){
                                $link = 'kateqoriya/'.$val['link'];
                            }else if($val['type'] == 1){
                                if(!in_array($val['id'],$notStaticPrefix))
                                {
                                    $link = Yii::$app->params['site.static_slug'].'/'.$val['link'];
                                }
                            }
                            $link = Url::base(true).'/'.$link;
//                          $link = isset($data[$val['id']]) ? "javascript:void(0);" : $link;
                            ?>
                            <div class="list-item">
                                <span class="mbr-iconfont fa-chevron-right fa"></span>
                                <h5 class="mbr-fonts-style text display-7">
                                    <a href="<?=$link?>"><?=$menus[$x]['name']?></a>
                                </h5>
                            </div>
                            <?PHP
                        };
                    };
                    ?>

                </div>
            </div>
            <?PHP }; ?>

        </div>

    </div>
</section>
<section class="footer1 cid-qN7nkHa8tL_56" id="footer1-10d">
    <div class="container">

        <div class="mbr-row mbr-justify-content-center main-row">
            <div class="mbr-col-lg-12 mbr-col-md-12 mbr-col-sm-12">
                <div class="mbr-fonts-style text align-center display-7 style1000">© <?=date('Y')?> E-tibb.az - Bütün hüquqlar qorunur</div>
            </div>
        </div>

    </div>
</section>