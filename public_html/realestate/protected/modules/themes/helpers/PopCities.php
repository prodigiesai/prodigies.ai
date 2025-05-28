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

class PopCities extends PopUnit
{
    public static function getType()
    {
        return PopUnit::TYPE_CITIES;
    }

    public function getItemsId()
    {
        return $this->theme->getFromJson($this->getKeyItemsId());
    }

    public static function getModelByPk($id)
    {
        return ApartmentCity::model()->findByPk($id);
    }

    public function renderForm(PopDataForm $model)
    {
        $this->model = $model;

        $cities = ApartmentCity::getActiveCity();

        echo '<div class="clearfix"></div>';
        echo '<br/>';

        echo '<div class="form">';

        echo '<div class="form-group">';
        echo CHtml::activeLabelEx($this->model, 'city_id');
        echo Select2::activeDropDownList($this->model, 'city_id', $cities, array('id' => 'item_id', 'class' => 'span3 form-control'));
        echo CHtml::error($this->model, 'city_id');
        echo '</div>';

        echo '<br/>';
        echo AdminLteHelper::getLink(tc('Add'), 'javascript:;', 'fa fa-check', array('class' => 'btn btn-primary', 'id' => 'pd_add_item'));

        echo '</div>';
    }
}