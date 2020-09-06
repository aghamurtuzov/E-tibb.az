<?php
use \frontend\models\SitePhoneNumbersModel;
use \frontend\models\SiteAdressesModel;

$addresses = SiteAdressesModel::getAddresses($enterprise["id"],2);
$phones = SitePhoneNumbersModel::getPhones($enterprise["id"],2);
$socialsLinks = \frontend\models\SiteSocialLinksModel::getSocialLinks($enterprise["id"],2);
$phones_array = [];
foreach ($phones as $phone){
    $phones_array[$phone["number_type"]][] = $phone["number"];
}
$socials = [];
foreach ($socialsLinks as $social)
{
    $sosialLinkType = ['facebook','instagram','youtube','twitter','linkedin'];
    $socials[$sosialLinkType[$social["link_type"]]] = $social["link"];
}
?>
<!---------contact tab------------>
<div class="t-tab-content">
    <div class="row">
        <div class="col-md-4">
            <label class="contact-label">Telefon</label>
            <?php
                if(isset($phones_array[SitePhoneNumbersModel::$TYPE_PHONE])){
                    foreach ($phones_array[SitePhoneNumbersModel::$TYPE_PHONE] as $phone){
                ?>
                        <a href="tel:<?= $phone?>" class="link no-decoration"><img src="assets/img/icon/phone.png" alt="telefon"> <?= $phone?></a>
                        <?php
                    }
                }
            ?>
        </div>
        <div class="col-md-4">
            <label class="contact-label">Mobİl</label>
            <?php
            if(isset($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE])){
                foreach ($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE] as $phone){
                    ?>
                    <a href="tel:<?= $phone?>" class="link no-decoration"><img src="assets/img/icon/mobile.png" alt="mobil telefon"> <?= $phone?></a>
                    <?php
                }
            }
            ?><?php
            if(isset($phones_array[SitePhoneNumbersModel::$TYPE_WP])){
                foreach ($phones_array[SitePhoneNumbersModel::$TYPE_WP] as $phone){
                    ?>
                    <a href="https://api.whatsapp.com/send?phone=<?=$phone?>" class="link no-decoration"><img src="assets/img/icon/wp.png" alt="whatsapp nomre"> <?= $phone?></a>
                    <?php
                }
            }
            ?>
        </div>
        <div class="col-md">
            <label class="contact-label">Sosİal</label>
            <div class="social-block">

                <?php if(isset($socials["facebook"])){ ?>
                    <a target="_blank" href="<?= $socials["facebook"]?>">
                        <img src="assets/img/icon/fb.png" alt="facebook <?= $enterprise["name"]?>">
                    </a>
                <?php } ?>

                <?php if(isset($socials["instagram"])){ ?>
                    <a target="_blank" href="<?= $socials["instagram"]?>">
                        <img src="assets/img/icon/insta.png" alt="instagram <?= $enterprise["name"]?>">
                    </a>
                <?php } ?>

                <?php if(isset($socials["youtube"])){ ?>
                    <a target="_blank" href="<?= $socials["youtube"]?>">
                        <img src="assets/img/icon/gp.png"  alt="google plus <?= $enterprise["name"]?>">
                    </a>
                <?php } ?>


                <?php if(isset($socials["twitter"])){ ?>
                    <a target="_blank" href="<?= $socials["twitter"]?>">
                        <img src="assets/img/icon/tw.png" alt="twitter <?= $enterprise["name"]?>">
                    </a>
                <?php } ?>

                <?php if(isset($socials["linkedin"])){ ?>
                    <a target="_blank" href="<?= $socials["linkedin"]?>">
                        <img src="assets/img/icon/in.png" alt="linkedin <?= $enterprise["name"]?>">
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="row -h-top-2">
        <div class="col-md-12">
            <label class="contact-label">Ünvan</label>
            <?php
                foreach ($addresses as $address){
                    ?>
                    <p><img src="assets/img/icon/location.png" alt="unvan <?= $enterprise["name"]?>"> <?= $address["address"]?></p>
                    <?php
                }
            ?>
        </div>
    </div>

  <!--  <div class="row -h-top-2">
        <div class="col-md-12">
            <iframe class="map-frame" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12155.893067084138!2d49.87453016977538!3d40.38728510000001!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40307d40a035a6bd%3A0xa8c2cbf267a83fbd!2sHeydar+Aliyev+Centre!5e0!3m2!1sen!2s!4v1539126391661" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
    </div>-->
</div>
<!---------contact tab------------>