<?php

namespace app\models;

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
class SiteComments extends \yii\db\ActiveRecord
{
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
            [['id', 'connect_id', 'positive', 'status', 'type'], 'integer'],
            [['comment'], 'string'],
            [['datetime'], 'safe'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 60],
            [['name'], 'string','min' => 3, 'max' => 100],
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

    public static function getAllComments($id)
    {
        $db = Yii::$app->db;
        $data = $db->createCommand("SELECT * FROM site_comments WHERE status=1 and connect_id=:id and type=3 order by id desc", [':id' => $id])->queryAll();
        return $data;
    }

    public static function getTwoComments()
    {
        $db = Yii::$app->db;
        $data = $db->createCommand("SELECT sc.*,sd.photo,sd.name,ss.name as specialist_name 
                                        FROM site_comments as 
                                        sc 
                                        left join site_doctors as sd on sd.id=sc.connect_id 
                                        left join site_doctor_specialist as sds on sds.doctor_id = sc.connect_id
                                        left join site_specialists as ss on ss.id = sds.specialist_id
                                        WHERE sc.status=1 and sc.positive=2 and sc.type=1 order by sc.id desc")->queryAll();
        return $data;
    }

    public static function getCommentsByEnterprise($userID)
    {
        return Yii::$app->db->createCommand("SELECT * FROM `site_comments` WHERE `connect_id`=:connect_id AND `type`=:type_ AND `status`<>:status ORDER BY id DESC",['type_'=>2,':connect_id'=>$userID,':status'=>2])->queryAll();
    }
}
