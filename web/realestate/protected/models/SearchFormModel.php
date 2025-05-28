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
 * This is the model class for table "{{search_from}}".
 *
 * The followings are the available columns in table '{{search_from}}':
 * @property integer $id
 * @property integer $page
 * @property integer $status
 * @property integer $obj_type_id
 * @property string $field
 * @property integer $sorter
 */
class SearchFormModel extends ParentModel
{

    const OBJ_TYPE_ID_DEFAULT = 0;
    const STATUS_STANDARD = 1;
    const STATUS_NOT_REMOVE = 2;
    const STATUS_NEW_FIELD = 3;
    const PAGE_INDEX = 1;
    const PAGE_INNER = 2;

    public static function getPageList()
    {
        return array(
            self::PAGE_INDEX => tt('Main page'),
            self::PAGE_INNER => tt('Inner pages'),
        );
    }

    public function getPageName()
    {
        $list = self::getPageList();
        return isset($list[$this->page]) ? $list[$this->page] : '-';
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{search_form}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('status, obj_type_id, field, sorter', 'required'),
            array('status, obj_type_id, sorter, compare_type, formdesigner_id, page', 'numerical', 'integerOnly' => true),
            array('field', 'length', 'max' => 100),
            array('id, status, obj_type_id, field, sorter', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'formdesigner' => array(self::BELONGS_TO, 'FormDesigner', 'formdesigner_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'status' => 'Status',
            'obj_type_id' => 'Obj Type',
            'field' => 'Field',
            'sorter' => 'Sorter',
        );
    }

    public function scopes()
    {
        return array(
            'sort' => array(
                'order' => 'sorter ASC',
            ),
        );
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
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('status', $this->status);
        $criteria->compare('obj_type_id', $this->obj_type_id);
        $criteria->compare('field', $this->field, true);
        $criteria->compare('sorter', $this->sorter);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SearchFormModel the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getLabel()
    {
        return self::getLabelByField($this->field);
    }

    public static function getLabelByField($field)
    {
        if ($field == SearchForm::SEARCH_LOCATION) {
            if (issetModule('location')) {
                return tc('Country') . ' / ' . tc('Region') . ' / ' . tc('City');
            } else {
                return tc('City');
            }
        }

        $elements = SearchForm::getSearchFields();
        if (isset($elements[$field])) {
            return tc($elements[$field]['translate']);
        } else {
            return tc('Search by ' . $field);
        }
    }

    public static function getFields($isInner = false, $objType = null, $typeDeal = null)
    {
        // for next version
        //$page = $isInner ? SearchFormModel::PAGE_INNER : SearchFormModel::PAGE_INDEX;
        $page = SearchFormModel::PAGE_INDEX;

        $criteria = new CDbCriteria;
        $criteria->select = 'DISTINCT field, id, page, status, compare_type, obj_type_id, sorter, formdesigner_id';

        $criteria->addCondition('obj_type_id=:obj_type_id');
        $criteria->addCondition('page=:page');

        $criteria->params[':obj_type_id'] = SearchFormModel::OBJ_TYPE_ID_DEFAULT;
        $criteria->params[':page'] = $page;

        if (isset($objType) && $objType) {
            $criteria->params[':obj_type_id'] = $objType;
            $criteria->params[':page'] = $page;

            $searchFields = SearchFormModel::model()
                ->sort()
                ->findAll($criteria);

            if (!$searchFields) {
                $criteria->params[':obj_type_id'] = SearchFormModel::OBJ_TYPE_ID_DEFAULT;
                $criteria->params[':page'] = $page;
            } else {
                if($typeDeal){
                    return self::getFieldsByTypeDeal($typeDeal, $searchFields);
                }

                return $searchFields;
            }
        }

        $searchFields = SearchFormModel::model()
            ->sort()
            ->findAll($criteria);

        if($typeDeal){
            return self::getFieldsByTypeDeal($typeDeal, $searchFields);
        }

        return $searchFields;
    }

    private static function getFieldsByTypeDeal($typeDeal, $searchFields)
    {
        $searchFieldsByField = [];
        foreach ($searchFields as $searchField){
            $searchFieldsByField[$searchField->field] = $searchField;
        }

        $searchFieldsParams = SearchFormFieldParam::model()->findAll();

        if (strpos($typeDeal, '-') !== false) {
            $typeArr = explode('-', $typeDeal);
            $typeDeal = isset($typeArr[0]) ? $typeArr[0] : 0;
        }

        foreach ($searchFieldsParams as $param){
            if(isset($searchFieldsByField[$param->field])){
                $typeDealsAllow = $param->getFromJson('type_deals');
                if(!in_array($typeDeal, $typeDealsAllow)){
                    unset($searchFieldsByField[$param->field]);
                }
            }
        }

        return $searchFieldsByField;
    }
}
