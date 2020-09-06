<?php

namespace api\modules\general\models\search;

use Yii;

/**
 *
 * @property string $datetime
 */
class ContactSearch extends \yii\db\ActiveRecord
{
    public static $tableName = 'site_contact';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_contact';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datetime'], 'safe'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'datetime' => 'Tarix'

        ];
    }

    public function searchCount($search)
    {
        $datetime = !empty($search['datetime']) ? $search['datetime'] : '----';

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `site_contact` WHERE `datetime` LIKE '%$datetime%'")->queryOne();

    }

    public function search($search,$limits)
    {
        $datetime = !empty($search['datetime']) ? $search['datetime'] : '----';

        return Yii::$app->db->createCommand("SELECT * FROM `site_contact` WHERE `datetime` LIKE '%$datetime%' ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}"  )->queryAll();

    }


}
