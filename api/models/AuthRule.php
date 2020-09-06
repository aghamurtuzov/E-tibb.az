<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "auth_rule".
 *
 * @property string $name
 */

class AuthRule extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'auth_rule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
        ];
    }

}

