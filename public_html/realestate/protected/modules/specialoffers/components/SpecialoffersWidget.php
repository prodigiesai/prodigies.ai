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

class SpecialoffersWidget extends CWidget
{

    public $usePagination = 1;
    public $criteria = null;
    public $count = null;
    public $showWidgetTitle = true;
    public $widgetTitle = null;
    public $breadcrumbs = null;
    public $numBlocks = 3;
    public $showSorter = 1;
    public $showSwitcher = 1;
    public $setLimit = 1;
    public $showIfNone = true;
    public $modeListShow = '';
    public $showCount = false;
    public $customWidgetTitle;
    public $widgetTitles;
    public $callFromWidget = false;
    public $urlSwitching = array('block' => 1, 'table' => 1, 'map' => 0);

    public function getViewPath($checkTheme = true)
    {
        if ($checkTheme && ($theme = Yii::app()->getTheme()) !== null) {
            if (is_dir($theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'apartments' . DIRECTORY_SEPARATOR . 'views'))
                return $theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'apartments' . DIRECTORY_SEPARATOR . 'views';
        }
        return Yii::getPathOfAlias('application.modules.apartments.views');
    }

    public function run()
    {
        if (Yii::app()->theme->name == Themes::THEME_BASIS_NAME && Yii::app()->controller->layout == '//layouts/inner') {
            $this->numBlocks = 2;
        }

        $this->widgetTitle = tc('Special offers');

        $this->criteria = new CDbCriteria;
        $this->criteria->addCondition('is_special_offer = 1');

        $result = \application\modules\apartments\helpers\ApartmentsHelper::getApartments(param('countListitng' . User::getModeListShow(), 6), $this->usePagination, 0, $this->criteria);

        if ($this->count) {
            $result['count'] = $this->count;
        } else {
            $result['count'] = $result['apCount'];
        }

        $result['showCount'] = $this->showCount;

        $subTitleKey = InfoPages::getWidgetSubTitleKey('specialoffers');
        $result['customWidgetTitle'] = $this->customWidgetTitle = InfoPages::getWidgetSubTitle($subTitleKey, $this->widgetTitles);
        if (!empty($this->widgetTitles)) {
            $this->callFromWidget = true;
        }

        $result['callFromWidget'] = $this->callFromWidget;

        $this->render('widgetApartments_list', $result);
    }
}
