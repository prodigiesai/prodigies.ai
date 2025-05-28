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

class CustomSort extends CSort
{

    public function url($attribute, $descending = false)
    {
        $directions = array($attribute => $descending);

        $url = $this->createUrl(Yii::app()->getController(), $directions);

        return $url;
    }

    public function label($label = null, $descending = false)
    {
        $label .= $descending ? ' &uarr;' : ' &darr;';

        return $label;
    }

    public function getDropDownSelected()
    {
        $selected = '';
        if (!empty($_GET[$this->sortVar])) {
            $selected = $_GET[$this->sortVar];
        }
        return $selected;
    }

    public function renderDropDownSorter($options)
    {
        //echo CHtml::openTag('div',array('class'=>$this->sorterCssClass))."\n";
        //echo $this->sorterHeader===null ? Yii::t('zii','Sort by: ') : $this->sorterHeader;

        echo CHtml::dropDownList(
            'sorter', $this->getDropDownSelected(), CHtml::listData($options, 'name', 'label'), array(
                'id' => 'drop-down-sorter',
                'class' => 'form-control',
                'onchange' => "dropDownSort($(this).val()); return false;", // Формирование ссылки для ajax сортировки
                'encode' => false
            )
        );

        $jsOptions = CJavaScript::encode(CHtml::listData($options, 'name', 'url'));

        $script = <<< JS
    
    var sorterOptions = $jsOptions;

    function dropDownSort(name) {
        if(typeof sorterOptions[name] != 'undefined'){
            reloadApartmentList(sorterOptions[name]);
        }
        return false;
    }
JS;
        Yii::app()->clientScript->registerScript('drop-down-sort', $script, CClientScript::POS_END);

        //echo $this->sorterFooter;
        //echo CHtml::closeTag('div');
    }
}
