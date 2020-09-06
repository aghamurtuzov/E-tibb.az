<?php

namespace api\modules\doctor\models\search;

use Yii;

/**
 * This is the model class for table "site_promotions".
 *
 * @property string $date_start
 * @property string $date_end
 * @property string $fullname
 * @property string $email
 * @property int $phone
 * @property int $status
 */
class AppointmentSearch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $fullname;
    public $phone;
    public $date_start;
    public $date_end;

    public static function tableName()

    {

        return 'site_appoint';

    }

    /**
     * {@inheritdoc}
     */

    public function rules()

    {

        return [

            ['email','trim'],

            ['email','email'],

            [['status'], 'string'],

            [['fullname'], 'string', 'min' => 3, 'max' => 60],

            [['phone'], 'integer', 'min' => 10, 'max' => 10],

            [['date_start', 'date_end'], 'safe'],

        ];

    }


    /**
     * {@inheritdoc}
     */

    public function attributeLabels()

    {

        return [

            'date_start' => 'Başlanğıc Tarix',

            'date_end' => 'Son Tarix',

            'fullname' => 'Ad Soyad',

            'email' => 'Email',

            'phone' => 'Telefon',

            'status' => 'Status',

        ];

    }

    public function searchCount($search)
    {

        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }
        if (!empty($search['phone'])) {
            $phone = '994' . substr($search['phone'], 1);
        } else {
            $phone = '----';
        }

        $date_start = !empty($search['date_start']) ? $search['date_start'] : '----';
        $date_end = !empty($search['date_end']) ? $search['date_end'] : '----';
        $fullname = !empty($search['fullname']) ? $search['fullname'] : '----';
        $email = !empty($search['email']) ? $search['email'] : '----';
        $doctor_id = Yii::$app->session->get('userID');

        return Yii::$app->db->createCommand("SELECT count(`id`) AS `count` FROM `view_doctor_appoint_search` WHERE " . $status . " AND `doctor_id`=:doctor_id AND ((`fullname` LIKE '%$fullname%') OR (`email` LIKE '%$email%') OR (`telefon` LIKE '%$phone%') OR (`date` BETWEEN CAST('$date_start' AS DATE) AND CAST('$date_end' AS DATE)))", [':doctor_id' => $doctor_id])->queryOne();

    }


    public function search($search, $limits)
    {

        if ($search['status'] == 'all') {

            $status = ' status <> 2';
        } else {
            $status = "status= " . $search['status'] . " AND status <> 2";
        }
        if (!empty($search['phone'])) {
            $phone = '994' . substr($search['phone'], 1);
        } else {
            $phone = '----';
        }

        $date_start = !empty($search['date_start']) ? $search['date_start'] : '----';
        $date_end = !empty($search['date_end']) ? $search['date_end'] : '----';
        $fullname = !empty($search['fullname']) ? $search['fullname'] : '----';
        $email = !empty($search['email']) ? $search['email'] : '----';
        $doctor_id = Yii::$app->session->get('userID');


        return Yii::$app->db->createCommand("SELECT * FROM `view_doctor_appoint_search` WHERE " . $status . " AND `doctor_id`=:doctor_id AND ((`fullname` LIKE '%$fullname%') OR (`email` LIKE '%$email%') OR (`telefon` LIKE '%$phone%') OR (`date` BETWEEN CAST('$date_start' AS DATE) AND CAST('$date_end' AS DATE))) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}", [':doctor_id' => $doctor_id])->queryAll();

    }


}
