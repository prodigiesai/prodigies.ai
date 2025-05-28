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

/**
 * This is the model class for table "{{menu_list}}".
 *
 * The followings are the available columns in table '{{menu_list}}':
 * @property integer $id
 * @property integer $active
 * @property string $name_ru
 * @property string $name_en
 * @property string $name_de
 * @property string $name_es
 * @property string $name_ar
 * @property integer $is_system
 */
class MenuList extends ParentModel
{
    const ID_TOP_ATLAS_BASIC_1 = 1;
    const ID_TOP_ATLAS_BASIC_2 = 1;
    const ID_TOP_BASIS_DOLPHIN_1 = 3;
    const ID_TOP_BASIS_DOLPHIN_2 = 4;
    const ID_TOP_BASIS_DOLPHIN_3 = 5;

    public function i18nFields()
    {
        return array(
            'name' => 'varchar(255) not null default ""',
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{menu_list}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('active, is_system', 'numerical', 'integerOnly' => true),
            array('name_ru, name_en, name_de, name_es, name_ar', 'length', 'max' => 255),
            array('name', 'i18nRequired', 'except' => 'multiply'),
            array($this->getI18nFieldSafe(), 'safe'),
            array('id, active, name_ru, name_en, name_de, name_es, name_ar, is_system', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'active' => 'Active',
            'name' => tc('Name'),
            'is_system' => 'Is System',
        );
    }

    public function getName()
    {
        return $this->getStrByLang('name');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $tmp = 'name_' . Yii::app()->language;
        $criteria->compare('t.' . $tmp, $this->$tmp, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('active', $this->active);
        $criteria->compare('is_system', $this->is_system);

        if (!in_array(Themes::THEME_BASIS_NAME, Themes::getDBTemplatesList()) && !in_array(Themes::THEME_DOLPHIN_NAME, Themes::getDBTemplatesList())) {
            $criteria->addNotInCondition('id', array(MenuList::ID_TOP_BASIS_DOLPHIN_1, MenuList::ID_TOP_BASIS_DOLPHIN_2, MenuList::ID_TOP_BASIS_DOLPHIN_3));
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return MenuList the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function afterDelete()
    {
        $sql = 'DELETE FROM {{menu}} WHERE menu_list_id=:id';
        Yii::app()->db->createCommand($sql)->execute(array(':id' => $this->id));

        return parent::afterDelete();
    }

    public function allowDelete()
    {
        if ($this->is_system == 1) {
            return false;
        }
        return true;
    }
}
