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
 * This is the model class for table "{{lang_widget_opt}}".
 *
 * The followings are the available columns in table '{{lang_widget_opt}}':
 * @property integer $id
 * @property string $model_name
 * @property integer $model_id
 * @property integer $status
 */
class LangWidgetOpt extends CActiveRecord
{

    const STATUS_SHOW = 0;
    const STATUS_DESTROY = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{lang_widget_opt}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('model_name, model_id, status', 'required'),
            array('model_id, status', 'numerical', 'integerOnly' => true),
            array('model_name', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, model_name, model_id, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'model_name' => 'Model Name',
            'model_id' => 'Model',
            'status' => 'Status',
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
        $criteria->compare('model_name', $this->model_name, true);
        $criteria->compare('model_id', $this->model_id);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LangWidgetOpt the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getStatusForModel($model)
    {
        $status = Yii::app()->db->createCommand()
            ->select('status')
            ->from(LangWidgetOpt::model()->tableName())
            ->where('model_id = :model_id AND model_name = :model_name', array(
                ':model_id' => $model->id,
                ':model_name' => get_class($model),
            ))->queryScalar();

        if ($status === null) {
            $status = (int)Yii::app()->db->createCommand()
                ->select('status')
                ->from(LangWidgetOpt::model()->tableName())
                ->where('model_name = :model_name', array(
                    ':model_name' => get_class($model),
                ))->queryScalar();
        }

        return $status;
    }
}
