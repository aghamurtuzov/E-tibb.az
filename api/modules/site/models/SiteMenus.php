<?php

namespace api\modules\site\models;
 
use Yii;

/**
 * This is the model class for table "site_news".
 *
 * @property int $id
 * @property int $category_id
 * @property string $photo
 * @property string $headline
 * @property string $content
 * @property string $datetime
 * @property int $news_read
 */
class SiteMenus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_menus';
    }

    public static function getClassName()
    {
        $exp = explode('\\',__CLASS__);
        return $exp[count($exp)-1];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent','menu_order','name','type','status'],'required'],
            [['menu_order','target','position','type','status','template','hidden'],'integer'],
            [['link','name','keywords','content','description'],'string'],
            ['settings','safe']
        ];
    }

    /**
     * Get status
     */
    public static function get_Status()
    {
        return [
            0 => 'DeAktiv',
            1 => 'Aktiv'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent' => 'Əsas səhifə',
            'menu_order' => 'Sıra',
            'link' => 'Link',
            'target' => 'Target',
            'name'=>'Ad',
            'description'=>'Açıqlama',
            'keywords'=>'Açar sözlər',
            'content'=>'Mətn',
            'position'=>'Yerləşmə',
            'type'=>'Növ',
            'hidden'=>'Gizli',
            'status'=>'Status'
        ];
    }
}
