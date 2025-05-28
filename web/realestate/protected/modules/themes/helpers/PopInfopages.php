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

class PopInfopages extends PopUnit
{
    public $infopages;

    public static function getType()
    {
        return PopUnit::TYPE_INFOPAGES;
    }

    public function getItemsId()
    {
        return $this->theme->getFromJson($this->getKeyItemsId());
    }

    public static function getModelByPk($id)
    {
        return InfoPages::model()->findByPk($id);
    }

    public function renderForm(PopDataForm $model)
    {
        $this->model = $model;

        $this->infopages = InfoPages::getInfoPagesAddList();

        echo '<div class="clearfix"></div>';
        echo '<br/>';

        echo '<div class="form">';

        echo '<div class="form-group">';
        echo CHtml::activeLabelEx($this->model, 'info_id');
        echo Select2::activeDropDownList($this->model, 'info_id', $this->infopages, array('id' => 'item_id', 'class' => 'span3 form-control'));
        echo CHtml::error($this->model, 'info_id');
        echo '</div>';

        echo '<br/>';
        echo AdminLteHelper::getLink(tc('Add'), 'javascript:;', 'fa fa-check', array('class' => 'btn btn-primary', 'id' => 'pd_add_item'));

        echo '</div>';
    }
}