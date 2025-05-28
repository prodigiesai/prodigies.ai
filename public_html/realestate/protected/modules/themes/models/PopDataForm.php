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

class PopDataForm extends CFormModel
{
    const UPLOAD_NOT = 1;
    const UPLOAD_BY_CRITERIA = 2;
    const UPLOAD_ALL = 3;

    public $pd_title;
    public $pd_cities;
    public $loc_country;
    public $loc_region;
    public $loc_city;
    public $city_id;

    public $info_id;

    public $i_enable_slider_and_pd;
    public $i_enable_best_ads;
    public $i_enable_feature;
    public $i_enable_contact;
    public $i_enable_last_news;

    public $popular_dest_type;
    public $pd_upload;

    public $i_vk;
    public $i_facebook;
    public $i_twitter;

    public $i_lat;
    public $i_lng;
    public $i_zoom;

    public $hotObjTypeIds;
    public $hotLimit;

    public function rules()
    {
        return array(
            array('i_enable_slider_and_pd, i_enable_best_ads, i_enable_feature, i_enable_contact, i_enable_last_news, popular_dest_user_set, info_id', 'safe'),
            array('i_facebook, i_vk, i_twitter, popular_dest_type', 'safe'),
            array('i_lat, i_lng, i_zoom', 'numerical'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'pd_title' => tc('Title'),
            'pd_cities' => tt('Cities'),
            'i_enable_slider_and_pd' => tt('Display widget "slider" and "popular directions"'),
            'i_enable_best_ads' => tt('Display widget "Best listings"'),
            'i_enable_feature' => tt('Display widget "Feature"'),
            'i_enable_contact' => tt('Display widget "Contact"'),
            'i_enable_last_news' => tt('Display widget "Last news"'),

            'i_vk' => tt('Link to group in vk'),
            'i_facebook' => tt('Link to group in facebook'),
            'i_twitter' => tt('Link to group in twitter'),

            'i_lat' => tt('Coordinates of the marker contacts, latitude'),
            'i_lng' => tt('Coordinates of the marker contacts, longitude'),
            'i_zoom' => tt('Zoom for contact map'),

            'loc_country' => tc('Country'),
            'loc_region' => tc('Region'),
            'loc_city' => tc('City'),

            'i_enable_pd' => tt('Display widget "Popular directions"'),
            'pd_upload' => tt('Upload listings'),
            'popular_dest_type' => tt('Objects for the widget'),

            'hotObjTypeIds' => tt('Object type', 'apartments'),
            'hotLimit' => tt('Limit object', 'apartments'),
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
