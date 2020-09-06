<?php

namespace api\modules\general\models\search;

use api\modules\enterprise\models\EnterpriseDoctor;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site_doctors".
 *
 * @property string $name
 * @property string $email
 * @property string $status
 * @property string $number
 * @property string $code
 * @property string $specialist
 */
class DoctorsSearch extends \yii\db\ActiveRecord
{

    public $number;
    public $code;
    public $specialist;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_doctors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $return = [
            ['email','trim'],
            ['email','email'],
            [['status'],'string'],
            [['number'],'integer'],
            [['code'],'integer'],
            [['name'], 'string','min' => 3, 'max' => 100],
            [['specialist'],'string','min' => 5, 'max' => 100],

        ];


        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ad',
            'number' => 'Telefon nömrəsi',
            'specialist' => 'İxtisas',
            'status' => 'Status',
            'code' => 'Qeydiyyat nömrəsi',
            'email' => 'Email',
        ];
    }

    public function searchCount($search)
    {
        if (!empty($search['number'])) {
            $phone = '994' . substr($search['number'], 1);
        } else {
            $phone = '----';
        }
        if ($search['status']=='all'){

            $status = 'status <> 2';
        }
        else{
            $status = "status= ".$search['status']." AND status <> 2";
        }
        $email = !empty($search['email']) ? $search['email'] : '----';
        $name = !empty($search['name']) ? $search['name'] : '----';
        $qeydiyyat = !empty($search['code']) ? $search['code'] : '----';
        $specialist = !empty($search['specialist']) ? $search['specialist'] : '----';

        return Yii::$app->db->createCommand("SELECT DISTINCT count(`id`) AS `count` FROM `view_doctors_search` WHERE ".$status." 
                            AND  ((`name` LIKE '%$name%') OR (`email` LIKE '%$email%') OR (`qeydiyyat_id` LIKE '%$qeydiyyat%') 
                            OR (`ixtisas` LIKE '%$specialist%') OR (`number` LIKE  '%$phone%' AND  `number_type` = 1))",
            [':status' => $status])->queryOne();

    }

    public function search($search,$limits)
    {
        if (!empty($search['number'])) {
            $phone = '994' . substr($search['number'], 1);
        } else {
            $phone = '----';
        }
        if ($search['status']=='all'){

            $status = 'status <> 2';
        }
        else{
            $status = "status= ".$search['status']." AND status <> 2";
        }
        $email = !empty($search['email']) ? $search['email'] : '----';
        $name = !empty($search['name']) ? $search['name'] : '----';
        $qeydiyyat = !empty($search['code']) ? $search['code'] : '----';
        $specialist = !empty($search['specialist']) ? $search['specialist'] : '----';

        return Yii::$app->db->createCommand("SELECT DISTINCT `qeydiyyat_id` AS code ,`id`,`name`,`email`,`status`,`photo` FROM `view_doctors_search` WHERE ".$status." 
                            AND  ((`name` LIKE '%$name%') OR (`email` LIKE '%$email%') OR (`qeydiyyat_id` LIKE '%$qeydiyyat%') 
                            OR (`ixtisas` LIKE '%$specialist%') OR (`number` LIKE  '%$phone%' AND  `number_type` = 1)) ORDER BY `id` DESC LIMIT {$limits[0]},{$limits[1]}",
            [':status' => $status])->queryAll();

    }


}
