<?php

namespace api\controllers;

use yii;
use backend\components\Functions;

class ApiController extends yii\rest\ActiveController
{

    public function init(){
        parent::init();
    }

    public function response($status,$status_message,$data = null,$addField = null,$headers = [])
    {
        $yiiHeaders = Yii::$app->response->headers;
        Yii::$app->response->statusCode = $status;

        $response['status']=$status;
        $response['message']=$status_message;
        if(!empty($addField))
        {
            foreach($addField as $key => $val)
            {
                $response[$key] = $val;
            }
        }
        if(!empty($headers))
        {
            foreach($headers as $key => $val)
            {
                $yiiHeaders->add($key,$val);
                $yiiHeaders->set($key,$val);
            }
        }
        $response['data']=$data;
        //$json_response = json_encode($response,JSON_HEX_TAG);
        return $response;
    }

    /**
     * Merkezi funksiya
     */
    public function ResultList($temp, $customPath = null, $link = null, $gallery_path = null)
    {
        $customPath = empty($customPath) ? $this->customPath : $customPath;
        if(count($temp)>0)
        {
            $data = [];
            $rm_first_index = false;
            if(!isset($temp[0])) {
                $data[] = $temp;
                $rm_first_index = true;
            } else {
                $data = $temp;
            }
            foreach ($data as $index => $val)
            {
                if(isset($val['icon']))
                {
                    $data[$index]['icon'] = Functions::getUploadUrl().$customPath.'/'.$data[$index]['icon'];
                }
                if(isset($val['photo']))
                {
                    $photo = $data[$index]['photo'];
                    $data[$index]['photo'] = Functions::getUploadUrl().$customPath.'/'.$photo;
                    $data[$index]['thumb'] = Functions::getUploadUrl().$customPath.'/small/'.$photo;
                }
                if(isset($val['file_photo']))
                {
                    $photo = $data[$index]['file_photo'];
                    $data[$index]['file_photo'] = Functions::getUploadUrl().$customPath.'/'.$photo;
                    $data[$index]['file_photo_thumb'] = Functions::getUploadUrl().$customPath.'/small/'.$photo;
                }
                if(!empty($link))
                {
                    if($link == 'LinkNews')
                    {
                        $data[$index]['link'] = Functions::getSiteUrl().'/xeber/'.$data[$index]['slug'].'-'.$data[$index]['id'];
                    }else if($link == 'LinkPromotion'){
                        $data[$index]['link'] = Functions::getSiteUrl().'/aksiya/'.$data[$index]['slug'].'-'.$data[$index]['id'];
                    }
                }
                if(isset($val['gallery_images']) && !empty($val['gallery_images'])) {
                    foreach($val['gallery_images'] as $key => $gallery_image) {
                        $data[$index]['gallery_images'][$key]['file_photo'] = \api\components\Functions::getUploadUrl().$this->galleryPath.'/'.$gallery_image['name'];
                        $data[$index]['gallery_images'][$key]['file_photo_thumb'] = Functions::getUploadUrl().$this->galleryPath.'/small/'.$gallery_image['name'];
                    }
                }
            }

            return ($rm_first_index) ? $data[0] : $data;
        }
    }

}