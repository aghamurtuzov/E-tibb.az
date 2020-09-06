<?php
/**
 * Created by PhpStorm.
 * User: JAVANSHIR
 * Date: 3/4/2020
 * Time: 10:35
 */

namespace api\models;

class Jcode extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'jcode_token';
    }

    public function rules()
    {
        return [
            ['token', 'required'],
            ['token','integer'],
        ];
    }
}