<?PHP
use yii\helpers\ArrayHelper;
use frontend\models\SiteSpecialistsModel;
use frontend\components\Specialist;
use backend\components\Functions;

$specialists = ArrayHelper::toArray(new Specialist());
$specialists = $specialists['specialists']['all'];
?>

<?PHP if(!empty($specialists)){ ?>
<div class="t-card filter">
    <div class="row">

        <div class="col-md col-12">
            <select select2 class="w-100" data-placeholder="Həkim axtarışı" id="home_select" data-icon="assets/img/icon/hekim.png">
                <option></option>
                <?PHP
                foreach($specialists as $key => $val)
                {
                    $link_slug = Functions::slugify($val['name'],['transliterate'=>true]);
                    $link   = $link_slug.'-'.$val['id'];
                    $id     = Yii::$app->request->get('id');
                    $slug   = Yii::$app->request->get('cat');
                    $class  = ($id==$val['id'] && $slug==$link_slug) ? 'selected':null;
                    echo "<option {$class} value=\"{$link}\">{$val['name']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md col-lg-4 col-12 m-top-10">
            <button id="home_search" class="cb orange orange-hover shadow w-100 inner-shadow">Axtar</button>
        </div>

    </div>
</div>
<?PHP }; ?>