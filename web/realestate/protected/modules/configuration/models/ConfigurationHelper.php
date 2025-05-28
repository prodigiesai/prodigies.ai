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

class ConfigurationHelper
{
    public static function getHintForParam($key, $default = null)
    {
        $hintList = array(
            'module_apartments_ymapApiKey' => array(
                'ru' => 'Получить API-ключ можно в <a href="https://developer.tech.yandex.ru/?from=club" target="_blank" rel="noopener noreferrer">Кабинете разработчика</a>. Нажмите "Получить ключ", затем выберите сервис "JavaScript API и HTTP Геокодер" и заполните анкету — ваш API-ключ будет сразу готов к использованию.',
                'default' => '<a href="https://developer.tech.yandex.ru/?from=club" target="_blank" rel="noopener noreferrer">' . tc('Get API Key') . '</a>',
            ),
            'googleMapApiKey' => array(
                'default' => '<a href="https://developers.google.com/maps/documentation/geocoding/get-api-key" target="_blank" rel="noopener noreferrer">' . tc('Get API Key') . '</a>',
            )
        );

        return isset($hintList[$key][Yii::app()->language]) ? $hintList[$key][Yii::app()->language] : (
        isset($hintList[$key]['default']) ? $hintList[$key]['default'] : $default
        );
    }
}