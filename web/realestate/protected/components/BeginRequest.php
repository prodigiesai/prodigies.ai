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

class BeginRequest
{

    const TIME_UPDATE = 21600;
    const TIME_UPDATE_TARIFF_PLANS = 43200;

    public static function updateStatusAd()
    {
        if (Yii::app()->request->isAjaxRequest)
            return false;

        if (!oreInstall::isInstalled())
            return false;

        $data = Yii::app()->statePersister->load();

        // Обновляем статусы 4 раза в сутки
        if (isset($data['next_check_status'])) {
            if ($data['next_check_status'] < time()) {
                $data['next_check_status'] = time() + self::TIME_UPDATE;
                Yii::app()->statePersister->save($data);

                if (issetModule('paidservices')) {
                    self::checkStatusAd();
                }

                self::clearDrafts();
                self::clearApartmentsStats();
                self::clearUsersSessions();
                self::checkDateEndActivity();
                self::deleteIPFromBlocklist();
                self::clearGuestAdImagesTemp();
                self::clearSpecialOffers();

                if (issetModule('seo')) {
                    self::clearSeoDuplicates();
                }

                if (issetModule('historyChanges')) {
                    self::clearHistoryChangesTable();
                }
            }
        } else {
            $data['next_check_status'] = time() + self::TIME_UPDATE;
            Yii::app()->statePersister->save($data);

            if (issetModule('paidservices')) {
                self::checkStatusAd();
            }

            self::clearDrafts();
            self::clearApartmentsStats();
            self::clearUsersSessions();
            self::checkDateEndActivity();
            self::deleteIPFromBlocklist();
            self::clearGuestAdImagesTemp();
            self::clearSpecialOffers();

            if (issetModule('seo')) {
                self::clearSeoDuplicates();
            }

            if (issetModule('historyChanges')) {
                self::clearHistoryChangesTable();
            }
        }

        // Тарифные планы и курсы валют - 2 раза в сутки
        if (issetModule('tariffPlans') && issetModule('paidservices')) {
            if (isset($data['next_check_status_users_tariffs'])) {
                if ($data['next_check_status_users_tariffs'] < time()) {
                    $data['next_check_status_users_tariffs'] = time() + self::TIME_UPDATE_TARIFF_PLANS;
                    Yii::app()->statePersister->save($data);

                    self::checkTariffPlansUsers();

                    if (issetModule('paidservices')) {
                        Currency::model()->parseExchangeRates();
                    }
                }
            } else {
                $data['next_check_status_users_tariffs'] = time() + self::TIME_UPDATE_TARIFF_PLANS;
                Yii::app()->statePersister->save($data);

                self::checkTariffPlansUsers();

                if (issetModule('paidservices')) {
                    Currency::model()->parseExchangeRates();
                }
            }
        }
    }

    public static function clearSpecialOffers()
    {
        $activeOffers = Apartment::model()->findAll('is_special_offer = 1 AND `is_free_to` IS NOT NULL AND DATE_FORMAT(`is_free_to`, "%Y-%m-%d") < DATE_FORMAT( NOW(), "%Y-%m-%d")');
        foreach ($activeOffers as $apartment) {
            $apartment->is_special_offer = 0;
            $apartment->is_free_to = NULL;
            $apartment->update(array('is_special_offer', 'is_free_to'));
        }
    }

    public static function checkStatusAd()
    {
        $activePaids = ApartmentPaid::model()->findAll('date_end <= NOW() AND status=' . ApartmentPaid::STATUS_ACTIVE);

        foreach ($activePaids as $paid) {
            $paid->status = ApartmentPaid::STATUS_NO_ACTIVE;

            if ($paid->paid_id == PaidServices::ID_SPECIAL_OFFER || $paid->paid_id == PaidServices::ID_UP_IN_SEARCH) {
                $apartment = Apartment::model()->findByPk($paid->apartment_id);

                if ($apartment) {
                    $apartment->scenario = 'update_status';

                    if ($paid->paid_id == PaidServices::ID_SPECIAL_OFFER) {
                        $apartment->is_special_offer = 0;
                        $apartment->is_free_to = NULL;
                        $apartment->update(array('is_special_offer', 'is_free_to'));
                    }

                    if ($paid->paid_id == PaidServices::ID_UP_IN_SEARCH) {
                        $apartment->date_up_search = new CDbExpression('NULL');
                        $apartment->update(array('date_up_search'));
                    }
                }
            }

            if (!$paid->update(array('status'))) {
                //deb($paid->getErrors());
            }
        }
    }

    public static function clearApartmentsStats()
    {
        $sql = 'DELETE FROM {{apartment_statistics}} WHERE date_created < (NOW() - INTERVAL 8 DAY)';
        Yii::app()->db->createCommand($sql)->execute();
    }

