<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "site_promotions".
 *
 * @property int $id
 * @property string $bank
 * @property string $card_number
 * @property int $type
 * @property int $status
 */

class BankAccounts extends \yii\db\ActiveRecord
{

    public $deletedImages;

    public static function tableName()
    {
        return 'bank_accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank','balance','card_number','type','status'], 'required'],
            [['bank','card_number'], 'string'],
            [['type','status'],'integer'],
            ['balance','number']
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
     * Get types
     */
    public static function get_types()
    {
        return [
            1 => 'Visa',
            2 => 'Master'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bank' => 'Bank',
            'card_number' => 'Kart nömrəsi',
            'type' => 'Növ',
            'status' => 'Status',
            'balance' => 'Balans'
        ];
    }

}

