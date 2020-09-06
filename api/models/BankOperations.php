<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "site_promotions".
 *
 * @property int $account_id
 * @property string $reason
 * @property number $price
 * @property int $action
 * @property safe $datetime
 */

class BankOperations extends \yii\db\ActiveRecord
{

    public $deletedImages;

    public static function tableName()
    {
        return 'bank_account_operations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id','reason','price','action','datetime'], 'required'],
            [['reason'], 'string'],
            ['price','number'],
            ['datetime','safe'],
            [['account_id','action'],'integer']
        ];
    }

    /**
     * Get actions
     */
    public static function get_Actions()
    {
        return [
            1 => 'Mədaxil',
            2 => 'Məxaric'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Bank hesabı',
            'reason' => 'Açıqlama',
            'price' => 'Ödəniş',
            'action' => 'Təyinat',
            'datetime' => 'Tarix'
        ];
    }

}

