<?php

/**

 * Created by PhpStorm.

 * User: JAVANSHIR

 * Date: 10/11/2018

 * Time: 14:31

 */



namespace api\components;



use Yii;



class ImageUpload

{

 

    public function validate($file,$allowMimeTypes = ["image/gif", "image/png", "image/jpeg", "image/jpg"])
    {
        if(!empty($file) && $file->error == 0)
        {
            if(in_array($file->type,$allowMimeTypes))
            {
                return true;
            }
        }
    }



    public function saveFile($file,$options = [])

    {



        $photo = null;



        $allowMimeTypes = isset($options['allow.MimeTypes']) ? $options['allow.MimeTypes'] : ["image/gif", "image/png", "image/jpeg", "image/jpg"];



        if($this->validate($file,$allowMimeTypes))

        {



            //$rootPath = realpath(Yii::$app->basePath).'/../upload/';



            $rootPath = isset($options['path.root']) ? realpath(Yii::$app->basePath).'/../'.$options['path.root'].'/' : realpath(Yii::$app->basePath).'/../upload/';



            if(!is_dir($rootPath)){ mkdir($rootPath); }



            $savePath   = isset($options['path.save']) ? trim($options['path.save'],'/') : null;



            if(!empty($savePath))

            {

                if(!is_dir($rootPath.'/'.$savePath))

                {

                    $folders = '';

                    $paths = explode('/',$savePath);

                    foreach($paths as $item)

                    {

                        $folders .= $item.'/';

                        if(!is_dir($rootPath.'/'.$folders))

                        {

                            mkdir($rootPath.'/'.$folders);

                        }

                    }

                }

            }



            $fullPath      = $rootPath.'/'.$savePath.'/';

            $fullThumbPath = $fullPath.'small/';



            if(strpos($file->name,'.'))

            {

                $ext       = explode('.',$file->name);

                $photoExt  = $ext[count($ext)-1];

                $photo     = rand(1,4948375).'.'.$photoExt;

            }



            if($file->saveAs($fullPath.$photo))

            {



                if(isset($options['resize.img']))

                {

                    $master     = isset($options['resize.img'][2]) ? $options['resize.img'][2] : null;

                    $loadedFile = Yii::$app->image->load($fullPath.$photo);



                    $options['resize.img'][1] = isset($options['resize.img'][1]) ? $options['resize.img'][1] : null;

                    $loadedFile->resize($options['resize.img'][0],$options['resize.img'][1],$master)->save($fullPath.$photo,100);

                }



                if(isset($options['resize.thumb']))

                {

                    if(!is_dir($fullThumbPath)){ mkdir($fullThumbPath); }



                    $master     = isset($options['resize.thumb'][2]) ? $options['resize.thumb'][2] : null;

                    $loadedFile = Yii::$app->image->load($fullPath.$photo);



                    $options['resize.thumb'][1] = isset($options['resize.thumb'][1]) ? $options['resize.thumb'][1] : null;

                    $loadedFile->resize($options['resize.thumb'][0],$options['resize.thumb'][1],$master)->save($fullThumbPath.$photo,100);

                }



                return $photo;



            }

        }

    }



    public function clonFile($file,$options = [])

