<?php

namespace api\modules\general\controllers;


use api\modules\general\models\ContactApiModel;
use api\modules\general\models\Contact;
use api\modules\general\models\NewsApiModel;
use api\modules\general\models\search\ContactSearch;
use yii;
use yii\web\UploadedFile;
use api\components\Functions;
use api\components\Pagination;
use api\components\ImageUpload;
use api\models\SiteAds;
use api\models\SiteUsers;
use api\modules\general\models\AdsApiModel;
use api\modules\general\controllers\MainController;
/**
 * Ads API
 */

class ContactController extends MainController
{

    public $modelClass = '';
    public $customPath = 'ads';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * Contact info
     * https://e-tibb.az/api/general/contact/info/50
     */
    public function actionInfo()
    {
        $id = intval(Yii::$app->request->get('id'));

        if(empty($id))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        $model  = new ContactApiModel();

        /** Contact info */
        $contact = $model->Contact($id);

        if(empty($contact))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $contact['text']     = strip_tags($contact['text']);

        $contact = $this->ResultList($contact,'contact');

        return $this->response(200,"Məlumat mövcuddur",$contact);

    }

    /**
     * Contact
     * https://e-tibb.az/api/general/contact
     * id=1
     * type=1
     * page=1
     * count=5
     */
    public function actionIndex()
    {
        $id     = intval(Yii::$app->request->get('id'));
        $type   = intval(Yii::$app->request->get('type'));
        $status = SiteAds::get_Status();
        $type   = empty($type) ? 1 : $type;

        $model = new ContactApiModel();

        $totalCount = $model->ContactCount();

        if($totalCount['count'] <= 0)
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $pagination = new Pagination(['totalCount' => $totalCount]);

        $limits = $pagination->getLimits();

        $list = $model->ContactAll($limits);

        if(!empty($list))
        {
            foreach($list as $key => $val)
            {
                $list[$key]['text']     = strip_tags($list[$key]['text']);
            }
        }

        $data['list'] = $this->ResultList($list,'contact');

        $pages = $pagination->getPaginationInfo();

        $result = array_merge($pages,$data);

        return $this->response(200,"Məlumat mövcuddur",$result);
    }

    /**
     * Contact search
     * https://e-tibb.az/api/general/contact/search
     * https://e-tibb.az/api/general/contact/search?page=1&count=5
     * datetime = 01.01.2020
     */
    public function actionSearch()
    {

        Yii::$app->db->schema->refresh();
        $model = new ContactSearch();
        $search = Yii::$app->request->get();

        if ($model->load($search, '')){

            $model->validate();
            if (!empty($model->errors)) {
                return $this->response(400, 'Məlumatın axtarılması zamanı xəta baş verdi', $model->errors);
            }

            $totalCount = $model->searchCount($search);

            if($totalCount['count'] <= 0)
            {
                return $this->response(400,"Heçbir məlumat tapılmadı");
            }

            $pagination = new Pagination(['totalCount' => $totalCount]);

            $limits = $pagination->getLimits();

            $result = $model->search($search,$limits);

            $data['list'] = $result;

            $pages = $pagination->getPaginationInfo();

            $result = array_merge($pages,$data);

            return $this->response(200, "Məlumat mövcuddur", $result);
        }

    }



    /**
     * Contact info
     * https://e-tibb.az/api/general/contact/delete/
     * ids array
     */
    public function actionDel()
    {
        $ids = Yii::$app->request->post('ids');
        if(empty($ids))
        {
            return $this->response(400,"Lazımi parametr(lər) mövcud deyil");
        }

        foreach($ids as $key => $id)
        {
            $update = ContactApiModel::ContactDelete($id);
            if(empty($update))
            {
                return $this->response(400,"Məlumatın silinməsi zamanı xəta baş verdi");
            }
        }
        return $this->response(200,"Melumat(lar) silindi");
    }

    protected function findModel($id)
    {
        if(($model = SiteAds::findOne($id)) !== null){
            return $model;
        }
    }


    /**
     * Contact info
     * https://e-tibb.az/api/general/contact/mail
     * id
     * message
     */
    public function actionMail(){


        Yii::$app->db->schema->refresh();
        $id = (int) Yii::$app->request->post('id');
        $message = strip_tags(Yii::$app->request->post('message'));
        if (empty($id)) {
            return $this->response(400, "Lazımi parametr(lər) mövcud deyil");
        }
        if (empty($message)) {
            return $this->response(400, "Mesaj xanasını boş buraxmayın");
        }

        $model  = new ContactApiModel();

        /** Contact info */
        $contact = $model->Contact($id);

        if(empty($contact))
        {
            return $this->response(200,"Heçbir məlumat tapılmadı");
        }

        $message = $message . '<br><br>'. nl2br('Hörmətlə e-tibb.az komandası.');

        Yii::$app->mailer->compose()
            ->setFrom('no-reply@e-tibb.az')
            ->setTo($contact['email'])
            ->setSubject('E-tibb.az | Sorğunuzun cavabı')
            ->setHtmlBody($message)
            ->send();


        return $this->response(200, "Məlumat uğurla göndərildi");
    }

}