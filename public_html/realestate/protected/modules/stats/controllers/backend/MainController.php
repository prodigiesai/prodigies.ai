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

class MainController extends ModuleAdminController
{

    const LAST_DAYS = 7;

    public function accessRules()
    {
        return array(
            array('allow',
                'expression' => "Yii::app()->user->checkAccess('stats_admin')",
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionAdmin()
    {
        Yii::app()->user->setState('menu_active', 'stats.admin');

        $adminStatsBage = Yii::app()->controller->adminStatsBage;

        $newsProductItems = array();

        NewsProduct::getProductNews();
        $newsProduct = new NewsProduct;
        $newsProduct = $newsProduct->getAllWithPagination(null, 3);
        if (!empty($newsProduct['items']))
            $newsProductItems = $newsProduct['items'];

        $this->render('admin', array(
                'newsProductItems' => $newsProductItems,
                'adminStatsBage' => $adminStatsBage,
            )
        );
    }

    public function actionGraph()
    {
        Yii::app()->user->setState('menu_active', 'stats.graph');

        NewsProduct::getProductNews();

        $countNewsProduct = Yii::app()->controller->countNewsProduct;
        if ($countNewsProduct > 0) {
            Yii::app()->user->setFlash('info', Yii::t('common', 'There are new product news') . ': '
                . CHtml::link(Yii::t('common', '{n} news', $countNewsProduct), array('/entries/backend/main/product')));
        }

        if (demo())
            Yii::app()->user->setFlash('warning', tt('Generated_data', 'stats'));


        $periodArr = $resListings = $resPayments = $resUsers = $resComments = $resReviews = $searchDayString = array();
        $dataBookingRequests = $dataListings = $dataPayments = $dataUsers = $dataComments = $dataReviews = array();
        $maxValBookingRequests = $maxValListings = $maxValPayments = $maxValUsers = $maxValComments = $maxValReviews = 0;

        for ($i = 0; $i < self::LAST_DAYS; $i++) {
            $day = date("Y-m-d", strtotime('-' . $i . ' days'));
            $periodArr[] = $day;
            $searchDayString[] = 'date_created = "' . $day . '"';
        }

        $sql = 'SELECT COUNT(id) as count, STR_TO_DATE(date_created, "%Y-%m-%d") as date_created2 FROM {{booking_table}} GROUP BY date_created2 HAVING date_created2 >= CURDATE() - INTERVAL ' . self::LAST_DAYS . ' DAY ';
        $resBookingRequests = Yii::app()->db->createCommand($sql)->queryAll();
        if ($resBookingRequests && is_array($resBookingRequests))
            $resBookingRequests = CHtml::listData($resBookingRequests, 'date_created2', 'count');

        $sql = 'SELECT COUNT(id) as count, STR_TO_DATE(date_created, "%Y-%m-%d") as date_created2 FROM {{apartment}} WHERE active <> ' . Apartment::STATUS_DRAFT . ' GROUP BY date_created2 HAVING date_created2 >= CURDATE() - INTERVAL ' . self::LAST_DAYS . ' DAY';
        $resListings = Yii::app()->db->createCommand($sql)->queryAll();
        if ($resListings && is_array($resListings))
            $resListings = CHtml::listData($resListings, 'date_created2', 'count');

        if (Yii::app()->user->checkAccess('payment_admin') && issetModule('payment')) {
            $sql = 'SELECT COUNT(id) as count, STR_TO_DATE(date_created, "%Y-%m-%d") as date_created2 FROM {{payments}} WHERE status = ' . Payments::STATUS_PAYMENTCOMPLETE . ' GROUP BY date_created2 HAVING date_created2 >= CURDATE() - INTERVAL ' . self::LAST_DAYS . ' DAY';
            $resPayments = Yii::app()->db->createCommand($sql)->queryAll();
            if ($resPayments && is_array($resPayments))
                $resPayments = CHtml::listData($resPayments, 'date_created2', 'count');
        }

        $sql = 'SELECT COUNT(id) as count, STR_TO_DATE(date_created, "%Y-%m-%d") as date_created2 FROM {{users}} WHERE active = 1 GROUP BY date_created2 HAVING date_created2 >= CURDATE() - INTERVAL ' . self::LAST_DAYS . ' DAY';
        $resUsers = Yii::app()->db->createCommand($sql)->queryAll();
        if ($resUsers && is_array($resUsers))
            $resUsers = CHtml::listData($resUsers, 'date_created2', 'count');

        $sql = 'SELECT COUNT(id) as count, STR_TO_DATE(date_created, "%Y-%m-%d") as date_created2 FROM {{comments}} GROUP BY date_created2 HAVING date_created2 >= CURDATE() - INTERVAL ' . self::LAST_DAYS . ' DAY';
        $resComments = Yii::app()->db->createCommand($sql)->queryAll();
        if ($resComments && is_array($resComments))
            $resComments = CHtml::listData($resComments, 'date_created2', 'count');


        $sql = 'SELECT COUNT(id) as count, STR_TO_DATE(date_created, "%Y-%m-%d") as date_created2 FROM {{reviews}} GROUP BY date_created2 HAVING date_created2 >= CURDATE() - INTERVAL ' . self::LAST_DAYS . ' DAY';
        $resReviews = Yii::app()->db->createCommand($sql)->queryAll();
        if ($resReviews && is_array($resReviews))
            $resReviews = CHtml::listData($resReviews, 'date_created2', 'count');


        foreach ($periodArr as $day) {
            if (demo())
                $value = mt_rand(0, 10);
            else
                $value = array_key_exists($day, $resBookingRequests) ? (int)$resBookingRequests[$day] : 0;

            $maxValBookingRequests = ($maxValBookingRequests < $value) ? $value : $maxValBookingRequests;

            // Yii::app()->dateFormatter->format(Yii::app()->locale->getDateFormat('long'), CDateTimeParser::parse($day, 'yyyy-MM-dd'));
            $dataBookingRequests[] = array(
                Yii::app()->dateFormatter->format('d MMMM', CDateTimeParser::parse($day, 'yyyy-MM-dd')),
                $value
            );
        }


        foreach ($periodArr as $day) {
            if (demo())
                $value = mt_rand(0, 10);
            else
                $value = array_key_exists($day, $resListings) ? (int)$resListings[$day] : 0;

            $maxValListings = ($maxValListings < $value) ? $value : $maxValListings;

            $dataListings[] = array(
                Yii::app()->dateFormatter->format('d MMMM', CDateTimeParser::parse($day, 'yyyy-MM-dd')),
                $value
            );
        }

        if (Yii::app()->user->checkAccess('payment_admin') && issetModule('payment')) {
            foreach ($periodArr as $day) {
                if (demo())
                    $value = mt_rand(0, 7);
                else
                    $value = array_key_exists($day, $resPayments) ? (int)$resPayments[$day] : 0;

                $maxValPayments = ($maxValPayments < $value) ? $value : $maxValPayments;

                $dataPayments[] = array(
                    Yii::app()->dateFormatter->format('d MMMM', CDateTimeParser::parse($day, 'yyyy-MM-dd')),
                    $value
                );
            }
        }

        foreach ($periodArr as $day) {
            if (demo())
                $value = mt_rand(0, 5);
            else
                $value = array_key_exists($day, $resUsers) ? (int)$resUsers[$day] : 0;

            $maxValUsers = ($maxValUsers < $value) ? $value : $maxValUsers;

            $dataUsers[] = array(
                Yii::app()->dateFormatter->format('d MMMM', CDateTimeParser::parse($day, 'yyyy-MM-dd')),
                $value
            );
        }

        foreach ($periodArr as $day) {
            if (demo())
                $value = mt_rand(0, 3);
            else
                $value = array_key_exists($day, $resComments) ? (int)$resComments[$day] : 0;

            $maxValComments = ($maxValComments < $value) ? $value : $maxValComments;

            $dataComments[] = array(
                Yii::app()->dateFormatter->format('d MMMM', CDateTimeParser::parse($day, 'yyyy-MM-dd')),
                $value
            );
        }

        foreach ($periodArr as $day) {
            if (demo())
                $value = mt_rand(0, 2);
            else
                $value = array_key_exists($day, $resReviews) ? (int)$resReviews[$day] : 0;

            $maxValReviews = ($maxValReviews < $value) ? $value : $maxValReviews;

            $dataReviews[] = array(
                Yii::app()->dateFormatter->format('d MMMM', CDateTimeParser::parse($day, 'yyyy-MM-dd')),
                $value
            );
        }

        $this->render('graph', array(
                'dataBookingRequests' => $dataBookingRequests,
                'dataListings' => $dataListings,
                'dataPayments' => $dataPayments,
                'dataUsers' => $dataUsers,
                'dataComments' => $dataComments,
                'dataReviews' => $dataReviews,
                'maxValBookingRequests' => $this->normalizeMaxValue($maxValBookingRequests),
                'maxValListings' => $this->normalizeMaxValue($maxValListings),
                'maxValPayments' => $this->normalizeMaxValue($maxValPayments),
                'maxValUsers' => $this->normalizeMaxValue($maxValUsers),
                'maxValComments' => $this->normalizeMaxValue($maxValComments),
                'maxValReviews' => $this->normalizeMaxValue($maxValReviews),
            )
        );
    }

    public function normalizeMaxValue($value)
    {
        if ($value < 10)
            return $value + 1;
        else
            return round($value + ($value / 10));
    }
}
