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

Yii::import('bootstrap.widgets.BsListView');

class CustomListView extends BsListView
{

    //public $pager=array('class'=>'itemPaginator');
    public $template = "{pager}\n{sorter}\n{items}";
    public $type = 'striped bordered condensed';
    public $pager = array('class' => 'bootstrap.widgets.BsPager');

}
