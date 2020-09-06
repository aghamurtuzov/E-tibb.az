<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "site_addresses".
 *
 * @property int $id
 * @property int $connect_id
 * @property string $address
 * @property int $type 1 - Doctor | 2 - Enterprise
 */
class SiteAddresses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_addresses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['connect_id', 'address', 'type'], 'required'],
            [['connect_id', 'type'], 'integer'],
            [['address'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'connect_id' => 'Connect ID',
            'address' => 'Address',
            'type' => 'Type',
        ];
    }
}
