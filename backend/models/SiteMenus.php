<?php

namespace backend\models;
 
use Yii;

/**
 * This is the model class for table "site_menus".
 *
 * @property int $id
 * @property int $parent
 * @property int $menu_order
 * @property string $link
 * @property int $target
 * @property string $name
 * @property string $keywords
 * @property string $content
 * @property int $position
 * @property int $type 1 - static page | 2 - enterprise cat | 3 - news cat | 4 - shop cat
 * @property int $hidden
 * @property int $status 0 - deactive 1 - active
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
    /**
     * Position types
     */
    public static function get_Position()
    {
        return [
            1 => 'Üst',
            2 => 'Alt'
        ];
    }
    /**
     *  Menu Types
     */
    public static function get_Type()
    {
        return [
            1 => 'Statik Səhifə',
            2 => 'Korporativ Səhifə',
            3 => 'Xəbər Səhifəsi',
            4 => 'Magaza Səhifəsi',
            5 => 'Xarici Keçid',
            6 => 'Modul Sehifesi'
        ];
    }

    /**
     *  Menu Types
     */
    public static function get_Status()
    {
        return [
            0 => 'Passiv',
            1 => 'Aktiv'
        ];
    }

    /** 
     *  Get Parents
     */
    public static function get_Parents($id)
    {
        $parent_menus = SiteMenus::find()->where(['id' => $id])->one();
        if(!empty($parent_menus))
        {
            return $parent_menus->name;
        }
        return 'Yoxdur';
    }

    /**
     *  Get News Category
     */

    public static function get_Last_Order()
    {
        $datas = SiteMenus::find()->orderBy('menu_order DESC')->one();
        if(!empty($datas))
        {
            return $datas->menu_order+1;
        }
        return 1;
    }
    
    public static function get_News_Category($id)
    {
        $news_category = SiteMenus::find()->where(['id' => $id,'type'=>3])->one();
        if(!empty($news_category))
        {
            return $news_category->name;
        }
        return 'Yoxdur';
    }
    
    /**
    * Get last menu order
    */


    public function rules()
    {
        return [
            [['menu_order',  'name'], 'required'],
            [['parent', 'menu_order', 'target', 'position', 'type', 'hidden', 'status'], 'integer'],
            [['content'], 'string'],
            [['link', 'keywords'], 'string', 'max' => 250],
            [['name'], 'string', 'max' => 100],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent' => 'Üst Menu',
            'menu_order' => 'Sıra',
            'link' => 'Link',
            'target' => 'Keçid',
            'name' => 'Ad',
            'keywords' => 'Açar Sözlər',
            'content' => 'Kontent',
            'position' => 'Pozisiya',
            'type' => 'Tip',
            'hidden' => 'Görünməz',
            'status' => 'Status'
        ];
    }
}
