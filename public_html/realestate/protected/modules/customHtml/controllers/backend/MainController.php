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

class MainController extends ModuleAdminController
{

    public $modelName = 'CustomHtml';

    public function accessRules()
    {
        return array(
            array('allow',
                'expression' => "Yii::app()->user->checkAccess('entries_admin')",
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionCreate()
    {
        $model = new $this->modelName;

        $this->performAjaxValidation($model);

        if (isset($_POST[$this->modelName])) {
            $model->attributes = $_POST[$this->modelName];
            $model->active = 1;
            if ($model->save()) {
                Yii::app()->user->setFlash('success', tc('Success'));

                if (isset($_POST['save_close_btn'])) {
                    $this->redirect(array('admin'));
                } else {
                    $this->redirect(array('update', 'id' => $model->id));
                }
            }
        }

        $this->render('create', array('model' => $model));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if (isset($_POST[$this->modelName])) {
            $model->attributes = $_POST[$this->modelName];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', tc('Success'));

                #reload model
                $model = $this->loadModel($model->id);

                if (isset($_POST['save_close_btn'])) {
                    $this->redirect(array('admin'));
                }
            }
        }

        $this->render('update', array('model' => $model));
    }

    public function actionView($id)
    {
        $this->redirect(array('admin'));
    }
}
