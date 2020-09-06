<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "site_services".
 *
 * @property int $id
 * @property string $name
 * @property double $price
 * @property int $type 	1 - Doctor | 2 - Enterprise
 * @property int $status 0 => deaktiv | 1=> aktiv
 */
class ServicesModel extends \yii\db\ActiveRecord
{

    public static $tableName = 'site_services';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'type', 'status'], 'required'],
            [['price'], 'number'],
            [['type', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'type' => 'Type',
            'status' => 'Status',
        ];
    }

    public static function getServices($type_id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT id,name,price,type,status FROM `".self::$tableName."` WHERE `type`=:type and status=1",[":type" => $type_id])->cache(120)->queryAll();
        $rows = false;
        foreach ($result as $row){
            $rows[$row["id"]] = $row;
        }
        return $rows;
    }
}
