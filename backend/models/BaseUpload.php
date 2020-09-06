<?php

namespace backend\models;

use yii\base\Model;

class BaseUpload extends Model
{
    public $files;

    public function rules()
    {
        return [
            [['files'], 'file','skipOnEmpty' => true, 'extensions'=>'png,jpg','maxFiles' => 20, 'wrongExtension'=>'Yalnız {extensions}']
        ];
    }

}
?>