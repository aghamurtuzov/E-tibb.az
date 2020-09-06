<?phpnamespace backend\models;use Yii;/** * This is the model class for table "view_doctor_workplaces". * * @property int $doctor_id * @property int $id * @property int $category_id * @property string $name * @property string $photo * @property string $expires * @property int $promotion * @property int $feature 1 - Catdirilma | 2 - 24 saat | 3 - Caqiris * @property string $data Json * @property string $about * @property string $services_prices */class ViewDoctorWorkplaces extends \yii\db\ActiveRecord{    /**     * {@inheritdoc}     */    public static function tableName()    {        return 'view_doctor_workplaces';    }    /**     * {@inheritdoc}     */    public function rules()    {        return [            [['doctor_id'], 'required'],            [['doctor_id', 'id', 'category_id', 'promotion', 'feature'], 'integer'],            [['expires'], 'safe'],            [['data', 'about', 'services_prices'], 'string'],            [['name'], 'string', 'max' => 200],            [['photo'], 'string', 'max' => 60],        ];    }    /**     * {@inheritdoc}     */    public function attributeLabels()    {        return [            'doctor_id' => 'Doctor ID',            'id' => 'ID',            'category_id' => 'Category ID',            'name' => 'Name',            'photo' => 'Photo',            'expires' => 'Expires',            'promotion' => 'Promotion',            'feature' => 'Feature',            'data' => 'Data',            'about' => 'About',            'services_prices' => 'Services Prices',        ];    }}