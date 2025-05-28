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

class PopUnitItem
{
    protected $item;

    public function getId()
    {
        return $this->item->id;
    }

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function getTitle($link = false)
    {
        $title = $url = '';

        switch (true) {
            case $this->item instanceof InfoPages:
                $title = $this->item->getTitle();
                $url = $this->item->getUrl();
                break;

            case $this->item instanceof City:
            case $this->item instanceof ApartmentCity:
                $title = $this->item->getName();
                $url = HSeo::getCityUrlById($this->item->id);
                break;
        }

        if ($link) {
            return CHtml::link($title, $url);
        } else {
            return $title;
        }
    }

    public function getEditUrl()
    {
        switch (true) {
            case $this->item instanceof InfoPages:
                return Yii::app()->createUrl('/infopages/backend/main/update', array('id' => $this->item->id));
                break;

            case $this->item instanceof City:
                return Yii::app()->createUrl('/location/backend/city/update', array('id' => $this->item->id));
                break;

            case $this->item instanceof ApartmentCity:
                return Yii::app()->createUrl('/apartmentCity/backend/main/update', array('id' => $this->item->id));
                break;
        }

        return 'hz';
    }

    public function getImageSrc($type = 'thumb', $width = 640, $height = 440)
    {
        if ($type == 'thumb') {
            return (isset($this->item->image) && $this->item->image) ? $this->item->image->getSmallThumbLink() : Yii::app()->baseUrl . '/uploads/150x100_no_photo_img.png';
        } else {
            return (isset($this->item->image) && $this->item->image) ? $this->item->image->getThumbHref($width, $height) : Yii::app()->baseUrl . '/uploads/150x100_no_photo_img.png';
        }
    }

    public function getListInline()
    {
        $objHtm = '';

        switch (true) {
            case $this->item instanceof InfoPages:
                $objHtm = '<ul class="list-inline">';
                $objHtm .= '<li>' . truncateText($this->item->getBody()) . '</li>';
                $objHtm .= '</ul>';
                break;

            case $this->item instanceof City:
            case $this->item instanceof ApartmentCity:
                $objTypesList = ApartmentObjType::getListForSearch();

                $objHtm = '<ul class="list-inline">';
                foreach ($objTypesList as $objTypeId => $objTypeName) {
                    $link = HSeo::getCityObjTypeLinkById($this->getId(), $objTypeId);

                    if (!$link) {
                        $url = Yii::app()->createUrl('/search', array('objType' => $objTypeId, 'city[]' => $this->getId()));
                        $link = CHtml::link($objTypeName, $url);
                    }
                    $objHtm .= '<li>' . $link . '</li>';
                }
                $objHtm .= '</ul>';
                break;
        }

        return $objHtm;
    }

}