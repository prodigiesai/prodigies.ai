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

class PopLocations extends PopUnit
{
    public $countries;
    public $regions;
    public $cities;

    public $loc_country;
    public $loc_region;
    public $loc_city;

    public static function getType()
    {
        return PopUnit::TYPE_LOCATIONS;
    }

    public function getItemsId()
    {
        return $this->theme->getFromJson($this->getKeyItemsId());
    }

    public static function getModelByPk($id)
    {
        return City::model()->findByPk($id);
    }

    public function initRef()
    {
        $this->countries = Country::getCountriesArray();

        //при создании города узнаём id первой в дропдауне страны
        $country_keys = array_keys($this->countries);

        if ($this->loc_country && in_array($this->loc_country, $country_keys)) {
            $this->model->loc_country = $this->loc_country;
        } else {
            $this->model->loc_country = isset($country_keys[0]) ? $country_keys[0] : 0;
        }

        $this->regions = Region::getRegionsArray($this->model->loc_country);

        $region_keys = array_keys($this->regions);
        if ($this->loc_region && in_array($this->loc_region, $region_keys)) {
            $this->model->loc_region = $this->loc_region;
        } else {
            $this->model->loc_region = isset($region_keys[0]) ? $region_keys[0] : 0;
        }

        $this->cities = City::getCitiesArray($this->model->loc_region, 0, 2);

        if ($this->loc_city) {
            $this->model->loc_city = $this->loc_city;
        } else {
            $city_keys = array_keys($this->cities);
            $this->model->loc_city = isset($city_keys[0]) ? $city_keys[0] : 0;
        }
    }

    public function renderForm(PopDataForm $model)
    {
        $this->model = $model;

        $this->initRef();

        echo '<div class="clearfix"></div>';
        echo '<br/>';

        echo '<div class="form">';

        echo '<div class="form-group">';
        echo CHtml::activeLabelEx($this->model, 'loc_country');
        echo Select2::activeDropDownList($this->model, 'loc_country', $this->countries, array(
                'id' => 'ap_country',
                'ajax' => array(
                    'type' => 'GET', //request type
                    'url' => Yii::app()->createUrl('/location/main/getRegions'), //url to call.
                    'data' => 'js:"country="+$("#ap_country").val()',
                    'success' => 'function(result){
						$("#ap_region").html(result);
						$("#ap_region").change();
						$("#ap_region").select2().trigger("change");
					}'
                ),
                'class' => 'span3 form-control'
            )
        );
        echo CHtml::error($this->model, 'loc_country');
        echo '</div>';

        echo '<div class="form-group">';
        echo CHtml::activeLabelEx($this->model, 'loc_region');
        echo Select2::activeDropDownList($this->model, 'loc_region', $this->regions, array('id' => 'ap_region',
                'ajax' => array(
                    'type' => 'GET', //request type
                    'url' => Yii::app()->createUrl('/location/main/getCities'), //url to call.
                    'data' => 'js:"region="+$("#ap_region").val()',
                    'success' => 'function(result){
						$("#item_id").html(result); $("#item_id").select2().trigger("change");' . ((issetModule('metroStations')) ? '$("#item_id").change()' : '') .
                        '}'
                ),
                'class' => 'span3 form-control'
            )
        );
        echo CHtml::error($this->model, 'loc_region');
        echo '</div>';

        echo '<div class="form-group" id="locationCity">';
        echo CHtml::activeLabelEx($this->model, 'loc_city');
        echo CHtml::activeDropDownList($this->model, 'loc_city', $this->cities, array('id' => 'item_id', 'class' => 'span3 form-control'));
        echo CHtml::error($this->model, 'loc_city');
        echo '</div>';

        echo '<br/>';
        echo AdminLteHelper::getLink(tt('Add city'), 'javascript:;', 'fa fa-check', array('class' => 'btn btn-primary', 'id' => 'pd_add_item'));

        echo '</div>';
    }

}