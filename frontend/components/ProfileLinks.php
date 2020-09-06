<?PHP

namespace frontend\components;

use common\models\SiteEnterprises;
use frontend\models\Doctor;
use frontend\models\SiteDoctorsModel;
use frontend\models\User;
use Yii;
use yii\base\BaseObject;
use frontend\models\MainModel;

class ProfileLinks extends BaseObject
{

    public $list;
    public $model;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();
        $cache = Yii::$app->cache;
        $pages = [];
        if(Yii::$app->user->isGuest){
            $this->list = [];
        }else{
            $user_id = Yii::$app->user->id;
            $menus = $cache->get('menus');
            if(Yii::$app->user->identity->type == User::TYPE_DOCTOR){

                $settings = $menus["id"][4]["settings"];

                $model = Doctor::findOne(['user_id' => $user_id]);

                if($model){ $this->model = $model; }

                if(empty($model))
                {
                    Yii::$app->user->logout();
                    return Yii::$app->getResponse()->redirect(Yii::$app->params["site.url"]);
                }

                $settings = json_decode($settings,true);
                $specialist = SiteDoctorsModel::getDoctorSpecialist($model->id);

                if(!empty($specialist))
                {
                    if(!empty($specialist[0]['settings']))
                    {
                        $settings = json_decode($specialist[0]['settings'],true);
                    }
                }

                // Default settings

                if(isset($settings["profile"])){
                    $pages = $settings["profile"];
                }

            }elseif(Yii::$app->user->identity->type == User::TYPE_ENTERPRISE){
                $model = SiteEnterprises::findOne(['user_id' => $user_id]);
                $this->model = $model;
                if($model){
                    // Default settings
                    $settings = $menus["id"][5]["settings"];
                    if(isset($menus["id"][$model->category_id])){
                        $category = $menus["id"][$model->category_id];
                        if($category["settings"]!=""){
                            $settings = $category["settings"];
                        }
                    }

                    $settings = json_decode($settings,true);
                    if(isset($settings["profile"])){
                        $pages = $settings["profile"];
                    }
                }


            }
        }
        $this->list = $pages;
        return $this->list;

    /*    if(!$cache->get('menus'))
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

        $this->list = $cache->get('menus');*/

        //return $this->list;

    }

}
?>