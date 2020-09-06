<form action="" class="doc-log" method="post">
    <div class="row">
        <div class="col-12">
            <div class="t-card mini3 -h-top">
                <div class="row">
                    <div class="col col-md-6 text-left d-none d-md-block">
                        <h2 class="breadcrumb-title">Qeydiyyat</h2>
                    </div>
                    <div class="col col-md-6 text-left text-md-right">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb" style="padding:15px 0 8px;">
                                <li class="breadcrumb-item"><a href="https://www.e-tibb.az">Ana səhifə</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a class="active" href="https://www.e-tibb.az">Paketi seç</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="t-card mini3 -h-top nov radio-list">
                <h5>Paketlər</h5>
                <div class="bottom">
                    <p>Əgər Siz <a href="#">www.e-tibb.az</a> kataloqunda olmaq istəyirsinizsə, aşağıdakı seçimlərdən yararlana bilərsiniz: </p>
                    <div class="row">
                        <?php
                        $all_price = 0;
                        if($packages){
                            $i=1;
                            foreach ($packages as $package) {
                                $checked = '';
                                if($i==1){
                                    $checked = 'checked';
                                    $all_price = $package["price"];
                                }

                                ?>
                                <div class="col-md-4 col-12">
                                    <div class="form-check">
                                        <p class="count"><span><?= $package["name"] ?></span><?= $package["price"] ?>
                                            <sup>M</sup></p>
                                        <div class="center text-center">
                                            <p><span class="num"><?= $package["month"] ?></span>Aylıq</p>
                                            <span class="text"><?= $package["description"] ?></span>
                                        </div>
                                        <div class="cb cyan inputGroup d-inline-block">
                                            <input class="form-check-input select_package get_package_price" type="radio" name="package" data-price="<?= $package["price"] ?>"
                                                   id="exampleRadios<?= $i?>" value="<?= $package["id"] ?>" <?= $checked?>>
                                            <label class="form-check-label" for="exampleRadios<?= $i?>">
                                                Paketİ seç
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </div>
                    <!--<div class="col-12 border-top">
                        <p><span>Qeyd:</span> Maksimum 3 aylıq ödəniş edilə bilər</p>
                    </div>-->
                </div>
            </div>

            <div class="t-card mini3 -h-top nov list">
                <h5>VİP xİdmətlər</h5>
                <div class="bottom">
<!--                    <p>Əgər Siz <a href="#">www.e-tibb.az</a> saytında klinikalar kataloqunda olmaq istəyirsinizsə, aşağıdakı seçimlərdən yararlana bilərsiniz: </p>-->
                    <div class="row">
                        <div class="col-12" id="service_month">
                            <?php
                            if($services){
                                foreach ($services as $service){
                                    ?>
                                    <div class="col-12 group">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 col-12">
                                                <label class="checkbox control control--checkbox" title="Premium iştirak">
                                                    <input type="checkbox" value="<?= $service["id"]?>" name="services[]" class="get_package_price"> <?= $service["name"]?>
                                                    <span class="control__indicator"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-12 text-right search-box">
                                                <select class="selectpicker form-control get_package_price" tabindex="-98" name="service_month[]" id="service_month_<?=$service["id"]?>">
                                                    <?php
                                                    $prices = $service["prices"];
                                                    foreach ($prices as $key=>$price){
                                                        ?>
                                                        <option value="<?= $service["id"]."-".$key."-".$price?>" data-price="<?= $price?>"><?= $key?> aylıq - <?= $price?>AZN</option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="t-card mini3 -h-top nov list">
                <h5>Xİdmətlər haqqında</h5>
                <div class="bottom">
                    <div class="row">
                        <div class="col-12" id="service_month">
                            <strong>Qiymətlər AZN ilə müəyyənləşdirilib.</strong>
                            <br>
<!--                            <strong>Premium paket</strong>-->
                            Premium paket reklamvericinin bütün ay ərzində saytın əsas səhifəsində postunun
                            yerləşdirilməsi üçün nəzərdə tutulub.
                            <br><br>
                            Facebook, İnstagram qiymətləri ayda iki dəfə reklamverici barədə sponsorlu postun yerləşdirilməsi üçün nəzərdə tutulub.
                            <br>
<!--                            <strong>Premium paket</strong>-->
                            <br>
                            Videorolik sifarişçinin istəyindən asılı olaraq hazırlanır, montaj olunur, youtubedeki e-tibb kanalına yerləşdirilir və istəyə uyğun olaraq müştəriyə təhvil verilir.
                            <br><br>

                        </div>
                    </div>
                </div>
            </div>

            <div class="t-card mini3 -h-top pay">
                <button class="cb orange orange-hover shadow inner-shadow">ÖDƏNİŞ ET</button>
                <p>Cəmi: <span><span id="all_price"><?= $all_price?></span><sup class="jis">M</sup></span></p>
            </div>

        </div>
    </div>
</form>