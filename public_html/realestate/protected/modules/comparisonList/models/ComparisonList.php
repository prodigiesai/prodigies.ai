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

class ComparisonList extends ParentModel
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{comparison_list}}';
    }

    public function rules()
    {
        return array(
            array('apartment_id, session_id', 'required'),
            array('user_id, apartment_id', 'numerical', 'integerOnly' => true),
            array('id, user_id, apartment_id, session_id, date_updated', 'safe', 'on' => 'search'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => tc('User'),
            'apartment_id' => tc('Apartment ID'),
            'session_id' => tt('Session_id', 'comparisonList'),
            'date_updated' => tc('Date updated'),
        );
    }

    public function search()
    {

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('apartment_id', $this->apartment_id);
        $criteria->compare('session_id', $this->session_id);

        return new CustomActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id ASC',
            ),
            'pagination' => array(
                'pageSize' => param('adminPaginationPageSize', 20),
            ),
        ));
    }

    public function behaviors()
    {
        return array(
            'AutoTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => null,
                'updateAttribute' => 'date_updated',
            ),
        );
    }

    public static function getCountListingsGuest($sessionId = '')
    {
        if ($sessionId) {
            $sql = 'SELECT COUNT(c.id) FROM {{comparison_list}} c
                    INNER JOIN {{apartment}} a ON a.id = c.apartment_id
                    WHERE c.session_id = :session_id AND active = 1 AND owner_active = 1 AND deleted = 0';
            return Yii::app()->db->createCommand($sql)->queryScalar(array(':session_id' => $sessionId));
        }
        return 0;
    }

    public static function getCountListingsUser($userId = '')
    {
        if ($userId) {
            $sql = 'SELECT COUNT(c.id) FROM {{comparison_list}} c
                    INNER JOIN {{apartment}} a ON a.id = c.apartment_id
				    WHERE c.user_id = :user_id AND active = 1 AND owner_active = 1 AND deleted = 0';
            return Yii::app()->db->createCommand($sql)->queryScalar(array(':user_id' => $userId));
        }
        return 0;
    }

    public static function getCountIn()
    {
        if (Yii::app()->user->isGuest) {
            $currCount = ComparisonList::getCountListingsGuest(Yii::app()->session->sessionId);
        } else {
            $currCount = ComparisonList::getCountListingsUser(Yii::app()->user->id);
        }

        return $currCount;
    }

    public static function getRefCategories()
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('type = ' . ReferenceCategories::TYPE_STANDARD);
        $criteria->order = 'sorter ASC';

        return ReferenceCategories::model()->findAll($criteria);
    }
}
