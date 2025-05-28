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

class WidgetController extends ModuleAdminController
{
    public $modelName = 'Themes';

    public function accessRules()
    {
        return array(
            array('allow',
                'expression' => "Yii::app()->user->checkAccess('all_settings_admin')",
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

//    public function init()
//    {
//        parent::init();
//        Yii::app()->user->setState('menu_active', 'themes.widget');
//    }

    public function getViewPath($checkTheme = true)
    {
        if ($checkTheme && ($theme = Yii::app()->getTheme()) !== null) {
            if (is_dir($theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->getModule($this->id)->getName() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . Yii::app()->controller->id))
                return $theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->getModule($this->id)->getName() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . Yii::app()->controller->id;
        }
        return Yii::getPathOfAlias('application.modules.' . $this->getModule($this->id)->getName() . '.views.' . Yii::app()->controller->id);
    }

    public function actionPopularDest($id)
    {
        $model = $this->loadModel($id);

        $dataModel = null;

        if ($model->dataModel && class_exists($model->dataModel)) {
            $dataModel = new $model->dataModel;

            if ($model->json_data) {
                $dataModel->attributes = CJSON::decode($model->json_data);
            }
        }

        if (isset($_GET['type']) && in_array($_GET['type'], PopUnit::getTypeList())) {
            $dataModel->popular_dest_type = $_GET['type'];

            $_POST[$model->dataModel]['popular_dest_type'] = $dataModel->popular_dest_type;
        } elseif (!$dataModel->popular_dest_type) {
            $dataModel->popular_dest_type = PopUnit::TYPE_DEFAULT;
        }

        // заголовок виджета
        $titleModel = TranslateMessage::model()->findByAttributes(['message' => 'Popular Destinations', 'category' => 'module_basis_theme']);
        if (isset($_POST['TranslateMessage'])) {
            $titleModel->attributes = $_POST['TranslateMessage'];
            $titleModel->save();
        }

        if ($dataModel && isset($_POST[$model->dataModel])) {
            $dataModel->attributes = $_POST[$model->dataModel];
            $dataModelValidate = $dataModel->validate();
            if ($dataModelValidate) {
                $dataModel->save($model);
            }
        }

        $popUnit = PopFactory::getUnit($dataModel->popular_dest_type, $model);

        $this->render('popular_dest', array(
            'model' => $model,
            'dataModel' => $dataModel,
            'popUnit' => $popUnit,
            'titleModel' => $titleModel,
        ));
    }


    public function actionAjaxPdAddItem()
    {
        $itemId = (int)Yii::app()->request->getParam('item_id');

        $popUnit = $this->getPopUnit(true);

        if ($popUnit->addItem($itemId)) {
            HAjax::jsonOk(tc('Success'), array(
                'html' => $popUnit->getItemsString()
            ));
        }

        HAjax::jsonError($popUnit->getErrorsString());
    }

    public function actionAjaxPdDelItem()
    {
        $itemId = (int)Yii::app()->request->getParam('item_id');

        $popUnit = $this->getPopUnit(true);

        if ($popUnit->delItem($itemId)) {
            HAjax::jsonOk(tc('The item is deleted'), array(
                'html' => $popUnit->getItemsString()
            ));
        }

        HAjax::jsonError();
    }

    private function getPopUnit($checkItemId = false)
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw404();
        }

        $type = Yii::app()->request->getParam('type');
        $themeId = (int)Yii::app()->request->getParam('theme_id');
        $itemId = (int)Yii::app()->request->getParam('item_id');

        if (!$type || !$themeId || ($checkItemId && !$itemId)) {
            throw new CHttpException('Bad params', 400);
        }

        $theme = Themes::model()->findByPk($themeId);

        return PopFactory::getUnit($type, $theme);
    }

    public function actionAjaxPdSaveSort()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw404();
        }

        $type = Yii::app()->request->getParam('type');
        $themeId = (int)Yii::app()->request->getParam('theme_id');
        $itemsId = Yii::app()->request->getParam('sort');

        if (!$type || !$themeId || !$itemsId) {
            throw new CHttpException('Bad params', 400);
        }

        $popUnit = $this->getPopUnit(false);

        $theme = Themes::model()->findByPk($themeId);

        $theme->setInJson($popUnit->getKeyItemsId(), $itemsId, true);

        HAjax::jsonOk(tc('Success'));
    }
}