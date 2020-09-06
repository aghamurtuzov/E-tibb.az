<?php

namespace api\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "admin_log".
 *
 * @property int $id
 * @property string $action
 * @property string $date
 * @property int $user_id
 */
class AdminEnterpriseLog extends Model
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_enterprise_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['user_id'], 'integer'],
            [['action','ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => 'Action',
            'ip' => 'Ip',
            'created_at' => 'Date',
            'user_id' => 'User ID',
        ];
    }


    public static function write($data = [],$action = '')
    {
        $actions = array(
            'create'     => 'Create',
            'update'     => 'Update',
            'edit'     => 'Edit',
            'active'     => 'Active',
            'block'     => 'Block',
            'answer'     => 'Answer',
            'delete-one' => 'Delete',
            'all-delete'     => 'All Delete',
            'base-delete-one'      => 'Baza Delete',
            'all-base-delete'     => 'All Baza Delete',
            'delete-answer'     => 'Answer Delete',
        );

        $idText         = isset($data['id']) ? ' | Id:'.$data['id'] : '';
        $nameText       = isset($data['name']) ? $data['name'] : '';
        $actionName     = Yii::$app->controller->action->id;

        $info = $nameText.$idText;
        $module = ucfirst(Yii::$app->controller->id);

        $action = isset($actions[$actionName]) ? $actions[$actionName] : $action;

        $userId = Yii::$app->session->get('userID');

        $date = date('Y-m-d h:i:s');

        $ip = Yii::$app->getRequest()->getUserIP();


        return Yii::$app->db->createCommand("INSERT INTO `admin_enterprise_log` (`info`,`action`,`module`,`created_at`, `user_id`,`ip`) VALUES ('" . $info . "','" . $action . "','" . $module . "', '" . $date . "', '" . $userId . "','".$ip."')")->execute();

    }
}
