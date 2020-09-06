<?php
if ($promotions) {
    $i = 1;
    foreach ($promotions as $promotion) {
        $count = $i;
        if (strlen($i) == 1) $count = "0" . $i;

        $expired_class = '';
        $href = \yii\helpers\Url::to(["aksiya/" . \backend\components\Functions::slugify($promotion["headline"], ['transliterate' => true]) . "-" . $promotion["id"]]);
        if (date("Y-m-d") > $promotion["date_end"]) {
            $expired_class = ' expired-discount';
            $href = 'javascript:void(0);';
        }
        ?>
        <div class="block-back pharmacy-sales">
            <div class="sales-content">
                <div class="row ">
                    <div class="col-md-1">
                        <div class="percent">
                            <span><?= $count ?></span>
                        </div>
                    </div>
                    <div class="col-md-11">
                        <div class="pharmacy-about">
                            <h5><a href="<?= $href ?>"><?= $promotion["headline"] ?></a></h5>
                            <p>Aksiya etibarlıdır:</p>
                            <span class="date-gray"> <?= date("d-m-Y", strtotime($promotion["date_end"])) ?></span>
                            <div class="coin">
                                <?php if($promotion['discount']){ ?>
                                <h4><?= $promotion["price"] - ($promotion["price"] * $promotion["discount"] / 100) ?> <sup>M</sup></h4>
                                <h6><?= $promotion["price"] ?> <sup>M</sup></h6>
                                <?php } else{ ?>
                                    <h4><?= $promotion["price"] ?> <sup>M</sup></h4>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="sale-flag">
                            <div class="flag-content">
                            <?php
                                if (date("Y-m-d") <= $promotion["date_end"]) {
                            ?>
                                <h3><?= $promotion["discount"] ?> <sup>%</sup></h3>
                                <span>ENDİRİM</span>
                                <?php } else {
                                    ?>
                                    <span>AKSİYA BİTİB</span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $i++;
    }
}
?>