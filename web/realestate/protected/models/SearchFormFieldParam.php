<?php

/**
 * Class SearchFormFieldParam
 * @property string $field
 * @property string $json_data
 *
 * @mixin JsonBehavior
 */
class SearchFormFieldParam extends ParentModel
{
    public $saveApTypes = array();

    public $locationParams;

    public function behaviors()
    {
        return array(
            'JsonBehavior' => array(
                'class' => 'application.components.behaviors.JsonBehavior',
            ),
        );
    }

    public function rules()
    {
        return [
            ['apTypesArray, locationParams', 'safe']
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return FormDesigner the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{search_form_field_param}}';
    }


    public function getApTypesArray()
    {
        if (!$this->saveApTypes) {
            if ($this->isNewRecord) {
                $this->saveApTypes = array_keys(HApartment::getTypesArray());
            } else {
                $this->saveApTypes = $this->getFromJson('type_deals');
            }
        }

        return $this->saveApTypes;
    }

    public function setApTypesArray($value)
    {
        $this->saveApTypes = $value;
        $this->setInJson('type_deals', $value);
    }

    public function attributeLabels()
    {
        return array(
            'apTypesArray' => tt('Show for type of listing', 'formdesigner'),
        );
    }
}