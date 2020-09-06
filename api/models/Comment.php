<?php

namespace api\models;

use Yii;

/**
 * This is the model class for table "site_comments".
 *
 * @property int $id
 * @property int $connect_id
 * @property string $name
 * @property string $email
 * @property string $comment
 * @property string $datetime
 * @property int $positive
 * @property int $status 0 - deactive 1 - active
 * @property int $type 1 - Doctor | 2 - Enterprise | 3 - News
 */
class Comment extends \yii\db\ActiveRecord
{
    public static $tableName = 'site_comments';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['connect_id', 'name', 'email', 'comment', 'rating'], 'required', 'message' => '{attribute} xanasını boş buraxmayın'],
            [['id', 'connect_id', 'positive', 'type'], 'integer'],
            [['status'], 'integer'],
            [['comment'], 'string'],
            [['datetime'], 'safe'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 60],
            [['name'], 'string', 'min' => 3, 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'connect_id' => 'Connect ID',
            'name' => 'Ad',
            'email' => 'E-mail',
            'comment' => 'Rəyiniz',
            'datetime' => 'Datetime',
            'positive' => 'Positive',
            'status' => 'Status',
            'type' => 'Type',
            'rating' => 'Reytinq',
        ];
    }


    /**
     * Get status
     */
    public static function get_Status()
    {
        return [
            0 => 'DeAktiv',
            1 => 'Aktiv',
            2 => 'Deleted'
        ];
    }

}
