<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MilliCardController extends Controller
{

    private $mid;
    private $secretkey;
    private $status;
    private $currency = "944"; //AZN
    private $language;
    private $test_url = "http://test.millikart.az:8513/gateway/payment/register?";
    private $pro_url  = "https://pay.millikart.az/gateway/payment/register?";
    public 	$description;
    public 	$amount;
    public 	$reference;

    public function __construct($amount, $reference, $description){
        $this->status       = Yii::$app->params['payment.production'];
        $this->mid          = Yii::$app->params['payment.mid'];
        $this->secretkey    = Yii::$app->params['payment.secret'];
        $this->language     = "az";
        $this->amount       = $amount*100;
        $this->description  = $description;
        $this->reference    = $reference;
        //589 ilqar
    }

    private function signature(){

        $data  = strlen($this->mid);
        $data .= $this->mid;
        $data .= strlen($this->amount);
        $data .= $this->amount;
        $data .= strlen($this->currency);
        $data .= $this->currency;

        if(!empty($this->description)){
            $data .= strlen($this->description);
            $data .= $this->description;
        }else{
            $data .= "0";
        }

        $data .= strlen($this->reference);
        $data .= $this->reference;
        $data .= strlen($this->language);
        $data .= $this->language;
        $data .= $this->secretkey;
        $data = md5($data);
        $data = strtoupper($data);

        return $data;

    }

    public function getURL()
    {
        $api = "mid=".$this->mid."&amount=".$this->amount."&currency=".$this->currency."&description=".$this->description."&reference=".$this->reference."&language=".$this->language."&signature=".$this->signature()."&redirect=1";
        $url = $this->status ? $this->pro_url.$api : $this->test_url.$api;
        return $url;
    }

    public function pay()
    {
        $url = $this->getURL();
        //print_r($url);
        if($url)
        {
            //$url = (array)$url;
            return $this->redirect($url);
        }
        else
        {
            throw new NotFoundHttpException(Yii::t('app','Ödəniş zamanı xəta baş verdi.Yenidən cəhd edin'));
        }
    }

}
