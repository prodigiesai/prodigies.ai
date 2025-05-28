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

class MainController extends ModuleUserController
{

    public $modelName = 'ComparisonList';
    public $defaultAction = 'index';
    public $layout = '//layouts/compare';

    public function actionIndex()
    {
        Yii::app()->getModule('referencecategories');

        $criteria = new CDbCriteria;
        $criteria->addInCondition('t.id', Yii::app()->controller->apInComparison);
        $criteria->addCondition('t.deleted = 0');
        $criteria->addCondition('t.active = ' . Apartment::STATUS_ACTIVE);
        $criteria->addCondition('t.owner_active = 1');

        $apartments = HApartment::findAllWithCache($criteria);

        if (!$apartments)
            $this->redirect(Yii::app()->controller->createAbsoluteUrl('/'));

        $this->render('index', array('apartments' => $apartments));
    }

    public function actionAdd()
    {
        // удаляем старые
        $this->deleteOld();

        if (!Yii::app()->request->isAjaxRequest) {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }

        $userId = $apartmentId = $sessionId = 0;

        if (!Yii::app()->user->isGuest)
            $userId = Yii::app()->user->id;

        $apartmentId = (int)Yii::app()->request->getParam('apId');
        $isJson = (int)Yii::app()->request->getParam('isJson');
        $sessionId = Yii::app()->session->sessionId;

        $currCount = ComparisonList::getCountIn();

        if ($currCount >= param('countListingsInComparisonList', 6)) {
            if ($isJson) {
                HAjax::jsonError('', array('error' => 'max_limit'));
            } else {
                echo 'max_limit';
            }
            Yii::app()->end();
        }

        if ($apartmentId && $sessionId) {
            $model = new ComparisonList;
            $model->user_id = $userId;
            $model->apartment_id = $apartmentId;
            $model->session_id = $sessionId;

            if ($model->validate()) {
                if ($model->save(false)) {
                    $currCount++;
                    if ($isJson) {
                        HAjax::jsonOk('', array(
                            'count' => $currCount,
                            'countString' => '<i class="fa fa-list"></i> ' . Yii::t('common', '{n} in the comparison list', array('{n}' => $currCount))
                        ));
                    } else {
                        echo 'ok';
                    }
                }
            } else {
                if ($isJson) {
                    HAjax::jsonError();
                } else {
                    echo 'no_valid';
                }
            }
        } else {
            if ($isJson) {
                HAjax::jsonNone();
            } else {
                echo 'no_data';
            }
        }
        Yii::app()->end();
    }

    public function actionDel()
    {
        $userId = $apartmentId = $sessionId = '';

        if (!Yii::app()->user->isGuest)
            $userId = Yii::app()->user->id;

        $apartmentId = (int)Yii::app()->request->getParam('apId');
        $isJson = (int)Yii::app()->request->getParam('isJson');
        $sessionId = Yii::app()->session->sessionId;

        if ($apartmentId) {
            if ($userId) {
                $result = ComparisonList::model()->findAllByAttributes(
                    array(
                        'apartment_id' => $apartmentId,
                        'user_id' => $userId,
                    )
                );
            } else {
                $result = ComparisonList::model()->findAllByAttributes(
                    array(
                        'apartment_id' => $apartmentId,
                        'session_id' => $sessionId,
                    )
                );
            }

            if ($result) {
                foreach ($result as $item) {
                    $model = ComparisonList::model()->findByPk($item->id);
                    $model->delete();
                }
            }

            if (Yii::app()->request->isAjaxRequest) {
                if ($isJson) {
                    $currCount = ComparisonList::getCountIn();
                    HAjax::jsonOk('', array(
                        'count' => $currCount,
                        'countString' => '<i class="fa fa-list"></i> ' . Yii::t('common', '{n} in the comparison list', array('{n}' => $currCount))
                    ));
                } else {
                    echo 'ok';
                }
            } else {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            }
        }
        Yii::app()->end();
    }

    public function deleteOld()
    {
        # удаляем старые только "у гостей".
        $sql = 'DELETE FROM {{comparison_list}} WHERE (date_updated + INTERVAL 10 DAY) < NOW() AND user_id = 0';
        Yii::app()->db->createCommand($sql)->execute();
    }
}
