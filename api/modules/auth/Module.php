<?php

namespace api\modules\auth;

use Yii;

/**
 * Doctor module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'api\modules\auth\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
        // custom initialization code goes here
    }
}
