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

use application\modules\favorite\services\FavoriteStorageService;

class MainController extends ModuleUserController
{
    public $modelName = 'Favorite';

    private $storage;

    public function __construct($id, $module=null)
    {
        $this->storage = new FavoriteStorageService();

        parent::__construct($id, $module);
    }

    public function actionIndex()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $this->excludeJs();
            $this->renderPartial('list', [
                'listCriteria' => $this->storage->getListCriteria(),
            ]);
        } else {
            $this->render('list', [
                'listCriteria' => $this->storage->getListCriteria(),
            ]);
        }
    }

    public function actionAdd()
    {
        $model = new FavoriteForm();

        $model->model_id = Yii::app()->request->getPost('model_id');
        $model->model_name = Yii::app()->request->getPost('model_name');

        try {
            if($model->validate() && $this->storage->add($model)){
                HAjax::jsonOk();
            } else {
                HAjax::jsonError(CHtml::errorSummary($model));
            }
        } catch (Exception $exception){
            HAjax::jsonError($exception->getMessage());
        }
    }

    public function actionRemove()
    {
        $model = new FavoriteForm();

        $model->model_id = Yii::app()->request->getPost('model_id');
        $model->model_name = Yii::app()->request->getPost('model_name');

        try {
            if($model->validate() && $this->storage->remove($model)){
                HAjax::jsonOk();
            } else {
                HAjax::jsonError(CHtml::errorSummary($model));
            }
        } catch (Exception $exception){
            HAjax::jsonError($exception->getMessage());
        }
    }
}