    {

        $photo = null;

        //print_r($options); die();

        $rootPath = isset($options['path.root']) ? realpath(Yii::$app->basePath).'/../'.$options['path.root'].'/' : realpath(Yii::$app->basePath).'/../upload/';



        if(!is_dir($rootPath)){ mkdir($rootPath); }

        

        $savePath    = isset($options['path.save']) ? trim($options['path.save'],'/') : null;

        $clone_name  = isset($options['clon']) ? trim($options['clon']) : null;

        $clon_path   = isset($options['clon_path']) ? trim($options['clon_path']) : null;

        $cloned_name = $clon_path.$clone_name;

        

        if(!empty($savePath))

        {

            if(!is_dir($rootPath.'/'.$savePath))

            {

                $folders = '';

                $paths = explode('/',$savePath);

                foreach($paths as $item)

                {

                    $folders .= $item.'/';

                    if(!is_dir($rootPath.'/'.$folders))

                    {

                        mkdir($rootPath.'/'.$folders);

                    }

                }

            }

        }



        $fullPath      = $rootPath.'/'.$savePath.'/';

        $fullThumbPath = $fullPath.'small/';

        //echo $cloned_name."-->".$fullPath.pathinfo($cloned_name, PATHINFO_BASENAME); die();

        if($this->clone_file($cloned_name,$fullPath))

        {

            if(isset($options['resize.img']))

            {

                $master     = isset($options['resize.img'][2]) ? $options['resize.img'][2] : null;

                $loadedFile = Yii::$app->image->load($fullPath.$clone_name);



                $options['resize.img'][1] = isset($options['resize.img'][1]) ? $options['resize.img'][1] : null;

                $loadedFile->resize($options['resize.img'][0],$options['resize.img'][1],$master)->save($fullPath.$clone_name,100);

            }

            if(isset($options['resize.thumb']))

            {

                if(!is_dir($fullThumbPath)){ mkdir($fullThumbPath); }

                $master     = isset($options['resize.thumb'][2]) ? $options['resize.thumb'][2] : null;

                $loadedFile = Yii::$app->image->load($fullPath.$clone_name);



                $options['resize.thumb'][1] = isset($options['resize.thumb'][1]) ? $options['resize.thumb'][1] : null;

                $loadedFile->resize($options['resize.thumb'][0],$options['resize.thumb'][1],$master)->save($fullThumbPath.$clone_name,100);

            }

            return $clone_name;

        }

    }



    public function uploadFile($file,$options = [])

    {



        $photo          = null;

        $allowMimeTypes = isset($options['allow.MimeTypes']) ? $options['allow.MimeTypes'] : ["image/gif", "image/png", "image/jpeg", "image/jpg"];

        

        $file = (object) $file;

        //print_r($file); die();

        if($this->validate($file,$allowMimeTypes))

        {

            $rootPath   = realpath(Yii::$app->basePath).'/../upload/tempData/';



            if( !is_dir($rootPath) )

            { 

                mkdir($rootPath); 

            }



            $savePath = isset($options['path.save']) ? trim($options['path.save'],'/') : null;



            if(!empty($savePath))

            {

                if(!is_dir($rootPath.'/'.$savePath))

                {

                    $folders    = '';

                    $paths      = explode('/',$savePath);

                    foreach($paths as $item)

                    {

                        $folders .= $item.'/';

                        if(!is_dir($rootPath.'/'.$folders))

                        {

                            mkdir($rootPath.'/'.$folders);

                        }

                    }

                }

            }



            $fullPath      = $rootPath.'/'.$savePath.'/';

            if(strpos($file->name,'.'))

            {

                $ext       = explode('.',$file->name);

                $photoExt  = $ext[count($ext)-1];

                $photo     = rand(1,4948375).'.'.$photoExt;

            }

            if(move_uploaded_file($file->tmp_name, $fullPath.$photo))

            {

                return $photo;

            }

            

        }

    }



    public function deleteFile($files = [],$pathRoot = null)
    {
        if(!empty($files))
        {
            $realPath = realpath(dirname(__FILE__).'/../../');
            $rootPath = isset($pathRoot) ? $realPath.'/'.$pathRoot : $realPath.Yii::$app->params['path.upload'];
            foreach($files as $file)
            {
                $file = trim($file,'/');
                if(is_file($rootPath.'/'.$file))
                {
                    unlink($rootPath.'/'.$file);
                }
            }
        }
    }



    public function reArrayFiles($file)

    {

        $file_ary   = array();

        $file_count = count($file['name']);

        $file_key   = array_keys($file);

        

        for($i=0; $i<$file_count; $i++)

        {

            foreach($file_key as $val)

            {

                $file_ary[$i][$val] = $file[$val][$i];

            }

        }

        return (object)$file_ary;

    }



    public function clone_file($source_file, $clonned_file)

    {

        if(copy($source_file, $clonned_file . pathinfo($source_file, PATHINFO_BASENAME))){

            return true;

        }

        else

        {

            return false;

        }

    }

}