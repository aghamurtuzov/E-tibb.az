<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "site_specialists".
 *
 * @property int $id
 * @property string $name
 * @property int $count
 */
class SiteSpecialists extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_specialists';
    }

    public function getClassName()
    {
        $exp = explode('\\',__CLASS__);
        return $exp[count($exp)-1];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required','message'=>'{attribute} xanasını boş buraxmayın'],
            [['count'], 'integer'],
            [['icon'], 'file', 'extensions'=>'png , jpg','wrongExtension'=>'Yalnız {extensions}'],
            [['name'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'İxtisas',
            'icon' => 'Şəkil ( png , jpg )',
            'count' => 'Həkim sayı'
        ];
    }
}
