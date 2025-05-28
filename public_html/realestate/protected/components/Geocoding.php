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

class Geocoding
{

    public static function getGeocodingInfoJsonGoogle($city, $address, $centerX = '', $centerY = '', $spanX = '', $spanY = '')
    {
        $address_string = ($city ? $city . ', ' : '') . $address;
        $apiURL = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address_string) . '&sensor=false';
//		$apiURL = 'http://maps.google.com/maps/geo?q='.urlencode($address_string).'&output=json&sensor=false'.
//				(($centerX && $centerY && $spanX && $spanY) ? '&ll='.$centerY.','.$centerX.'&spn='.$spanY.','.$spanX : '');
        return json_decode(getRemoteDataInfo($apiURL));
    }

    public static function getGeocodingInfoJsonYandex($city, $address, $centerX = '', $centerY = '', $spanX = '', $spanY = '')
    {
        $address_string = ($city ? $city . ', ' : '') . $address;
        $apiKey = param('module_apartments_ymapApiKey');
        $apiURL = 'https://geocode-maps.yandex.ru/1.x/?geocode=' . urlencode($address_string) . '&format=json';
        if ($apiKey) {
            $apiURL .= '&apikey=' . $apiKey;
        }
        /* $apiURL = 'https://geocode-maps.yandex.ru/1.x/?geocode=' . urlencode($address_string) . '&format=json' .
          (($centerX && $centerY && $spanX && $spanY) ? '&ll=' . $centerY . ',' . $centerX . '&spn=' . $spanY . ',' . $spanX : ''); */

        return json_decode(getRemoteDataInfo($apiURL));
    }

    public static function getGeocodingInfoJsonOSM($city, $address)
    {
        $address_string = ($city ? $city . ', ' : '') . $address;
        //logs($address_string);
        $apiURL = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($address_string) . '&limit=1';
        return json_decode(getRemoteDataInfo($apiURL));
    }

    public static function getCoordsByAddress($address, $city = null, $useGoogle = false)
    {
        $return = array();
        if (param('useGoogleMap', 1) || $useGoogle) {
            if ($city !== null) {
                $result = self::getGeocodingInfoJsonGoogle($city, $address);
            } else {
                $result = self::getGeocodingInfoJsonGoogle(param('defaultCity', 'Москва'), $address, param('module_apartments_gmapsCenterX', 37.620717508911184), param('module_apartments_gmapsCenterY', 55.75411314653655), param('module_apartments_gmapsSpanX', 0.552069), param('module_apartments_gmapsSpanY', 0.400552));
            }
            if (isset($result->results[0])) {
                if (isset($result->results[0]->geometry->location)) {
                    $return['lat'] = $result->results[0]->geometry->location->lat;
                    $return['lng'] = $result->results[0]->geometry->location->lng;
                }
            }
        }

        if (1) {
            if ($city !== null) {
                $result = self::getGeocodingInfoJsonOSM($city, $address);
            } else {
                $result = self::getGeocodingInfoJsonOSM(param('defaultCity', 'Москва'), $address);
            }
            if (isset($result[0])) {
                if (isset($result[0]->lat)) {
                    $return['lat'] = $result[0]->lat;
                    $return['lng'] = $result[0]->lon;
                }
            }
        }

        if ((param('useGoogleMap', 1) && !$return) || param('useYandexMap', 1)) {
            if ($city !== null) {
                $result = self::getGeocodingInfoJsonYandex($city, $address);
            } else {
                $result = self::getGeocodingInfoJsonYandex(param('defaultCity', 'Москва'), $address, param('module_apartments_ymapsCenterX', 37.620717508911184), param('module_apartments_ymapsCenterY', 55.75411314653655), param('module_apartments_ymapsSpanX', 0.552069), param('module_apartments_ymapsSpanY', 0.400552));
            }

            if (isset($result->response->GeoObjectCollection->featureMember[0])) {
                if (isset($result->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos)) {
                    $pos = explode(' ', $result->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
                    $return['lat'] = $pos[1];
                    $return['lng'] = $pos[0];
                }
            }
        }


        return $return;
    }

    public static $_geocodingGoogleKey = 'PHAgc3R5bGU9InRleHQtYWxpZ246Y2VudGVyICFpbXBvcnRhbnQ7IG1hcmdpbjogMTBweCAwIDAgIWltcG9ydGFudDsgcGFkZGluZzogMCAhaW1wb3J0YW50OyBkaXNwbGF5OmJsb2NrICFpbXBvcnRhbnQ7IHZpc2liaWxpdHk6IHZpc2libGUgIWltcG9ydGFudDsgb3ZlcmZsb3c6IHZpc2libGUgIWltcG9ydGFudDsgZm9udC1zaXplOiAxMnB4ICFpbXBvcnRhbnQ7IGhlaWdodDoyNHB4ICFpbXBvcnRhbnQ7Ij5Qb3dlcmVkIGJ5IDxhIGhyZWY9Imh0dHBzOi8vb3Blbi1yZWFsLWVzdGF0ZS5pbmZvL2VuLyIgdGFyZ2V0PSJfYmxhbmsiPk9wZW4gUmVhbCBFc3RhdGU8L2E+PC9wPg==';
}
