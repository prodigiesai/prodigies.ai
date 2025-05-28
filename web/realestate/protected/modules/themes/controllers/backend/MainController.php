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

    public function actionAdmin()
    {
        parent::actionAdmin();
    }

    public function actionSetDefault()
    {
        if (demo()) {
            throw new CException(tc('Sorry, this action is not allowed on the demo server.'));
        }

        $id = (int)Yii::app()->request->getPost('id');

        $model = Themes::model()->findByPk($id);
        $model->setDefault();

        // delete assets js cache
        ConfigurationModel::clearGenerateJSAssets();

        Yii::app()->end();
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $dataModel = null;
        $dataModelValidate = true;

        if ($model->dataModel && class_exists($model->dataModel)) {
            $dataModel = new $model->dataModel;

            if ($model->json_data) {
                $dataModel->attributes = CJSON::decode($model->json_data);
            }
        }

        if ($dataModel && isset($_POST[$model->dataModel])) {
            $dataModel->attributes = $_POST[$model->dataModel];
            $dataModelValidate = $dataModel->validate();
            if ($dataModelValidate) {
                $dataModel->save($model);
            }
        }

        if (isset($_POST["{$this->modelName}"])) {
            $model->attributes = $_POST["{$this->modelName}"];
            $newImage = isset($_FILES['Themes']) && $_FILES['Themes']['name']['upload_img'];
            if ($newImage) {
                // delete old image
                $model->delImage();
                $model->scenario = 'upload';
            }

            if ($model->validate()) {
                if ($newImage) {
                    $model->upload = CUploadedFile::getInstance($model, 'upload_img');
                    $model->bg_image = md5(uniqid()) . '.' . $model->upload->extensionName;
                }

                if ($dataModelValidate && $model->save()) {
                    if ($newImage) {
                        $model->upload->saveAs(Yii::getPathOfAlias($model->path) . '/' . $model->bg_image);

                        Yii::app()->user->setFlash(
                            'success', tt('Image successfully added', 'themes')
                        );
                    } else {
                        Yii::app()->user->setFlash(
                            'success', tc('Success')
                        );
                    }

                    $this->refresh();
                }
            }
        }

        $this->render('update', array(
            'model' => $model,
            'dataModel' => $dataModel
        ));
    }

    public function actionDeleteImg($id)
    {
        $model = $this->loadModel($id);

        $model->delImage();

        $model->bg_image = '';
        if ($model->update('bg_image')) {
            Yii::app()->user->setFlash(
                'success', tt('Image successfully deleted', 'themes')
            );
        } else {
            Yii::app()->user->setFlash(
                'error', HAjax::implodeModelErrors($model)
            );
        }
        $this->redirect(array('update', 'id' => $id));
    }

    public function actionView($id)
    {
        $this->redirect(array('admin'));
    }

    public function actionAjaxPdAddCity()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw404();
        }

        $cityId = Yii::app()->request->getParam('city_id');

        if ($cityId) {
            $theme = Themes::model()->findByAttributes(array('title' => Themes::THEME_BASIS_NAME));

            $dataTheme = $theme->json_data ? CJSON::decode($theme->json_data) : array();

            if (issetModule('location')) {
                $city = City::model()->findByPk($cityId);
            } else {
                $city = ApartmentCity::model()->findByPk($cityId);
            }

            if ($city) {
                $dataTheme['cities'][] = $city->id;
                array_unique($dataTheme);

                $theme->json_data = CJSON::encode($dataTheme);
                $theme->update(array('json_data'));
            }

            HAjax::jsonOk(tc('Success'), array(
                'cities' => $theme->getCitiesString()
            ));
        }

        HAjax::jsonError();
    }

    public function actionAjaxPdDelCity()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw404();
        }

        $cityId = Yii::app()->request->getParam('id');

        if ($cityId) {
            $theme = Themes::model()->findByAttributes(array('title' => Themes::THEME_BASIS_NAME));

            $cities = $theme->getFromJson('cities', array());

            if (in_array($cityId, $cities)) {
                $key = array_search($cityId, $cities);
                unset($cities[$key]);

                if ($cities) {
                    $theme->setInJson('cities', $cities, true);
                } else {
                    $theme->deleteInJson('cities');
                }
            }

            HAjax::jsonOk(tc('Success deleted'), array(
                'cities' => $theme->getCitiesString()
            ));
        }

        HAjax::jsonError();
    }

    public function actionAjaxPdSaveSort()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw404();
        }

        $cities = Yii::app()->request->getParam('sort');
        $theme = Themes::model()->findByAttributes(array('title' => Themes::THEME_BASIS_NAME));
        $theme->setInJson('cities', $cities, true);

        HAjax::jsonOk(tc('Success'));
    }
}
