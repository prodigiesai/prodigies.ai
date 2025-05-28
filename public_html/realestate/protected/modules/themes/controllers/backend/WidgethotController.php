<?php


class WidgethotController extends ModuleAdminController
{
    public $modelName = 'Themes';

    public function getViewPath($checkTheme = true)
    {
        if ($checkTheme && ($theme = Yii::app()->getTheme()) !== null) {
            if (is_dir($theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->getModule($this->id)->getName() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . Yii::app()->controller->id))
                return $theme->getViewPath() . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $this->getModule($this->id)->getName() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . Yii::app()->controller->id;
        }
        return Yii::getPathOfAlias('application.modules.' . $this->getModule($this->id)->getName() . '.views.' . Yii::app()->controller->id);
    }

    public function actionEdit($id)
    {
        $model = $this->loadModel($id);

        $dataModel = null;

        // заголовок виджета
        $titleModel = TranslateMessage::model()->findByAttributes(['message' => 'Best listings', 'category' => 'common']);
        if (isset($_POST['TranslateMessage'])) {
            $titleModel->attributes = $_POST['TranslateMessage'];
            $titleModel->save();
        }

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
                if($dataModel->save($model)){
                    Yii::app()->user->setFlash('success', tc('Success'));
                }
            }
        }

        $this->render('edit', [
            'model' => $model,
            'dataModel' => $dataModel,
            'titleModel' => $titleModel,
        ]);
    }
}