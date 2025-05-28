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

class JsonBehavior extends CActiveRecordBehavior
{

    /** @var  array decoded data */
    private $cacheJson;
    public $jsonField = 'json_data';

    /**
     * @param $key
     * @param $value
     * @param  bool  $save
     * @return bool
     */
    public function setInJson($key, $value, $save = false)
    {
        $this->loadCache();
        $this->cacheJson[$key] = $value;
        if ($save) {
            return $this->saveJson();
        } else {
            $this->getOwner()->{$this->jsonField} = CJSON::encode($this->cacheJson);
        }
        return true;
    }

    /**
     * @param $key
     * @param  null  $default
     * @return mixed|null
     */
    public function getFromJson($key, $default = NULL)
    {
        $this->loadCache();
        return isset($this->cacheJson[$key]) ? $this->cacheJson[$key] : $default;
    }

    /**
     * @param $key
     * @param  bool  $save
     * @return bool
     */
    public function deleteInJson($key, $save = true)
    {
        $this->loadCache();
        if (isset($this->cacheJson[$key])) {
            unset($this->cacheJson[$key]);
            if ($save) {
                $this->saveJson();
            }
        }
        return true;
    }

    private function loadCache()
    {
        if (!$this->cacheJson) {
            $this->cacheJson = $this->getOwner()->{$this->jsonField} ? CJSON::decode($this->getOwner()->{$this->jsonField}) : array();
        }
    }

    public function saveJson()
    {
        $this->getOwner()->{$this->jsonField} = CJSON::encode($this->cacheJson);
        if ($this->getOwner()->save(true, array($this->jsonField))) {
            return true;
        }
        //logs($this->getOwner()->errors);
        return false;
    }
}
