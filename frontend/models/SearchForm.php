<?php
namespace frontend\models;

use yii\base\Model;

/**
 * Search form
 */
class SearchForm extends Model
{
    public $q;

    public function rules()
    {
        return [
            ['q', 'trim'],
            ['q', 'required','message'=>'']
            //['q', 'required','message'=>'Axtardığınız ifadəni qeyd edin'],
            //['q', 'string','min'=>3,'max'=>80,'message'=>'Daxil etdiyiniz ifadə minimum 3, maksimum 80 simvol olmalıdır'],
        ];
    }

    public function attributeLabels()
    {
        return ['q'=>'Axtar'];
    }

}
