<?PHP
use frontend\models\SitePhoneNumbersModel;
use frontend\models\SiteAdressesModel;
use frontend\models\SiteSocialLinksModel;
use frontend\models\SiteDoctorsModel;

$doctor = $data['doctor'];

$workplaces   = SiteDoctorsModel::getWorkplaces($doctor['id']);
$phones       = SitePhoneNumbersModel::getPhones($doctor["id"],1);
$socialsLinks = SiteSocialLinksModel::getSocialLinks($doctor["id"],1);

$phones_array = [];
foreach ($phones as $phone)
{
    $phones_array[$phone["number_type"]][] = $phone["number"];
}

$socials = [];
foreach ($socialsLinks as $social)
{
    $sosialLinkType = ['facebook','instagram','youtube','twitter','linkedin'];
    $socials[$sosialLinkType[$social["link_type"]]] = $social["link"];
}

?>
<div class="t-tab-content">
    <div class="row">
        <div class="col-md-4">
            <label class="contact-label">Telefon</label>
            <?PHP
            if(isset($phones_array[SitePhoneNumbersModel::$TYPE_PHONE]))
            {
                foreach ($phones_array[SitePhoneNumbersModel::$TYPE_PHONE] as $phone)
                {
                    echo "<a href=\"tel:{$phone}\" class=\"link no-decoration\"><img src=\"assets/img/icon/phone.png\" alt=\"phone icon\">{$phone}</a>";
                }
            }
            ?>
        </div>
        <div class="col-md-4">
            <label class="contact-label">Mobİl</label>
            <?PHP
            if(isset($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE]))
            {
                foreach ($phones_array[SitePhoneNumbersModel::$TYPE_MOBILE] as $phone)
                {
                    echo "<a href=\"tel:{$phone}\" class=\"link no-decoration\"><img src=\"assets/img/icon/mobile.png\" alt=\"mobile icon\">{$phone}</a>";
                }
            }
            if(isset($phones_array[SitePhoneNumbersModel::$TYPE_WP]))
            {
                foreach ($phones_array[SitePhoneNumbersModel::$TYPE_WP] as $phone)
                {
                    echo "<a href=\"https://api.whatsapp.com/send?phone={$phone}\" class=\"link no-decoration\"><img src=\"assets/img/icon/wp.png\" alt=\"wp icon\">{$phone}</a>";
                }
            }
            ?>
        </div>
        <div class="col-md">
            <label class="contact-label">Sosİal</label>
            <div class="social-block">
                <?PHP

                if(isset($socials["facebook"]))
                {
                    echo "<a target=\"_blank\" href=\"{$socials["facebook"]}\"><img src=\"assets/img/icon/fb.png\" alt=\"facebook\"></a>";
                }

                if(isset($socials["instagram"]))
                {
                    echo "<a target=\"_blank\" href=\"{$socials["instagram"]}\"><img src=\"assets/img/icon/insta.png\" alt=\"instagram\"></a>";
                }

                if(isset($socials["youtube"]))
                {
                    echo "<a target=\"_blank\" href=\"{$socials["youtube"]}\"><img src=\"assets/img/icon/gp.png\" alt=\"google plus \"></a>";
                }

                if(isset($socials["twitter"]))
                {
                    echo "<a target=\"_blank\" href=\"{$socials["twitter"]}\"><img src=\"assets/img/icon/tw.png\" alt=\"twitter\"></a>";
                }

                if(isset($socials["linkedin"]))
                {
                    echo "<a target=\"_blank\" href=\"{$socials["linkedin"]}\"><img src=\"assets/img/icon/in.png\" alt=\"linkedin\"></a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div class="row -h-top-2">
        <div class="col-md-12">
            <?PHP
            if(isset($workplaces) and !empty($workplaces))
            {
                echo '<label class="contact-label">İş yerİnİn ünvanı</label>';
                foreach($workplaces as $key => $val)
                {
                    echo "<p><img src=\"assets/img/icon/location.png\"> {$val['name']}</p>";
                }
            }
            ?>
        </div>
    </div>

</div>
