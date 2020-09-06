<?php
/**
 * Created by PhpStorm.
 * User: Taleh F
 * Date: 1/16/2019
 * Time: 12:26 PM
 */

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\components\Functions;
use yii\helpers\ArrayHelper;
use yii\data\Pagination;
use frontend\models\MainModel;
use frontend\models\PromotionForm;
use frontend\models\PromotionModel;
use backend\models\SitePromotions;
use frontend\models\Doctor;
use frontend\models\WorkDaysModel;
use frontend\models\Enterprise;
use frontend\models\LoginForm;
use frontend\models\User;
use frontend\models\SignupForm;
use frontend\models\UserSettingsForm;
use frontend\components\Menu;

class WorkDaysController extends MainController
{

    public $menus;
    public $data        = null;
    public $layout      = 'promotion_form';
    //public $customPath  = 'promotions';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index','settings','logout'],
                'rules' => [

                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => false
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {

        $menus = ArrayHelper::toArray(new Menu());

        $this->menus = $menus['list'];

        return $this->menus;

    }

    public function userWorkDays($user_id)
    {
        if (Yii::$app->user->id==$user_id){
            $model = new WorkDaysModel();
            $total_count    = $model->getUserWorkDaysCount($user_id);
            $pagination     = new Pagination(['totalCount'=>$total_count]);
            $pagination->defaultPageSize = 6;
            $workdays_list = $model->getUserWorkDays($user_id,$pagination->limit,$pagination->offset);
            $workdays = array();
            if(!empty($workdays_list)){
                foreach ($workdays_list as $workday){
                    $workdays[$workday['id']]['id']         = $workday['id'];
                    $workdays[$workday['id']]['connect_id'] = $workday['connect_id'];
                    $workdays[$workday['id']]['time_interval'] = $workday['time_interval'];
                    $workdays[$workday['id']]['date']       = $workday['date'];
                    $workdays[$workday['id']]['workdays']   = explode(',',$workday['workdays']);
                }
                $data = ['workdays'=>$workdays, 'pagination'=>$pagination];
                return $data;
            }
            $data = false;
            return $data;
        }
    }

    public function actionIndex()
    {
        $error = 0;
        $msg   = '';
        $model = new WorkDaysModel();

        if (Yii::$app->request->isPost){
            $model->connect_id   = Yii::$app->user->id;
            $model->time_interval= intval(Yii::$app->request->post('time_interval'));
            $model->date         = date("Y-m-d",strtotime(Yii::$app->request->post('date')));
            $post_times          = Yii::$app->request->post('times');
            if(!empty($post_times))
            {
                $model->workdays = implode(',',$post_times);
            }
            else
            {
                $error=$error+1;
                $msg  = 'İş saatları seçilmədi!';
            }

            if($model->hasDate($model->connect_id, $model->date)!=0)
            {
                $msg  = 'Bu tarix üçün məlumat artıq qeyd olunub';
                $error=$error+1;
            }

            if($error==0)
            {
                if($model->save()){
                    Yii::$app->session->setFlash("success","İş saatları əlavə edildi!");
                }else{
                    Yii::$app->session->setFlash("danger","İş saatları əlavə edilmədi!");
                }
            }
            else
            {
                Yii::$app->session->setFlash("danger",$msg);
            }
        }

        if ($this->userWorkDays(Yii::$app->user->id )!= false){
            $workdays   = $this->userWorkDays(Yii::$app->user->id)['workdays'];
            $pagination = $this->userWorkDays(Yii::$app->user->id)['pagination'];
        }else{
            $workdays   = null;
            $pagination = new Pagination();
        }

        return $this->render('index',[
            'model'         => $model,
            'workdays_data' => $workdays,
            'pagination'    => $pagination,
        ]);
    }

    public function actionUpdate()
    {
        if(Yii::$app->request->isAjax){
            if (Yii::$app->request->isPost){
                $id     = intval(Yii::$app->request->post('post_id'));
                if(!empty($id)){
                    $model   = $this->findModelWorkdays($id);
                    $model->workdays = Yii::$app->request->post('workday');
                    if ($model->save()){
                        return 'Yes';
                    }else{
                        return 'No';
                    }
                }
                return 'No';
            }
            return 'No';
        }
        return 'No';
    }

    public function actionTimeExpand()
    {
        $time           = strtotime('07:00');
        $data['time']   = array();
        $request        = Yii::$app->request;
        $i = 0;
        $loop= 30;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if ($request->isPost){
            $interval = intval($request->post('get_option'));
            if($interval==30){
                $loop = 29;
            }else if($interval==45){
                $loop = 19;
            }else if($interval==60){
                $loop = 14;
            }
            while($i<$loop){
                if($interval == 30){
                    $startTime  = date("H:i", strtotime('+30 minutes', $time));
                    $time       = strtotime($startTime);
                    $endTime    = date("H:i", strtotime('+30 minutes', $time));
                }else if($interval == 45){
                    $startTime  = date("H:i", strtotime('+45 minutes', $time));
                    $time       = strtotime($startTime);
                    $endTime    = date("H:i", strtotime('+45 minutes', $time));
                }else if($interval == 60){
                    $startTime  = date("H:i", strtotime('+60 minutes', $time));
                    $time       = strtotime($startTime);
                    $endTime    = date("H:i", strtotime('+60 minutes', $time));
                }
                $data['time'][]= $startTime.'-'.$endTime;
                $time       = strtotime($startTime);
                $i++;
            }
            return $data;
        }
    }

    public function actionDeleteWorkDays()
    {
        if(Yii::$app->request->isAjax){
            $id = intval(Yii::$app->request->post('del_id'));
            if(!empty($id)){
                $model = $this->findModelWorkdays($id);
                $model->delete();
                return 'Yes';
            }
        }
    }

    protected function findModelWorkdays($id)
    {
        if (($model = WorkDaysModel::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}