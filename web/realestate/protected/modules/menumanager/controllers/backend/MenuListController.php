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

class MenuListController extends ModuleAdminController
{

    public $modelName = 'MenuList';

    public function accessRules()
    {
        return array(
            array('allow',
                'expression' => "Yii::app()->user->checkAccess('menumanager_admin')",
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }


    public function getViewPath($checkTheme = true)
    {
        if ($checkTheme && ($theme = Yii::app()->getTheme()) !== null) {
            if (is_dir($theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->getModule($this->id)->getName() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . Yii::app()->controller->id))
                return $theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->getModule($this->id)->getName() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . Yii::app()->controller->id;
        }
        return Yii::getPathOfAlias('application.modules.' . $this->getModule($this->id)->getName() . '.views.' . Yii::app()->controller->id);
    }

    public function actionAdmin()
    {
        $model = new $this->modelName('search');
        $model->resetScope();

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET[$this->modelName])) {
            $model->attributes = $_GET[$this->modelName];
        }
        $this->render('admin', array_merge(array('model' => $model), $this->params));
    }

    public function actionView($id)
    {
        $this->redirect(array('admin'));
    }
}
