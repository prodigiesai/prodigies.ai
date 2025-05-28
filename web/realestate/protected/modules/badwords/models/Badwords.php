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

class Badwords extends ParentModel
{
    /** @var array|null */
    protected static $cacheAll;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return '{{badwords}}';
    }

    public function rules()
    {
        return array(
            array('name', 'length', 'max' => 255),
            array('name', 'required'),
            array('id', 'safe', 'on' => 'search'),
            array($this->getI18nFieldSafe(), 'safe'),
        );
    }

    public function i18nFields()
    {
        return array();
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => tc('Name'),
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        return new CustomActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => param('adminPaginationPageSize', 20),
            ),
        ));
    }

    /**
     * @return array
     * @throws CException
     */
    public static function getAllBadWords()
    {
        if (!isset(self::$cacheAll)) {
            if (!empty($result = Yii::app()->db->createCommand('SELECT id, name FROM {{badwords}}')->queryAll())) {
                self::$cacheAll = CHtml::listData($result, 'id', 'name');
            }
        }

        return self::$cacheAll;
    }
}
