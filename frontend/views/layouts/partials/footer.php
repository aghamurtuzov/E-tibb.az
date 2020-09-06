<?PHP
use yii\helpers\Url;

?>
<footer>
    <div class="footer-body">
        <div class="footer-top relative">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <a href=""><img src="<?=Url::base();?>/assets/img/logo_footer.png" alt="logo"></a>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-8 text-right">
                        <ul class="list-inline">
                            <li>
                                <a href="https://www.facebook.com/etibb.az/"><span><i class="fa fa-facebook" aria-hidden="true"></i></span></a>
                            </li>
                            <li>
                                <a href=""><span><i class="fa fa-twitter" aria-hidden="true"></i></span></a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/etibb.az/"><span><i class="fa fa-instagram" aria-hidden="true"></i></span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <ul class="list-unstyled footer-list">
                <li><a href="<?=Yii::$app->params['site.url'].'hekimler';?>">Həkimlər</a></li>
                <li><a href="<?=Yii::$app->params['site.url'].'aksiyalar';?>">Aksiyalar</a></li>
                <li><a href="<?=Yii::$app->params['site.url'].'haqqimizda';?>">Haqqımızda</a></li>
                <?php
                    if(isset($data['menus']))
                    {
                        foreach($data['menus']['type'][2] as  $val)
                        {
                            $target = ($val['target'] == 1 )? 'target="_blank"' : '';
                            $link   = $val['link'];
                            if($val['type'] == 2){
                                $link = Yii::$app->params['site.url'].Yii::$app->params['site.enterprise_slug'].'/'.$val['link'].'-'.$val['id'];
                                ?>
                                <li>
                                    <a href="<?=$link?>"><?=$val['name']?></a>
                                </li>
                                <?php
                            }
                        }
                    }
                ?>
                <li><a href="/qaydalar">Qaydalar</a></li>
                <li><a href="/qanver">Qan ver</a></li>
                <li><a href="/elaqe">Bizimlə əlaqə</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="col-md-12 text-center">
                <h5>© <?=date("Y")?> E-tibb.az -  Bütün hüquqlar qorunur</h5>
            </div>
<!--            <div class="col-md-6 text-right">-->
<!--                <form action="">-->
<!--                    <div class="input-group">-->
<!--                        <input type="text" class="form-control" placeholder="E-mail ünvanınız" aria-describedby="basic-addon2">-->
<!--                        <button type="submit">Üzv ol</button>-->
<!--                    </div>-->
<!--                </form>-->
<!--            </div>-->
        </div>
    </div>
</footer>

<script>
    var siteUrl = '<?=Yii::$app->params['site.url']?>';
</script>