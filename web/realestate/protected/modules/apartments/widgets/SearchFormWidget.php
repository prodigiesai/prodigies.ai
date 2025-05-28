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
namespace application\modules\apartments\widgets;

use SearchHelper;
use Yii;

class SearchFormWidget extends \CWidget
{
    public $model;

    public $column = 3;

    public $searchFields = [
        'id', 'ownerEmail', 'status', 'status_owner', 'title', 'type', 'obj_type_id', 'price', 'floor', 'square',
        'rooms', 'ot', 'photo'
    ];

    public $action = '';

    public $isShowBadges = false;
    public $isUseForm = true;
    public $isShowForm = false;
    public $isShowApplyButton = true;
    public $isShowClearButton = true;

    public $typeList;
    public $objectTypeList;

    public function getViewPath($checkTheme = true)
    {
        if ($checkTheme && ($theme = Yii::app()->getTheme()) !== null) {
            if (is_dir($theme->getViewPath().DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'apartments'.DIRECTORY_SEPARATOR.'views')) {
                return $theme->getViewPath().DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'apartments'.DIRECTORY_SEPARATOR.'views';
            }
        }
        return Yii::getPathOfAlias('application.modules.apartments.views.backend.search');
    }

    public function run()
    {
        $this->registerJs();

        $this->render('search', [
            'model' => $this->model,
        ]);
    }

    public function createUrl($route, $params = [])
    {
        return Yii::app()->createUrl($route, $params);
    }

    public function getTypeList()
    {
        if($this->typeList){
            return $this->typeList;
        }
        return \HApartment::getTypesArray(false);
    }

    public function getObjectTypeList()
    {
        if($this->objectTypeList){
            return $this->objectTypeList;
        }
        return \CArray::merge(array(0 => ''), \ApartmentObjType::getList());
    }

    public static function getBadges(\Apartment $model)
    {
        $badges = array();
        if ($model->id) {
            $badges[] = self::badge(tt('ID', 'apartments').': '.$model->id, 'filter.clearInput(\'#ap_find_id\');');
        }
        if ($model->ownerEmail) {
            $badges[] = self::badge(tt('Owner email', 'apartments').': '.$model->ownerEmail,
                'filter.clearInput(\'#ap_find_ownerEmail\');');
        }
        $titleField = 'title_'.\Yii::app()->language;
        if ($model->$titleField) {
            $badges[] = self::badge(tt('Apartment title', 'apartments').': '.$model->$titleField,
                'filter.clearInput(\'#ap_find_title\');');
        }
        if (isset($activeList[$model->active])) {
            $badges[] = self::badge(tt('Status', 'apartments').': '.$activeList[$model->active],
                'filter.clearSelect(\'#active\', true);');
        }
        if (isset($activeListOwner[$model->owner_active])) {
            $badges[] = self::badge(tt('Status (owner)', 'apartments').': '.$activeListOwner[$model->owner_active],
                'filter.clearSelect(\'#owner_active\', true);');
        }
        if ($model->loc_country) {
            $county = \Country::model()->findByPk($model->loc_country);
            if ($county) {
                $badges[] = self::badge($county->getName(),
                    'filter.clearSelect(\'#ap_city\', false); filter.clearSelect(\'#region\', false); filter.clearSelect(\'#county_f\', true);');
            }
        }
        if ($model->loc_region) {
            $region = \Region::model()->findByPk($model->loc_region);
            if ($region) {
                $badges[] = self::badge($region->getName(),
                    'filter.clearSelect(\'#ap_city\', false); filter.clearSelect(\'#region\', true);');
            }
        }
        if ($model->loc_city) {
            $city = \City::model()->findByPk($model->loc_city);
            if ($city) {
                $badges[] = self::badge($city->getName(), 'filter.clearSelect(\'#ap_city\', true);');
            }
        }

        if ($model->metroSrc && $model->loc_city) {
            $badgeMetro = self::getBageForMetro($model->metroSrc, $model->loc_city);
            if ($badgeMetro) {
                $badges[] = $badgeMetro;
            }
        }
        if ($model->type && isset($types[$model->type])) {
            $badges[] = self::badge($types[$model->type], 'filter.clearSelect(\'#type_f\', true);');
        }
        if ($model->obj_type_id && isset($objTypes[$model->obj_type_id])) {
            $badges[] = self::badge($objTypes[$model->obj_type_id], 'filter.clearSelect(\'#obj_type\', true);');
        }
        if ($model->price_min) {
            $badges[] = self::badge(tc('Price from').': '.$model->price_min, 'filter.clearInput(\'#price_min\');');
        }
        if ($model->price_max) {
            $badges[] = self::badge(tc('Price to').': '.$model->price_max, 'filter.clearInput(\'#price_max\');');
        }
        if ($model->floor_min) {
            $badges[] = self::badge(tc('Floor from').': '.$model->floor_min, 'filter.clearInput(\'#floor_min\');');
        }
        if ($model->floor_max) {
            $badges[] = self::badge(tc('Floor to').': '.$model->floor_max, 'filter.clearInput(\'#floor_max\');');
        }
        if ($model->square_min) {
            $badges[] = self::badge(tc('Square from').': '.$model->square_min, 'filter.clearInput(\'#square_min\');');
        }
        if ($model->square_max) {
            $badges[] = self::badge(tc('Square to').': '.$model->square_max, 'filter.clearInput(\'#square_max\');');
        }
        if ($model->searchPaidService && isset($paidServicesArray[$model->searchPaidService])) {
            $badges[] = self::badge(tc('Paid services').': '.$paidServicesArray[$model->searchPaidService],
                'filter.clearSelect(\'#searchPaidService\', true);');
        }
        $roomItems = SearchHelper::getRoomsList();
        if ($model->rooms && isset($roomItems[$model->rooms])) {
            $badges[] = self::badge(tc('Number of rooms').': '.$roomItems[$model->rooms],
                'filter.clearInput(\'#rooms\');');
        }
        $ownerList = SearchHelper::getOwnerList();
        if ($model->ot && isset($ownerList[$model->ot])) {
            $badges[] = self::badge(tc('Listing from').': '.$ownerList[$model->ot],
                'filter.clearInput(\'#search_ot\');');
        }
        $photoList = SearchHelper::getPhotoList();
        if ($model->photo && isset($photoList[$model->photo])) {
            $badges[] = self::badge(tc('Photo').': '.$photoList[$model->photo],
                'filter.clearInput(\'#search_photo\');');
        }

        return $badges;
    }

