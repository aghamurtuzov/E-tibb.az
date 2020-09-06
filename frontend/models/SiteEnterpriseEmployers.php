<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
/**
 * This is the model class for table "site_enterprise_employers".
 *
 * @property int $id
 * @property int $connect_id
 * @property string $specialty
 * @property int $study
 * @property string $photo
 * @property int $status
 */
class SiteEnterpriseEmployers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_enterprise_employers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'experience','specialty','study'], 'required','message'=>'{attribute} xanasını boş buraxmayın'],
            [['connect_id', 'study' , 'status'], 'integer'],
            [['name', 'specialty', 'photo'], 'string', 'max' => 255],
        ];
    }

    public static function get_Study()
    {
        return [
            0 => 'Natamam ali',
            1 => 'Ali',
            2 => 'Tibb üzrə fəlsəfə doktoru',
            3 => 'Elmlər doktoru'
        ];
    }

    public function getEmployers($employe_id,$limit,$offset)
    {
        $result = $this->db->createCommand("SELECT * FROM site_enterprise_employers WHERE `connect_id`=$employe_id and `status`= 1 ORDER BY `id` DESC LIMIT $limit OFFSET $offset")->queryAll();
        return $result;
    }

    public function getEmployersCount($employe_id)
    {
        $result = $this->db->createCommand("SELECT count(`id`) as `counter` FROM site_enterprise_employers  WHERE `connect_id`=$employe_id and `status`= 1 ")->queryScalar();
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'connect_id' => 'Connect ID',
            'name' => 'Ad,Soyad',
            'specialty' => 'İxtisas',
            'experience' => 'İş təcrübəsi',
            'study' => 'Elmi dərəcə',
            'photo' => 'Photo',
            'status' => 'Status',
        ];
    }
}
