<?PHP

namespace frontend\components;

use Yii;
use yii\base\BaseObject;
use frontend\models\MainModel;

class Menu extends BaseObject
{

    public $list;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        $cache = Yii::$app->cache;

        if(!$cache->get('menus'))
        {

            $mainModel = new MainModel();
            $menus     = $mainModel->getMenus();

            if(!empty($menus))
            {
                foreach($menus as $key => $val)
                {
                    $this->list['id'][$val['id']]       = $val;
                    $this->list['type'][$val['type']][] = $val;
                    $this->list['slug'][$val['link']][] = $val;
                    if($val['hidden'] == 0)
                    {
                        $parent = !empty($val['parent']) ? $val['parent'] : 0;
                        $this->list['position'][$val['position']][] = $val;
                        if($val['position'] == 1)
                        {
                            $this->list['parent'][$parent][] = $val;
                        }
                    }

                }
            }

            $cache->set('menus',$this->list,120);

        }
        else
        {
            $this->list = $cache->get('menus');
        }

        return $this->list;

    }

}
?>