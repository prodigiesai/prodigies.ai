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
namespace application\modules\apartments\helpers;

use Yii;
use CDbCriteria;
use CustomSort;
use Apartment;
use CMap;
use HApartment;

class ApartmentsHelper
{
    public static function getApartments($limit = 10, $usePagination = 1, $all = 1, $criteria = null, $showChild = false)
    {
        $pages = array();

        Yii::app()->getModule('apartments');

        if ($criteria === null)
            $criteria = new CDbCriteria;

        if (!$all) {
            $criteria->addCondition('t.deleted = 0');
            $criteria->addCondition('t.active = ' . Apartment::STATUS_ACTIVE);
            $criteria->addCondition('t.owner_active = 1');
        }

        $sort = new CustomSort('Apartment');
        $sort->attributes = array(
            'price' => array('asc' => 'price', 'desc' => 'price DESC', 'default' => 'desc'),
            'date_created' => array('asc' => 'date_created', 'desc' => 'date_created DESC', 'default' => 'desc'),
            'rating' => array('asc' => 'rating', 'desc' => 'rating DESC', 'default' => 'desc'),
        );

        if (!$criteria->order)
            $sort->defaultOrder = 't.date_up_search DESC, t.sorter DESC';
        $sort->applyOrder($criteria);

        if (issetModule('seasonalprices')) {
            $criteria->with = CMap::mergeArray($criteria->with, array('seasonalPrices'));

            if ($criteria->order) {
                if ($criteria->order == '`t`.`price`') {
                    $criteria->order = 't.price, seasonalPrices_sort_asc.price';
                    $criteria->with = CMap::mergeArray($criteria->with, array('seasonalPrices_sort_asc'));
                }
                if ($criteria->order == '`t`.`price` DESC') {
                    $criteria->order = 't.price DESC, seasonalPrices_sort_desc.price DESC';
                    $criteria->with = CMap::mergeArray($criteria->with, array('seasonalPrices_sort_desc'));
                }
            }
        }

        $sorterLinks = self::getSorterLinks($sort);

        $criteria->addInCondition('t.type', HApartment::availableApTypesIds());
        //$criteria->addInCondition('t.price_type', array_keys(HApartment::getPriceArray(Apartment::PRICE_SALE, true)));
        $criteria->addCondition('(t.price_type IN (' . implode(',', array_keys(HApartment::getPriceArray(Apartment::PRICE_SALE, true))) . ') OR t.is_price_poa = 1)');

        if ($showChild == false) {
            $listExclude = \ApartmentObjType::getListExclude('search');
            if ($listExclude) {
                $criteria->addNotInCondition('t.obj_type_id', $listExclude);
            }
        }

        // find count
        $apCount = Apartment::model()->cache(param('cachingTime', 86400), Apartment::getDependency())->count($criteria);

        if ($usePagination && $limit) {
            $pages = new \CPagination($apCount);
            $pages->pageSize = $limit;
            $pages->applyLimit($criteria);
        } else {
            if ($limit)
                $criteria->limit = $limit;
        }

        if (issetModule('seo')) {
            $criteria->with = CMap::mergeArray($criteria->with, array('seo'));
        }

        return array(
            'pages' => $pages,
            'sorterLinks' => $sorterLinks,
            'sorter' => $sort,
            'apCount' => $apCount,
            'criteria' => $criteria
        );
    }

    public static function getSorterLinks(CustomSort $sort)
    {
        $HtmlOption = array('onClick' => 'reloadApartmentList(this.href); return false;', 'class' => 'sorter-link btn btn-light');
        $return = array(
            $sort->link('price', tt('Sorting by price', 'quicksearch'), $HtmlOption),
            $sort->link('date_created', tt('Sorting by date created', 'quicksearch'), $HtmlOption),
            $sort->link('rating', tt('Sorting by rating', 'quicksearch'), $HtmlOption),
        );
        return $return;
    }

    public static function getSorterOptions(CustomSort $sort)
    {
        return array(
            array(
                'name' => 'not',
                'label' => tt('Sorting by default', 'quicksearch'),
                'url' => $sort->url('not')
            ),
            array(
                'name' => 'price',
                'label' => $sort->label(tt('Sorting by price', 'quicksearch')),
                'url' => $sort->url('price')
            ),
            array(
                'name' => 'price.desc',
                'label' => $sort->label(tt('Sorting by price', 'quicksearch'), true),
                'url' => $sort->url('price', true)
            ),
            array(
                'name' => 'date_created',
                'label' => $sort->label(tt('Sorting by date created', 'quicksearch')),
                'url' => $sort->url('date_created')
            ),
            array(
                'name' => 'date_created.desc',
                'label' => $sort->label(tt('Sorting by date created', 'quicksearch'), true),
                'url' => $sort->url('date_created', true)
            ),
            array(
                'name' => 'rating',
                'label' => $sort->label(tt('Sorting by rating', 'quicksearch')),
                'url' => $sort->url('rating')
            ),
            array(
                'name' => 'rating.desc',
                'label' => $sort->label(tt('Sorting by rating', 'quicksearch'), true),
                'url' => $sort->url('rating', true)
            ),
        );
    }
}
