<?php
namespace application\modules\favorite\models;

/**
 * Class Favorite
 * @property string $model_name
 * @property integer $model_id
 * @property integer $user_id
 * @property string $date_created
 */
class Favorite extends \CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName(){
        return '{{favorite}}';
    }

    public function primaryKey(){
        return 'model_id';
    }

    public function rules()
    {
        return [
            ['model_name, model_id, user_id', 'required'],
            ['model_id, user_id', 'numerical', 'integerOnly' => true],
        ];
    }

    public function search()
    {
        $criteria = new \CDbCriteria;

        $criteria->compare($this->getTableAlias() . '.user_id', $this->user_id);
        $criteria->compare($this->getTableAlias() . '.model_id', $this->model_id);
        $criteria->compare($this->getTableAlias() . '.model_name', $this->model_name);

        return new \CustomActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC',
            ),
            'pagination' => array(
                'pageSize' => param('adminPaginationPageSize', 20),
            ),
        ));
    }
}