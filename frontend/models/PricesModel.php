<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "site_prices".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property double $price
 * @property int $month_count
 * @property int $connect_id
 * @property int $type 1 - Doctor | 2 - Enterprise	
 * @property int $status 0 => deaktiv | 1=> aktiv	
 */
class PricesModel extends \yii\db\ActiveRecord
{
    public static $tableName = 'site_prices';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_prices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status'], 'required'],
            [['id', 'month_count', 'connect_id', 'type', 'status'], 'integer'],
            [['price'], 'number'],
            [['name', 'description'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'price' => 'Price',
            'month_count' => 'Month Count',
            'connect_id' => 'Connect ID',
            'type' => 'Type',
            'status' => 'Status',
        ];
    }


    public static function getPrices($connect_id,$type_id)
    {
        $db = Yii::$app->db;
        $result = $db->createCommand("SELECT id,name,description,month_count,price,type,status FROM `".self::$tableName."` WHERE connect_id=:connect_id and `type`=:type and status=1",[":connect_id" => $connect_id,":type" => $type_id])->cache(120)->queryAll();
        if($result==false){
            $result = $db->createCommand("SELECT id,name,description,month_count,price,type,status FROM `".self::$tableName."` WHERE connect_id=:connect_id and `type`=:type and status=1",[":connect_id" => 0,":type" => $type_id])->cache(120)->queryAll();
        }
        $rows = false;
        foreach ($result as $row){
            $rows[$row["id"]] = $row;
        }
        return $rows;
    }

}
