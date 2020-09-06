<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "site_promotions".
 *
 * @property int $id
 * @property string $headline
 * @property double $price
 * @property int $discount
 * @property string $date_start
 * @property string $date_end
 * @property string $photo
 * @property string $organizer
 * @property string $content
 * @property int $connect_id
 * @property int $type 1 - Doctor | 2 - Enterprise
 */
class PromotionForm extends \yii\db\ActiveRecord
{
    public $date;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_promotions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['headline', 'date', 'content','address','photo'], 'required'],
            [['price'], 'number'],
            [['discount', 'connect_id', 'type','promo_type','max_user'], 'integer'],
//            [['date_start', 'date_end'], 'safe'],
            [['content','promo_code','address'], 'string'],
            [['phones'], 'checkIsEmpty'],
            [['headline', 'organizer'], 'string', 'max' => 250],
            [['photo'], 'string', 'max' => 60],
            [['photo'], 'file','skipOnEmpty' => false, 'extensions'=>'png,jpg','maxFiles' => 2,'wrongExtension'=>'Yalnız {extensions}'],

        ];
    }
    public function checkIsEmpty($attribute)
    {
        var_dump(empty($this->$attribute));
        if(empty($this->$attribute)){
            $this->addError($attribute,'Xəta! Massiv deyil!');
        }
    }
    public function getPromoTypes()
    {
        return [
            0 => 'Yoxdur',
            1 => 'Hamı üçün',
            2 => 'Limitli'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'İD',
            'headline' => 'Başlıq',
            'price' => 'Qiymət',
            'discount' => 'Endirim %',
            'date_start' => 'Başlanğıc Tarix',
            'date_end' => 'Bitiş Tarixi',
            'photo' => 'Şəkil yüklə',
            'phones' => 'Nömrə',
            'organizer' => 'Orqanizator',
            'content' => 'Haqqında',
            'connect_id' => 'Əlaqəçi',
            'type' => 'Növ',
            'promo_type' => 'Promo kod növü',
            'max_user' => 'Maksimum istifadəçi',
            'date' => 'Tarix',
            'address' => 'Ünvan',
        ];
    }
}
