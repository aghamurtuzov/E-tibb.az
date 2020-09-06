<?php
namespace api\modules\enterprise\models;

use Yii;
use api\models\SiteDoctors;

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

class EnterpriseDoctor extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_enterprise_doctor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['enterprise_id','doctor_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'enterprise_id' => 'Enterprise ID',
            'doctor_id' => 'Doctor Type',
        ];
    }

}

