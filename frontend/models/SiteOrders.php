<?php
namespace frontend\models;
use Yii;
/** * This is the model class for table "site_orders".

 * * @property int $id
 * @property int $user_id
 * @property int $connect_id
 * @property int $type * 0 - Istifadeci  |	1 - Doctor | 2 - Enterprise
 * @property string $products
 * @property string $create_date
 * @property string $pay_date
 * @property double $price
 * @property int $pay_method 0=>cash,1=>card
 * @property int $status
 */

class SiteOrders extends \yii\db\ActiveRecord{

    /**     * {@inheritdoc}     */

    public static function tableName()    {

        return 'site_orders';

    }
    /**     * {@inheritdoc}     */

    public function rules()    {

        return [
            [['description',  'price', 'pay_method'], 'required'],
            [['user_id', 'type', 'pay_method'], 'integer'],
            [['create_date', 'pay_date'], 'safe'],
            [['price'], 'number'],
            [['description'], 'string', 'max' => 255],
        ];

    }
    /**     * {@inheritdoc}     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'connect_id' => 'Connect ID',
            'type' => 'Type',
            'description' => 'Products',
            'create_date' => 'Create Date',
            'pay_date' => 'Pay Date',
            'price' => 'Price',
            'pay_method' => 'Pay Method',
            'status' => 'Status',
        ];
    }
}