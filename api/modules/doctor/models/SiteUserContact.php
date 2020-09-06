<?php
namespace api\modules\doctor\models;

use Yii;

/**
 * This is the model class for table "site_addresses".
 *
 * @property int $id
 * @property int $connect_id
 * @property int $user_type
 * @property string $data
 * @property int $type
 * @property int $sub_type
 */

class SiteUserContact extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_user_contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['connect_id', 'user_type', 'data','type'], 'required'],
            [['connect_id', 'user_type','type'], 'integer'],
            [['data'], 'string', 'max' => 250],
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
            'user_type' => 'User Type',
            'data' => 'Data',
            'type' => 'Type',
            'sub_type' => 'Sub type',
        ];
    }

}