    private static function badge($label, $onclick = '')
    {
        return '<div class="badge">'.\CHtml::encode($label).'&nbsp;&nbsp;<i class="close-filter glyphicon glyphicon-remove" onclick="'.$onclick.'"></i></div>';
    }

    private static function getBageForMetro($metroSel, $city)
    {
        $metros = \MetroStations::getMetrosArray($city, 0);
        $label = array();
        foreach ($metroSel as $id) {
            if (isset($metros[$id])) {
                $label[] = $metros[$id];
            }
        }
        return $label ? self::badge(implode(', ', $label), 'filter.clearMetro();') : '';
    }

    private function registerJs()
    {
        $labelHide = \CJavaScript::encode(tc('Hide filter'));
        $labelShow = \CJavaScript::encode(tc('Filter'));

        $script = <<< JS

        function clearSearch(elem) {
            $('#ap_filter')[0].reset();
            $('#ap_filter').submit();
            $(".ladda-button").removeAttr("disabled").removeAttr("data-loading");
        }
    
        var filter = {
            clearSelect: function (id, submit) {
                $(id + " option[selected]").removeAttr("selected");
                $(id + " option:first").attr("selected", "selected");
                $(id).val("");
                if (submit === true) {
                    filter.submit();
                }
            },
            clearInput: function (id) {
                $(id).val('').change();
                filter.submit();
            },
            submit: function () {
                //            $('#ap_filter').submit();
                //            return false;
                $.fn.yiiGridView.update('apartments-grid', {
                    data: $($('#ap_filter')).serialize(),
    
                    complete: function (jqXHR, status) {
                        $(".ladda-button").removeAttr("disabled").removeAttr("data-loading");
                        if (status == 'success') {
                            let data = $('<div>' + jqXHR.responseText + '</div>');
                            let badges = $(data).find('#search_badge');
                            $('#search_badge').html(badges.html());
                            //admin.afterGridUpdate();
                        }
                    }
                });
            },
            clearMetro: function () {
                $('#metro').val('').trigger("chosen:updated");
                filter.submit();
            }
        }
    
        $('#ap_filter').submit(function () {
            filter.submit();
            return false;
        });

        function showHideApartmentsFilter() {
            $("#search_ap").toggle(); 
            
            if ($("#search_ap").is(":visible")) {
                $("#show-hide-aps-filter-button").html("<span class=\"fa fa-filter\"></span> " + $labelHide);
            }
            else {
                $("#show-hide-aps-filter-button").html("<span class=\"fa fa-filter\"></span> " + $labelShow);
            }
            
            return false;
        }
JS;

        Yii::app()->getClientScript()->registerScript('js-search-filter', $script, \CClientScript::POS_END);
    }
}