<?PHP

namespace frontend\components;

use Yii;
use yii\base\BaseObject;
use frontend\models\SiteSpecialistsModel;

class Specialist extends BaseObject
{

    public $specialists;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        $cache = Yii::$app->cache;

        if(!$cache->get('specialists_list'))
        {
            $spcList = SiteSpecialistsModel::getSpecialists();

            if(!empty($spcList))
            {
                foreach($spcList as $key => $val)
                {
                    $this->specialists['all'][] = $val;
                    $this->specialists['id'][$val['id']] = $val;
                }
            }

            $cache->set('specialists_list',$this->specialists,60);
        }
        else
        {
            $this->specialists = $cache->get('specialists_list');
        }

        return $this->specialists;
    }

}
?>