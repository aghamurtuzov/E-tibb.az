<?php
use backend\components\Functions;

$this->title = 'Site Ads';
$this->params['breadcrumbs'][] = $this->title;

?>
<section class="donate stocks">
    <div class="head">
        <div class="container">
            <div class="row page-arrange">
                <div class="col-xs-12 text-right">
                    <ol class="breadcrumb">
                        <li><a href="index.html">Ana səhifə </a></li>
                        <li class="active">Qanver</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-9 donate-right">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block-back">
                                    <div class="form">
                                        <div class="form-list row text-center">
                                            <select class="form-control selectpicker" name="money" title="Qan qurupu">
                                                <option value="allerqoloq-29">Allerqoloq</option>
                                                <option value="androloq-9">Androloq</option>
                                                <option value="anestezioloq-47">Anestezioloq</option>
                                                <option value="dermatoloq-23">Dermatoloq</option>
                                                <option value="dietoloq-43">Dietoloq</option>
                                            </select>
                                            <select class="form-control selectpicker" name="money" title="Şəhər">
                                                <option value="allerqoloq-29">Allerqoloq</option>
                                                <option value="androloq-9">Androloq</option>
                                                <option value="anestezioloq-47">Anestezioloq</option>
                                                <option value="dermatoloq-23">Dermatoloq</option>
                                                <option value="dietoloq-43">Dietoloq</option>
                                            </select>
                                            <select class="form-control selectpicker" name="money" title="Rayon">
                                                <option value="allerqoloq-29">Allerqoloq</option>
                                                <option value="androloq-9">Androloq</option>
                                                <option value="anestezioloq-47">Anestezioloq</option>
                                                <option value="dermatoloq-23">Dermatoloq</option>
                                                <option value="dietoloq-43">Dietoloq</option>
                                            </select>
                                            <button type="submit" class="btn btn-effect">Axtarış et</button>
                                            <button type="submit" class="btn-all">Elan yerləşdir</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="block-back">
                                    <h4 class="heading-inner">QANVER</h4>
                                </div>
                            </div>
                            <div class="block">
                                <?php
                                foreach ($data as $item) { ?>
                                    <div class="col-md-4">
                                        <a href="">
                                            <div class="block-cover">
                                                <div class="img-cover">
                                                    <img src="/assets/img/donate1.png" class="img-responsive center-block" alt="donate1">
                                                </div>
                                                <div class="information text-center">
                                                    <p class="info-name"><?=$item['title']?></p>
                                                    <p class="info-major"><?=substr($item['text'],0,50)?></p>
                                                    <span class="date-gray"><i class="fa fa-calendar" aria-hidden="true"></i> <?= Functions::getDatetime($item['created_at'], ['type' => 'date']) ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php }
                                ?>
                            </div>
                            <div class="col-xs-12 text-center">
                                <nav aria-label="Page navigation">
                                    <?= \yii\widgets\LinkPager::widget([
                                        'pagination' => $pages,
                                        'maxButtonCount' => 7,
                                        'firstPageLabel' => false,
                                        'lastPageLabel' => false,
                                        'prevPageLabel' => '<span aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i> Əvvəl</span>',
                                        'nextPageLabel' => '<span aria-hidden="true">Sonra<i class="fa fa-angle-right" aria-hidden="true"></i></span>',

                                    ]); ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="doctor-about block-back map-part">
                            <div class="about-top">
                                <div class="col-md-12">
                                    <h5>Xəritədə göstər</h5>
                                    <a href="" class="btn btn-all right-item">
                                        Online
                                    </a>
                                    <div class="map">
                                        <iframe width="100%" height="170" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d319.53452916962686!2d49.8293159739942!3d40.365812947941436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307dc96ca1a6b1%3A0x3729c8228d2fdf4!2zMTEzIExlcm1vbnRvdiBTdHJlZXQsIEJha8SxLCDQkNC30LXRgNCx0LDQudC00LbQsNC9!5e0!3m2!1sru!2s!4v1550040327286" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
                                            <a href="https://www.maps.ie/map-my-route/">Create route map</a>
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="right-sidebar-images">
                            <a href="">
                                <img src="/assets/img/Turkecare.png" alt="Turkecare">
                            </a>
                        </div>
                        <div class="right-sidebar-images">
                            <a href="">
                                <img src="/assets/img/Veterinar.png" alt="Veterinar">
                            </a>
                        </div>
                        <div class="right-sidebar-images">
                            <a href="">
                                <img src="/assets/img/Aptekler.png" alt="Aptekler">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>