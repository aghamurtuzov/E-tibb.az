<?php

namespace backend\models;

use backend\components\Functions;
use Yii;

/**
 * This is the model class for table "user_promo_codes".
 *
 * @property int $id
 * @property int $user_id
 * @property int $promotion_id
 * @property string $promo_code
 * @property int $status
 */
class UserPromoCodes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_promo_codes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'promotion_id', 'promo_code', 'status'], 'required'],
            [['user_id', 'promotion_id', 'status'], 'integer'],
            [['promo_code'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'promotion_id' => 'Promotion ID',
            'promo_code' => 'Promo Code',
            'status' => 'Status',
        ];
    }

    public static function insertPromoCode($promotion,$user_id){

        $db = Yii::$app->db;
        $user_exist = $db->createCommand("Select * from user_promo_codes where user_id=:user_id and promotion_id=:promotion_id",[':user_id' => $user_id, ':promotion_id' => $promotion['id']])->queryOne();
        $code='';
        if(empty($user_exist)) {
            if ($promotion['promo_type'] === 1) {
                $code=$promotion['promo_code'];
                $data = $db->createCommand("INSERT INTO user_promo_codes (user_id,promotion_id,promo_code,status) values (:user_id,:promotion_id,:promo_code,1)", [':user_id' => $user_id, 'promotion_id' => $promotion['id'], ':promo_code' => $code])->execute();
            } elseif ($promotion['promo_type'] === 2) {
                $code = $promotion['promo_code'].Functions::promoCodeGenerator(1, $user_id);
                $data = $db->createCommand("INSERT INTO user_promo_codes (user_id,promotion_id,promo_code,status) values (:user_id,:promotion_id,:promo_code,1)", [':user_id' => $user_id, 'promotion_id' => $promotion['id'], ':promo_code' =>  $code])->execute();
            } else {
                $code = '';
            }
        }
        return $code;
    }
}
