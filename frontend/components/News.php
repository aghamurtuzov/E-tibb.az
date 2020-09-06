<?PHP

namespace frontend\components;

use Yii;
use yii\base\BaseObject;
use frontend\models\MainModel;

class News extends BaseObject
{

    public $newsList;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        $cache = Yii::$app->cache;
        $home_NewsCategoryAllow = [34,35,36,37];
        $home_HeaderNewsLimit   = 10;

        $mainModel = new MainModel();
        $news      = $mainModel->getNews();

        if(!empty($news))
        {
            foreach($news as $key => $val)
            {
                if(in_array($val['category_id'],$home_NewsCategoryAllow))
                {
                    $this->newsList['categ'][$val['category_id']][] = $val;
                }

                if($key<$home_HeaderNewsLimit)
                {
                    $this->newsList['list'][] = $val;
                }

                if($val['category_id'] != 34 and $val['category_id'] != 35)
                {
                    $this->newsList['xeberler'][] = $val;
                }
            }
        }

        return $this->newsList;

    }

}
?>