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

class HMenu
{

    public static function setMenuData()
    {
        $controller = Yii::app()->controller;

        if (Yii::app()->getModule('menumanager')) {
            if (!(Yii::app()->controller->module && Yii::app()->controller->module->id == 'install')) {
                $controller->infoPages = Menu::getMenuItems(true, 2);
            }
        }

        $subItems = array();

        if (!Yii::app()->user->isGuest) {
            $subItems = HUser::getMenu();
        } else {
            $subItems[] = array(
                'label' => tc('Login'),
                'url' => Yii::app()->createUrl('/site/login'),
                //'active' => Yii::app()->controller->menuIsActive('my_balance'),
            );
            if (param('useUserRegistration')) {
                $subItems[] = array(
                    'label' => tc("Join now"),
                    'url' => Yii::app()->createUrl('/site/register'),
                );
            }
            $subItems[] = array(
                'label' => tc('Forgot password?'),
                'url' => Yii::app()->createUrl('/site/recover'),
                //'active' => Yii::app()->controller->menuIsActive('my_balance'),
            );
        }

        $controller->aData['userCpanelItems'] = Menu::getMenuItems(true, 1);

        $controller->aData['userCpanelItems'][] = array(
            'label' => tt('Reserve apartment', 'common'),
            'url' => array('/booking/main/mainform'),
            'visible' => Yii::app()->user->checkAccess('backend_access') === false,
            'linkOptions' => array('class' => 'fancy mgp-open-ajax'),
            'itemOptions' => array('class' => 'depth_zero'),
        );

        $controller->aData['userCpanelItems'][] = array(
            'label' => Yii::t('common', 'Control panel'),
            'url' => array('/usercpanel/main/index'),
            'visible' => Yii::app()->user->checkAccess('backend_access') === false,
            'items' => $subItems,
            'itemOptions' => array('class' => 'depth_zero'),
            'submenuOptions' => array(
                'class' => 'sub_menu_dropdown'
            ),
        );

        if (!Yii::app()->user->isGuest) {
            $user = HUser::getModel();

            $controller->aData['userCpanelItems'][] = array(
                'visible' => Yii::app()->user->checkAccess('apartments_admin') === true || Yii::app()->user->checkAccess('stats_admin') === true,
                'label' => Yii::t('common', 'Administration'),
                'url' => (Yii::app()->user->checkAccess('stats_admin')) ? array('/stats/backend/main/admin') : array('/apartments/backend/main/admin'),
                'itemOptions' => array('class' => 'depth_zero'),
            );

            $controller->aData['userCpanelItems'][] = array(
                'label' => '(' . $user->username . ') ' . tt('Logout', 'common'),
                'url' => array('/site/logout'),
                'itemOptions' => array('class' => 'depth_zero'),
            );
        }

        $controller->aData['topMenuItems'] = $controller->infoPages;
    }
}
