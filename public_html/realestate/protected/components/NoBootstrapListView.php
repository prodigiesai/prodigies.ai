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


Yii::import('zii.widgets.CListView');

class NoBootstrapListView extends CListView
{

    public $template = "{summary}\n{pager}<br />\n{items}\n{pager}";
    //public $template="{summary}\n{items}\n{pager}";

    public $type = 'striped bordered condensed';

    public function init()
    {
        $this->pager = array(
            'class' => 'itemPaginator'
        );

        if (Yii::app()->theme->name == Themes::THEME_ATLAS_NAME) {
            $this->pager = array(
                'class' => 'itemPaginatorAtlas',
                'header' => '',
                'selectedPageCssClass' => 'current',
                'htmlOptions' => array(
                    'class' => ''
                )
            );

            $this->pagerCssClass = 'pagination';
        }

        parent::init();
    }
}
