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

class Themes extends ParentModel
{

    private static $_defaultTheme;
    public static $_params;
    private static $_cache;

    public $path = 'webroot.uploads.rkl';
    public $urlRoute = 'uploads/rkl';
    public $upload;
    public $upload_img;
    public $maxHeight;
    public $maxWidth;
    public $supportExt = 'jpg, png, gif';
    public $fileMinSize = 10485; /* 1024 * 1024 * 0.01 - 10 KB */
    public $fileMaxSize = 10485760; /* 1024 * 1024 * 10 - 10 MB */

    const ADDITIONAL_VIEW_FULL_WIDTH_SLIDER = 1;
    const ADDITIONAL_VIEW_FULL_WIDTH_MAP = 2;
    const ADDITIONAL_VIEW_SEARCH_ONLY = 3;

    public $payModel = null;
    public $payModelName = null;
    public $viewName = null;

    const THEME_ATLAS_NAME = 'atlas';
    const THEME_BASIS_NAME = 'basis';
    const THEME_DOLPHIN_NAME = 'dolphin';

    public function init()
    {
        $fileMaxSize['set'] = $this->fileMaxSize;
        $fileMaxSize['postSize'] = toBytes(ini_get('post_max_size'));
        $fileMaxSize['uploadSize'] = toBytes(ini_get('upload_max_filesize'));
        $this->fileMaxSize = min($fileMaxSize);

        return parent::init();
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{themes}}';
    }

    public function behaviors()
    {
        $arr = array(
            'AutoTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'date_updated',
                'updateAttribute' => 'date_updated',
            ),
        );

        $arr['JsonBehavior'] = array(
            'class' => 'application.components.behaviors.JsonBehavior',
        );

        return $arr;
    }

    public function rules()
    {
        return array(
            array(
                'upload_img', 'file',
                'types' => "{$this->supportExt}",
                'minSize' => $this->fileMinSize,
                'maxSize' => $this->fileMaxSize,
                'tooSmall' => Yii::t('module_themes', 'The file was less than {size}MB. Please upload a larger file.', array('{size}' => round($this->fileMinSize / (1024 * 1024)))),
                'tooLarge' => Yii::t('module_slider', 'The file was larger than {size}MB. Please upload a smaller file.', array('{size}' => round($this->fileMaxSize / (1024 * 1024)))),
                'allowEmpty' => true,
                'on' => 'upload',
            ),
            array('title, is_default, date_updated', 'required'),
            array('additional_view, is_default', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 20),
            array('color_theme, bg_image', 'length', 'max' => 100),
            array('id, title, is_default, date_updated', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => tt('title'),
            'additional_view' => tc('Addition'),
            'is_default' => tt('Is Default'),
            'color_theme' => tt('Color theme', 'themes'),
            'bg_image' => tt('Background image', 'themes'),
            'upload_img' => tt('Background image', 'themes'),
            'date_updated' => tc('Last updated on'),
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('is_default', $this->is_default);
        $criteria->compare('date_updated', $this->date_updated, true);

        return new CustomActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array('defaultOrder' => $this->getTableAlias() . '.title ASC'),
            'pagination' => array(
                'pageSize' => param('adminPaginationPageSize', 20),
            ),
        ));
    }

    public function beforeSave()
    {
        if ($this->scenario == 'set_default') {
            $sql = "UPDATE " . $this->tableName() . " SET is_default=0 WHERE id !=" . $this->id;
            Yii::app()->db->createCommand($sql)->execute();
        }

        return parent::beforeSave();
    }

    public static function getDefaultTheme()
    {
        $tmpTemplate = null;

        if (!isset(self::$_defaultTheme)) {

            $sql = "SELECT id, title, additional_view, color_theme, bg_image, json_data FROM {{themes}} WHERE is_default=1";
            $data = Yii::app()->db->createCommand($sql)->queryRow();

            if ((demo() && !basicVersion()) || isDev()) {
                if (isset($_GET['template']) && array_key_exists($_GET['template'], self::getTemplatesList(isDev()))) {
                    $tmpTemplate = CHtml::encode($_GET['template']);
                } elseif (isset(Yii::app()->request->cookies['template']) && isset(Yii::app()->request->cookies['template']->value) && array_key_exists(Yii::app()->request->cookies['template']->value, self::getTemplatesList())) {
                    $tmpTemplate = CHtml::encode(Yii::app()->request->cookies['template']->value);
                }

                if (!empty($tmpTemplate)) {
                    $sql = "SELECT id, title, additional_view, color_theme, bg_image, json_data FROM {{themes}} WHERE title=:title";
                    $tmpData = Yii::app()->db->createCommand($sql)->queryRow(true, array(':title' => $tmpTemplate));

                    if (!empty($tmpData)) {
                        $data = $tmpData;
                    }
                }
            }

            self::$_params['id'] = $data['id'];
            self::$_params['title'] = $data['title'];
            self::$_params['additional_view'] = $data['additional_view'];
            self::$_params['json_data'] = $data['json_data'] ? CJSON::decode($data['json_data']) : array();

            if (demo() || isDev()) {
                if (isset($_GET['theme']) && array_key_exists($_GET['theme'], Themes::getColorThemesList($tmpTemplate))) {
                    self::$_params['color_theme'] = CHtml::encode($_GET['theme']);
                } else {
                    self::$_params['color_theme'] = isset(Yii::app()->request->cookies['theme']) ? (string)CHtml::encode(Yii::app()->request->cookies['theme']) : $data['color_theme'];
                }
            } else {
                self::$_params['color_theme'] = $data['color_theme'];
            }

            if (self::$_params['color_theme'] == 'color-green.css') {
                self::$_params['bg_image'] = $data['bg_image'] ? $data['bg_image'] : 'demo-cloud.jpg';
            } else {
                self::$_params['bg_image'] = $data['bg_image'];
            }

            self::$_defaultTheme = $data['title'];
        }
        return self::$_defaultTheme;
    }

    public function getIsDefaultHtml()
    {
        if ($this->is_default == 1) {
            $onclick = 'return false;';
        } else {
            $onclick = "changeDefault(" . $this->id . ");";
        }
        return CHtml::radioButton("is_default", ($this->is_default == 1), array('onclick' => $onclick));
    }

    public function setDefault()
    {
        if ($this->is_default) {
            return false;
        }

        $this->scenario = 'set_default';
        $this->is_default = 1;
        $this->update('is_default');

        return true;
    }

    public static function getParam($key)
    {
        return isset(self::$_params[$key]) ? self::$_params[$key] : '';
    }

    public static function getParamJson($key, $default = '')
    {
        return isset(self::$_params['json_data'][$key]) ? self::$_params['json_data'][$key] : $default;
    }

    public static function getColorThemesList($theme = self::THEME_ATLAS_NAME)
    {
        if ($theme == self::THEME_ATLAS_NAME) {
            return [
                '' => 'Default', # нельзя ставить "-" или что-то другое. иначе в Themes::getParam('color_theme') сохраняется это значение и уже минификатор не найдёт такой css
                'color-fresh.css' => 'Fresh',
                'color-bagway-gradient.css' => 'Bagway gradient',
                'color-green.css' => 'Green',
                'color-sandstone.css' => 'Sandstone',
            ];
        } elseif ($theme == self::THEME_BASIS_NAME) {
            return [
                '' => 'Default', # нельзя ставить "-" или что-то другое. иначе в Themes::getParam('color_theme') сохраняется это значение и уже минификатор не найдёт такой css
                'color-rage.css' => 'Rage',
                'color-rebirth.css' => 'Rebirth',
            ];
        } elseif ($theme == self::THEME_DOLPHIN_NAME) {
            return [
                '' => 'Default', # нельзя ставить "-" или что-то другое. иначе в Themes::getParam('color_theme') сохраняется это значение и уже минификатор не найдёт такой css
                'color-rage.css' => 'Rage',
                'color-vitality.css' => 'Vitality',
            ];
        }

        return [];
    }

    public static function getTemplatesList($all = false)
    {
        $array = array(
            self::THEME_ATLAS_NAME => 'Theme "Atlas"',
            self::THEME_BASIS_NAME => 'Theme "Basis"',
            self::THEME_DOLPHIN_NAME => 'Theme "Dolphin"',
        );

        return $array;
    }

    public static function getAllTemplatesList()
    {
        return [
            self::THEME_ATLAS_NAME,
            self::THEME_BASIS_NAME,
            self::THEME_DOLPHIN_NAME,
        ];
    }

    public static function getAllowedTemplatesList()
    {
        $version = isFree()
            ? 'free'
            : (basicVersion()
                ? 'basic'
                : 'pro');

        $allThemes = self::getAllTemplatesList();

        if ($version == 'free' || $version == 'basic') {
            # для free и basic вырезаем Дельфина и Базис
            $index = array_search(self::THEME_DOLPHIN_NAME, $allThemes);
            if (is_int($index)) {
                unset($allThemes[$index]);
            }

            $index = array_search(self::THEME_BASIS_NAME, $allThemes);
            if (is_int($index)) {
                unset($allThemes[$index]);
            }
        }

        return $allThemes;
    }

    public static function getListData()
    {
        if (!isset(self::$_cache)) {
            self::$_cache = CHtml::listData(Themes::model()->findAll(), 'id', 'title');
        }

        return self::$_cache;
    }

    public static function getDBTemplatesList()
    {
        return self::getListData();
    }

    public static function getAdditionalViewList($translateFromMessageFile = false)
    {
        return array(
            0 => (!$translateFromMessageFile) ? tc('No') : Yii::t('module_install', 'No', array(), 'messagesInFile', Yii::app()->language),
            self::ADDITIONAL_VIEW_FULL_WIDTH_SLIDER => (!$translateFromMessageFile) ? tt('Use_full_width_slider_homepage') : Yii::t('module_install', 'Use_full_width_slider_homepage', array(), 'messagesInFile', Yii::app()->language),
            self::ADDITIONAL_VIEW_FULL_WIDTH_MAP => (!$translateFromMessageFile) ? tt('Use_full_width_map_homepage') : Yii::t('module_install', 'Use_full_width_map_homepage', array(), 'messagesInFile', Yii::app()->language),
            self::ADDITIONAL_VIEW_SEARCH_ONLY => (!$translateFromMessageFile) ? tt('Use_search_without_slider_homepage') : Yii::t('module_install', 'Use_search_without_slider_homepage', array(), 'messagesInFile', Yii::app()->language),
        );
    }

    public static function getBgUrl($bgImage = null)
    {
        $bgImage = $bgImage ? $bgImage : Themes::getParam('bg_image');
        $model = self::model();
        $path = Yii::getPathOfAlias($model->path);
        $filePath = $path . DIRECTORY_SEPARATOR . $bgImage;
        if ($bgImage && file_exists($filePath)) {
            return Yii::app()->baseUrl . '/' . $model->urlRoute . '/' . $bgImage;
        } else {
            return false;
        }
    }

    public function delImage()
    {
        $path = Yii::getPathOfAlias($this->path);
        $filePath = $path . DIRECTORY_SEPARATOR . $this->bg_image;
        if ($this->bg_image && file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    public function isAllowEdit()
    {
        return in_array($this->title, array(Themes::THEME_ATLAS_NAME, Themes::THEME_BASIS_NAME, Themes::THEME_DOLPHIN_NAME));
    }
}
