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

Yii::import('bootstrap.widgets.BsAlert');

class CustomBsAlert extends BsAlert
{
    public function init()
    {
        if (!isset($this->htmlOptions['id']))
            $this->htmlOptions['id'] = $this->getId();

        if (is_string($this->alerts))
            $this->alerts = array($this->alerts);

        if (!isset($this->alerts))
            $this->alerts = array('error', BsHtml::ALERT_COLOR_DANGER, BsHtml::ALERT_COLOR_ERROR, BsHtml::ALERT_COLOR_INFO,
                BsHtml::ALERT_COLOR_SUCCESS, BsHtml::ALERT_COLOR_WARNING);
    }

    public function run()
    {
        $id = $this->htmlOptions['id'];

        foreach ($this->alerts as $type) {
            if (Yii::app()->user->hasFlash($type)) {
                $alertFunction = $this->block ? 'blockAlert' : 'alert';

                $validTypes = array('error', BsHtml::ALERT_COLOR_DANGER, BsHtml::ALERT_COLOR_ERROR, BsHtml::ALERT_COLOR_INFO,
                    BsHtml::ALERT_COLOR_SUCCESS, BsHtml::ALERT_COLOR_WARNING);

                if (in_array($type, $validTypes))
                    $alertColor = in_array($type, $validTypes) ? $type : BsHtml::ALERT_COLOR_DEFAULT;

                if ($type == 'error') {
                    $alertColor = 'danger';
                }

                if ($this->closeText !== false)
                    $this->htmlOptions['closeText'] = $this->closeText;

                echo BsHtml::alert($type, Yii::app()->user->getFlash($type), $this->htmlOptions);
            }
        }
    }
}
