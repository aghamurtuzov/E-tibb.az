<?PHP
use backend\components\Functions;

echo '<div class="t-card mini -h-top">';
foreach($data['promotions'] as $key => $val)
{
    $link = Yii::$app->params['site.aksiya'].Functions::slugify($val['headline']).'-'.$val['id'];
    $expiredDiscount = Yii::$app->params['current.date'] > $val['date_end'] ? 'expired-discount' : null;
    ?>
    <a href="<?=$link?>" class="block">
        <div class="row align-items-center discount-block <?=$expiredDiscount?>">
            <div class="col-md-1 d-none d-lg-block">
                <p class="counter"><?=($key+1)?></p>
            </div>
            <div class="col-md col-8">
                <div class="detail">
                    <p class="name"><?=$val['headline']?></p>
                    <p class="expired"><span class="d-none d-sm-inline-block">Aksiya etibarlıdır:</span> <span><?=Functions::getDatetime($val['date_end'])?></span></p>
                </div>
            </div>
            <div class="col-md-auto col-4 discount-right text-right">
                <div class="price">
                    <span class="d-block dc price-blue pr-l bold"><?= $val["price"] - ($val["price"]*$val["discount"]/100)?><sup class="jis">M</sup></span>
                    <span class="d-block dc gray-color n-tshadow pr-s margin-clear"><?=$val['price']?><sup class="jis">M</sup></span>
                </div>
                <div class="ribbon">
                    <?PHP if($expiredDiscount != 'expired-discount'){?>
                        <p class="num"><?=$val["discount"]?></p><span class="percentage">%</span>
                        <span class="text">endirim</span>
                    <?PHP }else{ ?>
                        <span class="text">aksiya</span>
                        <span class="text">bitib</span>
                    <?PHP }; ?>
                </div>
            </div>
        </div>
    </a>
    <?PHP
}
echo '</div>';
?>