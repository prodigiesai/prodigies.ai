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

class HSeo
{
    private $seoSearchModel;

    private $pageTitle;
    private $pageDescriptions;
    private $pageKeywords;

    private $pageH1 = '';
    private $pageBody = '';

    private static $_instance;

    public function __construct()
    {
        if(issetModule('seo') && (param('useSeoSearchConfigByLink') || param('useSeoSearchConfigBySearch'))){
            $repo = new \application\modules\seo\repositories\SeoSearchRepository();

            $this->seoSearchModel = $repo->getSearchModel();
        }

        if($this->seoSearchModel){
            $this->pageTitle = $repo->parse($this->seoSearchModel->getStrByLang('title'));
            $this->pageDescriptions = $repo->parse($this->seoSearchModel->getStrByLang('description'));
            $this->pageKeywords = $repo->parse($this->seoSearchModel->getStrByLang('keywords'));
            $this->pageH1 = $repo->parse($this->seoSearchModel->getStrByLang('h1'));
            $this->pageBody = $repo->parse($this->seoSearchModel->getStrByLang('body'), false);
        } else {
            $controller = Yii::app()->controller;

            $this->pageTitle = CHtml::encode($controller->seoTitle ? $controller->seoTitle : $controller->pageTitle);
            $this->pageDescriptions = CHtml::encode($controller->seoDescription ? $controller->seoDescription : $controller->pageDescription);
            $this->pageKeywords = CHtml::encode($controller->seoKeywords ? $controller->seoKeywords : $controller->pageKeywords);
        }
    }

    public static function getInstance()
    {
        if(!isset(self::$_instance)){
            self::$_instance = new HSeo();
        }

        return self::$_instance;
    }

    public static function getTitle()
    {
        return self::getInstance()->pageTitle;
    }

    public static function getDescription()
    {
        return self::getInstance()->pageDescriptions;
    }

    public static function getKeywords()
    {
        return self::getInstance()->pageKeywords;
    }

    public static function getH1()
    {
        return self::getInstance()->pageH1;
    }

    public static function getBody()
    {
        return self::getInstance()->pageBody;
    }

    public function setCanonical()
    {
        if($this->seoSearchModel){
            $canonicalUrl = $this->seoSearchModel->canonical_url;
            if(strpos($canonicalUrl, 'http') === false){
                $canonicalUrl = Yii::app()->controller->createAbsoluteUrl('/', ['lang' => Yii::app()->language]) . '/' . ltrim($canonicalUrl, '/');
            }
        } else {
            $canonicalUrl = Yii::app()->getBaseUrl(true) . '/' . Yii::app()->request->getPathInfo();
            $page = (int) Yii::app()->request->getParam('page');
            if (isset($page) && $page > 1) {
                $canonicalUrl .= '?page=' . $page;
            }
        }

        if($canonicalUrl){
            Yii::app()->clientScript->registerLinkTag('canonical', null, $canonicalUrl);
        }

        unset($canonicalUrl);
    }

    public function getMetaRobotsNoindex()
    {
        if($this->seoSearchModel && $this->seoSearchModel->is_noindex){
            return '<meta name="robots" content="noindex" />'.PHP_EOL;
        }
        return '';
    }

    public static function getCityUrlById($cityId, $params = array())
    {
        return SeoTrash::getInstance()->getCityUrlById($cityId, $params);
    }

    public static function getCityObjTypeLinkById($cityId, $objTypeId, $params = array())
    {
        return SeoTrash::getInstance()->getCityObjTypeLinkById($cityId, $objTypeId, $params);
    }
}

class SeoTrash
{
    private static $_instance;

    private $citiesListResult = array();
    private $objTypesListResult = array();
    private $countApartmentsByCategories = array();

    private $params = array();

    public function __construct()
    {
        $resCounts = array();
        if (issetModule('seo')) {
            $this->citiesListResult = SeoFriendlyUrl::getActiveCityRoute($this->params);
            $this->objTypesListResult = SeoFriendlyUrl::getActiveObjTypesRoute($this->params, true, true);
            $resCounts = SeoFriendlyUrl::getCountApartmentsForCategories($this->params);
        }

        if (!empty($resCounts)) {
            foreach ($resCounts as $values) {
                $this->countApartmentsByCategories[$values['city']][$values['obj_type_id']] = $values['count'];
            }
        }
        unset($resCounts);
    }

    /**
     * @return SeoTrash
     */
    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new SeoTrash();
        }

        return self::$_instance;
    }

    public function getCityUrlById($cityId, $params = array())
    {
        $cityValue = isset($this->citiesListResult[$cityId]) ? $this->citiesListResult[$cityId] : null;

        if (!$cityValue) {
            return Yii::app()->createUrl('/search', array('city[]' => intval($cityId)));
        }

        $paramsType = $paramsObjType = null;
        if (isset($params)) {
            $paramsType = (isset($params['type'])) ? $params['type'] : null;
        }

        $linkParams = array(
            'cityUrlName' => $cityValue[Yii::app()->language]['url'],
        );

        if (!empty($paramsType)) {
            $linkParams['apType'] = $paramsType;
        }

        return Yii::app()->controller->createUrl('/seo/main/viewsummaryinfo', $linkParams);
    }

    public function getCityObjTypeUrlById($cityId, $objTypeId, $params = array())
    {
        $cityValue = isset($this->citiesListResult[$cityId]) ? $this->citiesListResult[$cityId] : null;
        $objValue = isset($this->objTypesListResult[$objTypeId]) ? $this->objTypesListResult[$objTypeId] : null;

        if (!$cityValue || !$objValue) {
            return Yii::app()->createUrl('/search', array('city[]' => intval($cityId), 'objType' => intval($objTypeId)));
        }

        $paramsType = $paramsObjType = null;
        if (isset($params)) {
            $paramsType = (isset($params['type'])) ? $params['type'] : null;
        }

        $linkParams = array(
            'cityUrlName' => $cityValue[Yii::app()->language]['url'],
        );

        if (!empty($paramsType)) {
            $linkParams['apType'] = $paramsType;
        }


        $linkParams = array(
            'cityUrlName' => $cityValue[Yii::app()->language]['url'],
            'objTypeUrlName' => $objValue[Yii::app()->language]['url'],
        );


        return Yii::app()->controller->createUrl('/seo/main/viewsummaryinfo', $linkParams);
    }

    public function getCityObjTypeLinkById($cityId, $objTypeId, $params = array())
    {
        $cityValue = isset($this->citiesListResult[$cityId]) ? $this->citiesListResult[$cityId] : null;
        $objValue = isset($this->objTypesListResult[$objTypeId]) ? $this->objTypesListResult[$objTypeId] : null;

        if (!$cityValue || !$objValue) {
            return null;
        }

        $linkName = $objValue[Yii::app()->language]['name'];
        $addCount = '';
        $class = 'inactive-obj-type-url';
        if (isset($this->countApartmentsByCategories[$cityId]) && isset($this->countApartmentsByCategories[$cityId][$objTypeId])) {
            $class = 'active-obj-type-url';
            $addCount = '<span class="obj-type-count">(' . $this->countApartmentsByCategories[$cityId][$objTypeId] . ')</span>';
        }

        return CHtml::link(
            $linkName . ' ' . $addCount, $this->getCityObjTypeUrlById($cityId, $objTypeId, $params), array('class' => $class)
        );
    }
}