    public static function clearUsersSessions()
    {
        $sql = 'DELETE FROM {{users_sessions}} WHERE expire < (UNIX_TIMESTAMP(NOW() - INTERVAL 1 DAY))';
        Yii::app()->db->createCommand($sql)->execute();
    }

    public static function checkDateEndActivity()
    {
        $sql = 'SELECT a.id, a.owner_id FROM {{apartment}} a '
            . ' WHERE a.date_end_activity <= NOW() AND a.activity_always != 1 '
            . ' AND (a.active=' . Apartment::STATUS_ACTIVE . ' OR a.owner_active=' . Apartment::STATUS_ACTIVE . ') AND a.active <> ' . Apartment::STATUS_DRAFT;
        $adEndActivity = Yii::app()->db->createCommand($sql)->queryAll();

        if ($adEndActivity && is_array($adEndActivity)) {
            $usersIdsRolesArr = array();
            $usersIdsArr = CHtml::listData($adEndActivity, 'id', 'owner_id');

            $criteria = new CDbCriteria;
            $criteria->select = 'id, role';
            $criteria->addInCondition('id', $usersIdsArr);
            $result = User::model()->findAll($criteria);
            unset($usersIdsArr);

            if ($result) {
                $usersIdsRolesArr = CHtml::listData($result, 'id', 'role');

                if ($usersIdsRolesArr) {
                    foreach ($adEndActivity as $key => $ad) {
                        if (isset($usersIdsRolesArr[$ad['owner_id']])) {
                            $adEndActivity[$key]['role'] = $usersIdsRolesArr[$ad['owner_id']];
                        }
                    }
                }
                unset($usersIdsRolesArr);
            }

            foreach ($adEndActivity as $ad) {
                if (is_array($ad)) {
                    if (isset($ad['role']) && $ad['role'] == User::ROLE_ADMIN) {
                        $sql = 'UPDATE {{apartment}} SET active = ' . Apartment::STATUS_INACTIVE . ' WHERE id = ' . $ad['id'] . ' LIMIT 1';
                        Yii::app()->db->createCommand($sql)->execute();
                    } else {
                        $sql = 'UPDATE {{apartment}} SET active = ' . Apartment::STATUS_INACTIVE . ', owner_active = ' . Apartment::STATUS_INACTIVE . ' WHERE id = ' . $ad['id'] . ' LIMIT 1';
                        Yii::app()->db->createCommand($sql)->execute();
                    }
                }
            }

            Yii::app()->cache->flush();
        }
    }

    public static function checkTariffPlansUsers()
    {
        if (issetModule('tariffPlans') && issetModule('paidservices')) {
            TariffPlans::checkDeactivateTariffUsers();
        }
    }

    public static function deleteIPFromBlocklist()
    {
        $interval = intval(param('delete_ip_after_days', 5));

        $sql = 'DELETE FROM {{block_ip}} WHERE date_created < (NOW() - INTERVAL ' . $interval . ' DAY)';
        Yii::app()->db->createCommand($sql)->execute();

        Yii::app()->cache->flush();
    }

    public static function clearHistoryChangesTable()
    {
        if (issetModule('historyChanges')) {
            $interval = intval(param('delete_history_changes_after_days', 90));

            $sql = 'DELETE FROM {{history_changes}} WHERE date_created < (NOW() - INTERVAL ' . $interval . ' DAY)';
            Yii::app()->db->createCommand($sql)->execute();
        }
    }

    public static function clearDrafts()
    {
        $sql = 'DELETE FROM {{apartment}} WHERE active=:draft AND date_created<DATE_SUB(NOW(),INTERVAL 1 DAY)';
        Yii::app()->db->createCommand($sql)->execute(array(':draft' => Apartment::STATUS_DRAFT));
    }

    public static function clearGuestAdImagesTemp()
    {
        $dirPlacesComments = Yii::getPathOfAlias('webroot.uploads.guestad');
        $maskToDelete = $dirPlacesComments . DIRECTORY_SEPARATOR . 'temp__*';

        $dirs = glob($maskToDelete);
        if ($dirs && is_array($dirs)) {
            foreach ($dirs as $dir) {
                $timeUpdated = filemtime($dir);

                if ($timeUpdated < time() + self::TIME_UPDATE) {
                    @rrmdir($dir);
                }
            }
        }
    }

    public static function clearSeoDuplicates()
    {
        $sql = 'DELETE FROM `{{seo_friendly_url}}`
			 USING `{{seo_friendly_url}}`, `{{seo_friendly_url}}` as `tmp`
			 WHERE (`{{seo_friendly_url}}`.`id` > `tmp`.`id` )
			 AND (`{{seo_friendly_url}}`.`model_name` = `tmp`.`model_name`)
			 AND (`{{seo_friendly_url}}`.`model_id` = `tmp`.`model_id` )';
        Yii::app()->db->createCommand($sql)->execute();
    }
}
