<?PHP
use frontend\models\MainModel;
use frontend\components\News;
use yii\helpers\ArrayHelper;
use backend\components\Functions;
use frontend\models\SiteSpecialistsModel;
use frontend\components\Specialist;

$news        = ArrayHelper::toArray(new News());
$news        = $news['newsList']['list'];
//print_r($news);

$specialists = ArrayHelper::toArray(new Specialist());
$specialists = $specialists['specialists']['all'];

?>
<div class="container-fluid -t-slide">
    <div class="container">
        <div class="row">
            <div class="col-md d-none d-lg-block">
                <?= $this->render("nav",['data' => $data])?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-1 d-none d-lg-block"></div>
            <div class="col-lg-10 col-12 text-center -h-top">
                <h1 class="main-text d-none d-md-block">Tİbbİ xİdmət və həkİm axtarış mərkəzİ</h1>
                <h2 class="sub-text d-none d-md-block">Axtardığınız həkimi tapıb qəbula yazılın</h2>
                <div class="row">
                    <div class="col-md-1 d-none d-md-block"></div>
                    <div class="col-md-12 col-12 col-sm-12 padding-clear-m">
                        <div class="t-card filter">
                            <div class="row">

                                <div class="col-md col-12">
                                    <?PHP
                                    if(!empty($specialists))
                                    {
                                        echo '<select select2 class="w-100" data-placeholder="Həkim axtarışı" data-icon="assets/img/icon/hekim.png" id="home_select">';
                                        echo '<option></option>';
                                        foreach($specialists as $key => $val)
                                        {
                                            $link = $val['slug'].'-'.$val['id'];

                                            echo "<option value=\"{$link}\">{$val['name']}</option>";
                                        }
                                        echo '</select>';
                                    }
                                    ?>
                                </div>

                                <div class="col-md col-lg-4 col-12 m-top-10">
                                    <button class="cb orange orange-hover shadow w-100 inner-shadow" id="home_search">Axtar</button>
                                </div>

                            </div>
                        </div>
                        <?PHP
                        if(!empty($news))
                        {
                            echo '<div class="marquee d-none d-md-block">';
                            foreach($news as $key => $val)
                            {
                                $link  = yii::$app->params['site.post_uri'].$val['slug'].'-'.$val['id'];
                                echo "<a href=\"$link\">{$val['headline']}</a>";
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-1 d-none d-lg-block"></div>
        </div>
    </div>
</div>