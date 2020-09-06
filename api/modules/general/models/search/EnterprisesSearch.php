<?php

namespace api\modules\general\models\search;

use Yii;

/**
 * This is the model class for table "site_enterprises".
 *
 * @property string $name
 * @property string $status
 * @property int $code
 * @property int $category_id
 * @property int $phone
 */
class EnterprisesSearch extends \yii\db\ActiveRecord
{

    public $phone;
    public $code;


    /**
     * {@inheritdoc}
     */

    public static function tableName()

    {

        return 'site_enterprises';

    }

    public function rules()

    {

        $return = [

            [['status'], 'string'],

            [['category_id', 'code'], 'integer'],

            [['phone'], 'integer'],

            [['name'], 'string', 'min' => 3, 'max' => 200],

        ];

        return $return;

    }


    /**
     * {@inheritdoc}
     */

    public function attributeLabels()

    {

        return [

            'id' => 'ID',

            'category_id' => 'Kateqoriya',

            'name' => 'Ad',

            'phone' => 'Telefon nömrələsi',

            'status' => 'Status',

        ];

    }

    public function searchCount($search)
    {
        if (!empty($search['phone'])) {
            $phone = '994' . substr($search['phone'], 1);
        } else {
            $phone = '----';
        }
        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        $name = !empty($search['name']) ? $search['name'] : '-----';
        $code = !empty($search['code']) ? $search['code'] : '-----';
        $category_id = !empty($search['category_id']) ? $search['category_id'] : '-----';


        return Yii::$app->db->createCommand("SELECT DISTINCT count(`id`) AS `count` FROM `view_enterprise_search` WHERE " . $status . " AND `category_id` = :category_id AND ((`user_id` LIKE '%$code%') OR (`name` LIKE '%$name%') OR (`user_phone_number` LIKE '%$phone%'))", [':category_id' => $category_id])->queryOne();

    }


    public function search($search, $limits)
    {
        if (!empty($search['phone'])) {
            $phone = '994' . substr($search['phone'], 1);
        } else {
            $phone = '----';
        }
        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }

        $name = !empty($search['name']) ? $search['name'] : '-----';
        $code = !empty($search['code']) ? $search['code'] : '-----';
        $category_id = !empty($search['category_id']) ? $search['category_id'] : '-----';


        return Yii::$app->db->createCommand("SELECT DISTINCT `id`,`photo`,`name`,`user_id`,`user_name`,`user_email`,`user_phone_number`,`status` FROM `view_enterprise_search` WHERE " . $status . " AND `category_id` = :category_id AND ((`user_id` LIKE '%$code%') OR (`name` LIKE '%$name%') OR (`user_phone_number` LIKE '%$phone%')) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", [':category_id' => $category_id])->queryAll();

    }
}
