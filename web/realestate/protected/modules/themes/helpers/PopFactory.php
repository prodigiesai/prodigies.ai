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

class PopFactory
{
    /**
     * @param $type
     * @param Themes|int $theme
     * @return PopCities|PopInfopages|PopLocations
     * @throws CHttpException
     */
    public static function getUnit($type, $theme)
    {
        switch ($type) {
            case PopUnit::TYPE_LOCATIONS:
                return new PopLocations($theme);

            case PopUnit::TYPE_CITIES:
                return new PopCities($theme);

            case PopUnit::TYPE_INFOPAGES:
                return new PopInfopages($theme);

            case PopUnit::TYPE_DEFAULT:
                return new PopDefault($theme);
        }
    }
}