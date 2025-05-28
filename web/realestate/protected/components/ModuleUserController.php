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

class ModuleUserController extends Controller
{

    public $metroStations;
    public $cityActive;
    public $layout = '//layouts/inner';
    public $params = array();
    private $_model;
    public $_isAPICall = false;
    public $modelName;
    public $adminStatsBage;

    public function getViewPath($checkTheme = true)
    {
        if ($checkTheme && ($theme = Yii::app()->getTheme()) !== null) {
            if (is_dir($theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->getModule($this->id)->getName() . DIRECTORY_SEPARATOR . 'views'))
                return $theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->getModule($this->id)->getName() . DIRECTORY_SEPARATOR . 'views';
        }
        return Yii::getPathOfAlias('application.modules.' . $this->getModule($this->id)->getName() . '.views');
    }

    public function beginWidget($className, $properties = array())
    {
        if ($className == 'CustomForm') {
            $className = 'CActiveForm'; // 'CustomActiveForm'
        }
        if ($className == 'CustomGridView') {
            $className = 'CGridView';
        }
        return parent::beginWidget($className, $properties);
    }

    public function widget($className, $properties = array(), $captureOutput = false)
    {
        if ($className == 'bootstrap.widgets.TbButton') {
            if (isset($properties['htmlOptions'])) {
                return CHtml::submitButton($properties['label'], $properties['htmlOptions']);
            } else {
                return CHtml::submitButton($properties['label']);
            }
        }

        return parent::widget($className, $properties, $captureOutput);
    }

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            array(
                'ESetReturnUrlFilter + index, view, create, update, bookingform, complain, mainform, add, edit',
            ),
            array(
                'application.filters.html.ECompressHtmlFilter',
                'gzip' => false, /* (YII_DEBUG ? false : true), */
                'doStripNewlines' => false,
                'actions' => '*'
            ),
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'roles' => array('guest'),
            ),
        );
    }

    public function init()
    {
        parent::init();
        //$this->metroStations = SearchForm::stationsInit();
        $this->cityActive = SearchForm::cityInit();

        $this->_isAPICall = (issetModule('api') && Yii::app()->user->getState('is_from_api') == 1) ? true : false;
        if ($this->_isAPICall) {
            setLang(Lang::getDefaultLang());
            if (issetModule('currency')) {
                setCurrencyCookie(Currency::getDefaultCurrencyModel()->char_code);
            }
        }
    }

    public function actionView($id = 0, $url = '')
    {
        if ($url && issetModule('seo')) {
            $seo = SeoFriendlyUrl::getForView($url, $this->modelName);

            if (!$seo) {
                throw404();
            }

            $this->setSeo($seo);

            $id = $seo->model_id;
        }
        $model = $this->loadModel($id, 1);

        HSite::setCanonicalTag();

        $this->render('view', array(
            'model' => $model,
        ));
    }

    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider($this->modelName);
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function loadModel($id = null, $resetScope = 0)
    {
        if ($this->_model === null) {
            if ($id == null) {
                if (isset($_GET['id'])) {
                    $model = new $this->modelName;
                    if ($resetScope) {
                        $this->_model = $model->resetScope()->findByPk($_GET['id']);
                    } else {
                        $this->_model = $model->findByPk($_GET['id']);
                    }
                }
            } else {
                $model = new $this->modelName;
                if ($resetScope) {
                    $this->_model = $model->resetScope()->findByPk($id);
                } else {
                    $this->_model = $model->findByPk($id);
                }
            }

            if ($this->_model === null) {
                throw new CHttpException(404, tc('The requested page does not exist.'));
            }
        }
        return $this->_model;
    }

    public function loadModelWith($with)
    {
        if ($this->_model === null) {
            if (isset($_GET['id'])) {
                $model = new $this->modelName;
                $this->_model = $model->with($with)->findByPk($_GET['id']); //findByPk($_GET['id']);
            }
            if ($this->_model === null) {
                throw new CHttpException(404, tc('The requested page does not exist.'));
            }
        }
        return $this->_model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $this->modelName . '-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function sliderImages()
    {
        $dependency = new CDbCacheDependency('SELECT MAX(date_updated) FROM {{slider}}');
        $sql = 'SELECT url FROM {{slider}} ORDER BY sorter';
        $items = Yii::app()->db->cache(param('cachingTime', 86400), $dependency)->createCommand($sql)->queryColumn();
        return $this->renderPartial('_slider_image', array('items' => $items), true);
    }

    /**
     * Renders a view with a layout.
     *
     * @param string $view name of the view to be rendered. See {@link getViewFile} for details
     * about how the view script is resolved.
     * @param array $data data to be extracted into PHP variables and made available to the view script
     * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
     * @param array $fields allowed fields to REST render
     * @return string the rendering result. Null if the rendering result is not required.
     * @see renderPartial
     * @see getLayoutFile
     */
    public function render($view, $data = null, $return = false, array $fields = array('count', 'model', 'data'))
    {
        if (issetModule('api') && ($behavior = $this->asa('restAPI')) && $behavior->getEnabled()) {
            if (isset($data['model']) && $this->isRestService() && count(array_intersect(array_keys($data), $fields)) == 1) {
                $data = $data['model'];
                $fields = null;
            }
            return $this->renderRest($view, $data, $return, $fields);
        } else {
            return parent::render($view, $data, $return);
        }
    }

    /**
     * Redirects the browser to the specified URL or route (controller/action).
     * @param mixed $url the URL to be redirected to. If the parameter is an array,
     * the first element must be a route to a controller action and the rest
     * are GET parameters in name-value pairs.
     * @param boolean|integer $terminate whether to terminate OR REST response status code !!!
     * @param integer $statusCode the HTTP status code. Defaults to 302. See {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html}
     * for details about HTTP status code.
     */
    public function redirect($url, $terminate = true, $statusCode = 302)
    {
        if (($behavior = $this->asa('restAPI')) && $behavior->getEnabled()) {
            $this->redirectRest($url, $terminate, $statusCode);
        } else {
            parent::redirect($url, $terminate, $statusCode);
        }
    }
}
