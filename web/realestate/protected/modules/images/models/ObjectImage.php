<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.38.0
 * 	copyright			:	(c) 2016 Monoray
 * 							http://monoray.net
 * 							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */

class ObjectImage extends ParentModel
{

    const UPLOAD_DIR = 'models';
    const SMALL_THUMB_WIDTH = 150;
    const SMALL_THUMB_HEIGHT = 115;
    const FULL_THUMB_WIDTH = 480;
    const FULL_THUMB_HEIGHT = 480;

    public $imageInstance = null;
    public $path = 'webroot.uploads.object';

    public static function getSupportExt()
    {
        return 'jpg, png, gif, jpeg';
    }

    public static function getMaxImageSize($mb = false)
    {
        $fileMaxSize['postSize'] = toBytes(ini_get('post_max_size'));
        $fileMaxSize['uploadSize'] = toBytes(ini_get('upload_max_filesize'));
        $maxImageSize = min($fileMaxSize);
        $maxImageSizeMb = round($maxImageSize / (1024 * 1024));

        return $mb ? $maxImageSizeMb : $maxImageSize;
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{object_image}}';
    }

    public function rules()
    {
        return array();
    }

    public function relations()
    {
        $relations = array();
        if (issetModule('seo')) {
            $relations['seo'] = array(self::HAS_ONE, 'SeoFriendlyUrl', 'model_id', 'on' => 'model_name="ObjectImage"', 'alias' => 'image_seo');
        }

        //$relations['info'] = array(self::HAS_ONE, 'Infopages', 'model_id', 'on' => 'model_name="Infopages"', 'alias' => 'image_seo');

        return $relations;
    }

    public function seoFields()
    {
        return array();
    }

    public function fullHref()
    {
        return self::getUploadUrl($this) . '/' . $this->name;
    }

    public function getThumb($width, $height, $adaptive = false)
    {
        $path = self::getUploadDirectory($this);
        $filePath = $path . DIRECTORY_SEPARATOR . 'thumb_' . $width . 'x' . $height . "_" . $this->name;
        $fileName = 'thumb_' . $width . 'x' . $height . "_" . $this->name;
        if (file_exists($filePath)) {
            return $fileName;
        } else {
            $image = new CImageHandler();
            if ($image->load($path . DIRECTORY_SEPARATOR . $this->name)) {
                if ($adaptive) {
                    $image->adaptiveThumb($width, $height)->save($filePath, false, param('thumbQuality', 75));
                } else {
                    $image->thumb($width, $height)->save($filePath, false, param('thumbQuality', 75));
                }
                return $fileName;
            }
            return null;
        }
    }

    public function getThumbHref($width, $height)
    {
        $thumbName = $this->getThumb($width, $height);

        return self::getUploadUrl($this) . '/' . $thumbName;
    }

    public function beforeSave()
    {
        if ($this->imageInstance) {
            $path = self::getUploadDirectory($this);
            $name = $this->imageInstance->getName();

            $fileName = pathinfo($name, PATHINFO_FILENAME);
            $fileExt = pathinfo($name, PATHINFO_EXTENSION);

            $name = translit($fileName) . '.' . $fileExt;

            while (file_exists($path . DIRECTORY_SEPARATOR . $name)) {
                $name = rand(0, 9) . $name;
            }

            if ($this->imageInstance->saveAs($path . DIRECTORY_SEPARATOR . $name)) {
                $resultMemoryCheck = HSite::allowUploadAndResizeImage($path . DIRECTORY_SEPARATOR . $name);

                if ($resultMemoryCheck['result'] === true) {
                    $this->name = $name;
                } else {
                    $this->name = '';

                    @unlink($path . DIRECTORY_SEPARATOR . $name);
                    Yii::app()->user->setFlash(
                        'danger', tc('Upload failed. To upload image please increase the amount of RAM in your hosting.') . '(Minimum: ' . $resultMemoryCheck['memoryImageNeededInMB'] . 'MB)'
                    );
                }
            } else {
                return false;
            }
        }

        return parent::beforeSave();
    }

    public function beforeDelete()
    {
        if (issetModule('seo')) {
            $sql = 'DELETE FROM {{seo_friendly_url}} WHERE model_id="' . $this->id . '" AND model_name = "ObjectImage"';
            Yii::app()->db->createCommand($sql)->execute();
        }

        //@unlink(self::getUploadDirectory($this) . DIRECTORY_SEPARATOR . $this->name);
        @array_map("unlink", glob(self::getUploadDirectory($this) . "/*" . $this->name));

        return parent::beforeDelete();
    }

    public function getSmallThumbLink()
    {
        $name = $this->getThumb(self::SMALL_THUMB_WIDTH, self::SMALL_THUMB_HEIGHT);
        if ($name !== null) {
            return self::getUploadUrl($this) . '/' . $name;
        } else {
            return null;
        }
    }

    public function behaviors()
    {
        $arr = array();
//        if (issetModule('historyChanges')) {
//            $arr['ArLogBehavior'] = array(
//                'class' => 'application.modules.historyChanges.components.ArLogBehavior',
//            );
//        }

        return $arr;
    }

    public static function getUploadDirectory(ObjectImage $model, $category = self::UPLOAD_DIR)
    {
        $DS = DIRECTORY_SEPARATOR;
        $root = ROOT_PATH . $DS . 'uploads' . $DS . $category;
        self::genDir($root);

        $year = date('Y', strtotime($model->date_created));
        $path = $root . $DS . $year;
        self::genDir($path);

        $month = date('m', strtotime($model->date_created));
        $path = $path . $DS . $month;
        self::genDir($path);

        return $path;
    }

    public static function getUploadUrl(ObjectImage $model, $category = self::UPLOAD_DIR)
    {
        $DS = '/';
        $root = 'uploads' . $DS . $category;

        $year = date('Y', strtotime($model->date_created));
        $path = $root . $DS . $year;

        $month = date('m', strtotime($model->date_created));
        $path = $path . $DS . $month;

        return Yii::app()->baseUrl . $DS . $path;
    }

    public static function genDir($path)
    {
        if (!is_dir($path)) {
            if (!mkdir($path)) {
                throw new CException('ObjectImage невозможно создать директорию ' . $path);
            }
        }
    }

    public function getFullThumbLink()
    {
        $name = $this->getThumb(self::FULL_THUMB_WIDTH, self::FULL_THUMB_HEIGHT);
        if ($name !== null) {
            return self::getUploadUrl($this) . DIRECTORY_SEPARATOR . $name;
        } else {
            return null;
        }
    }
}
