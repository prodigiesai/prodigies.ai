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

    public $modelName = 'ApartmentCity';

    public function accessRules()
    {
        return array(
            array('allow',
                'expression' => "Yii::app()->user->checkAccess('all_reference_admin')",
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionView($id)
    {
        $this->redirect(array('admin'));
    }

    public function actionIndex()
    {
        $this->redirect(array('admin'));
    }

    public function actionAdmin()
    {
        $this->getMaxSorter();
        $this->getMinSorter();

        parent::actionAdmin();
    }

    public function actionCreate()
    {
        $model = new $this->modelName;

        $this->performAjaxValidation($model);

        if (isset($_POST[$this->modelName])) {
            $model->attributes = $_POST[$this->modelName];
            $model->cityImage = CUploadedFile::getInstance($model, 'cityImage');
            if ($model->save()) {
                Yii::app()->user->setFlash('success', tt('The new city is successfully created.'));
                if (isset($_POST['addMore']) && $_POST['addMore'] == 1)
                    $this->redirect('create');
                $this->redirect('admin');
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
            if ($model->validate()) {
                $model->cityImage = CUploadedFile::getInstance($model, 'cityImage');
                if ($model->save(false)) {
                    Yii::app()->user->setFlash('success', tc('Success'));

                    if (!empty($this->redirectTo))
                        $this->redirect($this->redirectTo);
                    else
                        $this->redirect(array('update', 'id' => $model->id));
                    //$this->redirect(array('view', 'id' => $model->id));
                }
            }
        }

        $this->render('update', array_merge(
                array('model' => $model), $this->params
            )
        );
    }

    public function actionDelete($id)
    {

        // Не дадим удалить последний город
        if (ApartmentCity::model()->count() <= 1) {
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('error', tt('You can not delete the last city'));
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            } else {
                echo "<div class='flash-error'>" . tt('You can not delete the last city') . "</div>";
            }
            Yii::app()->end();
        }

        parent::actionDelete($id);
    }
}
