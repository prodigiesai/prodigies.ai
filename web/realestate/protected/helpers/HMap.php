<?php

class HMap
{
    private $options = array(
        'lat' => 55.76,
        'lng' => 37.64,
        'zoom' => 11,
        'draggable' => false,
        'scrollWheel' => false,
    );

    public function __construct($options = array())
    {
        if ($options && is_array($options)) {
            $this->options = CArray::merge($this->options, $options);
        }
    }

    public function createMapWithMarker()
    {
        if (param('useGoogleMap', 1)) {
            $this->googleMap();
        } elseif (param('useYandexMap', 1)) {
            $this->yandexMap();
        } elseif (param('useOSMMap', 1)) {
            $this->OSMMap();
        }
    }

    public function googleMap()
    {
        $jsCode = '
		var LatLngMarker = new google.maps.LatLng(' . $this->getOption('lat', 55.76) . ', ' . $this->getOption('lng', 37.64) . ');
			
		var HMap = new google.maps.Map(document.getElementById("contactMap"), {
			zoom: ' . $this->getOption('zoom', 8) . ',
			center: LatLngMarker,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			maxZoom: 17,
			scrollwheel: "' . ($this->getOption('scrollWheel', false)) . '",
			draggable: "' . ($this->getOption('draggable', false)) . '"
		});
		
		var marker = new google.maps.Marker({
          position: LatLngMarker,
          map: HMap
        });
		';

        echo CHtml::tag('div', array('id' => 'contactMap'), '', true);

        if(CustomGMap::$loadScript == false){
            $js1 = 'https://maps.google.com/maps/api/js?v=3&key=' . param('googleMapApiKey') . '&callback=initGmap2&language=' . Yii::app()->language;
            $jsVars = "\n loadScript('$js1', true);\n";
            echo CHtml::script(PHP_EOL . $jsVars . PHP_EOL . 'function initGmap2() { ' . $jsCode . ' }');
        } else {
            echo CHtml::script(PHP_EOL . '$(window).load(function () { ' . $jsCode . ' });' . PHP_EOL);
        }

    }

    public function yandexMap()
    {
        $jsCode = '
            ymaps.ready(function () {
                var myMap = new ymaps.Map("contactMap", {
					center: [' . $this->getOption('lat', 55.76) . ', ' . $this->getOption('lng', 37.64) . '],
					zoom: ' . $this->getOption('zoom', 8) . '
				});
                    
                var myPlacemark = new ymaps.Placemark([' . $this->getOption('lat', 55.76) . ', ' . $this->getOption('lng', 37.64) . ']);
                //, { hintContent: \'Москва!\', balloonContent: \'Столица России\' }
                
                myMap.geoObjects.add(myPlacemark);
            });
        ';

        echo CHtml::tag('div', array('id' => 'contactMap'), '', true);

        Yii::app()->getClientScript()->registerScriptFile(
            'https://api-maps.yandex.ru/2.1/?load=package.full&lang=' . CustomYMap::getLangForMap(), CClientScript::POS_END);

        Yii::app()->getClientScript()->registerScript('hmap', $jsCode, CClientScript::POS_READY);
    }

    public function OSMMap()
    {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/OpenLayers.js', CClientScript::POS_BEGIN);

        $jsCode = '
            var map = new OpenLayers.Map("contactMap", {theme: null});
            map.addLayer(new OpenLayers.Layer.OSM(
            "OpenStreetMap", 
            // Official OSM tileset as protocol-independent URLs
            [
                "//a.tile.openstreetmap.org/${z}/${x}/${y}.png",
                "//b.tile.openstreetmap.org/${z}/${x}/${y}.png",
                "//c.tile.openstreetmap.org/${z}/${x}/${y}.png"
            ], 
            {tileOptions: 
               {crossOriginKeyword: null}
            }));
        
            var size = new OpenLayers.Size(32,32);
            var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
            var icon = new OpenLayers.Icon("' . Yii::app()->request->baseUrl . '/images/maps/map-marker.png", size, offset);

            var lonLat = new OpenLayers.LonLat( ' . $this->getOption('lng', 37.64) . ', ' . $this->getOption('lat', 55.76) . ')
                  .transform(
                    new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                    map.getProjectionObject() // to Spherical Mercator Projection
                  );
                  
            var zoom = ' . $this->getOption('zoom', 8) . ';
        
            var markers = new OpenLayers.Layer.Markers( "Markers" );            
            map.addLayer(markers);
                        
            markers.addMarker(new OpenLayers.Marker(lonLat, icon));
            
            map.setCenter (lonLat, zoom);
        ';

        echo CHtml::tag('div', array('id' => 'contactMap'), '', true);
        echo CHtml::script(PHP_EOL . '$(function(){' . $jsCode . '});');
    }

    public function getOption($key, $default = '')
    {
        return (isset($this->options[$key]) && !empty($this->options[$key])) ? $this->options[$key] : $default;
    }
}