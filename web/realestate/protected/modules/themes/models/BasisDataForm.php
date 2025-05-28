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

class BasisDataForm extends PopDataForm
{

    public $pd_title;
    public $pd_cities;
    public $loc_country;
    public $loc_region;
    public $loc_city;
    public $city_id;
    public $i_enable_slider_and_pd;
    public $i_enable_best_ads;
    public $i_enable_feature;
    public $i_enable_contact;
    public $i_enable_last_news;
    public $popular_dest_user_set;

    public $i_vk;
    public $i_facebook;
    public $i_twitter;

    public $i_lat;
    public $i_lng;
    public $i_zoom;

    public function rules()
    {
        return array(
            array('i_enable_slider_and_pd, i_enable_best_ads, i_enable_feature, i_enable_contact, i_enable_last_news, popular_dest_user_set', 'safe'),
            array('i_facebook, i_vk, i_twitter, popular_dest_type, hotObjTypeIds, hotLimit', 'safe'),
            array('i_lat, i_lng, i_zoom', 'numerical'),
        );
    }

    public function save(Themes $model)
    {
        foreach ($this->attributes as $attribute => $value) {
            if (isset($this->{$attribute}) && $this->{$attribute} !== null) {
                $model->setInJson($attribute, $value);
            }
        }
        return $model->saveJson();
    }
}
