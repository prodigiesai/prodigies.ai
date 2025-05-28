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

class MainController extends ModuleUserController
{

    public $layout = '//layouts/usercpanel';
    public $htmlPageId = 'userads';
    public $modelName = 'UserAds';
    public $photoUpload = false;
    public $showSearchForm = false;

    public function init()
    {
        parent::init();

        if (!param('useUserads')) {
            throw404();
        }
        // если админ - делаем редирект на просмотр в админку
        if (!$this->_isAPICall && Yii::app()->user->checkAccess('apartments_admin')) {
            $this->redirect($this->createAbsoluteUrl('/apartments/backend/main/admin'));
        }
    }

    public function behaviors()
    {
        if (issetModule('api')) {
            return array(
                'restAPI' => array('class' => '\rest\controller\Behavior')
            );
        }
        return array();
    }

    public function accessRules()
    {
        return array(
            array(
                'allow',
                'expression' => 'param("useUserads") && Yii::app()->user->checkAccess("registered")',
            ),
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        Yii::app()->user->setState('searchUrl', NULL);

        $model = new Apartment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Apartment'])) {
            $model->attributes = $_GET['Apartment'];
        }
        $model = $model->onlyAuthOwner()->notDeleted();
        $model->scopeChilds('grid');

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('index', array('model' => $model), false, true);
        } else {
            $this->render('index', array('model' => $model));
        }
    }

    public function actionActivate()
    {

        if (isset($_GET['id']) && isset($_GET['action'])) {
            $action = filter_var(Yii::app()->request->getQuery('action'), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $model = $this->loadModelUserAd((int)$_GET['id']);
            $model->scenario = 'update_status';

            if ($model) {
                if (issetModule('tariffPlans') && issetModule('paidservices') && $action == 'activate') {
                    TariffPlans::checkAllowUserActivateAd($model->owner_id, false, '>', true);
                }

                $model->owner_active = ($action == 'activate' ? 1 : 0);
                $model->update(array('owner_active'));
            }
        }
        if (!Yii::app()->request->isAjaxRequest) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }

    public function actionCreate()
    {
        $this->setActiveMenu('add_ad');

        $this->modelName = 'Apartment';
        $model = new $this->modelName;

        $user = User::model()->findByPk(Yii::app()->user->id);
        if (preg_match("/null\.io/i", $user->email)) {
            Yii::app()->user->setFlash('error', tt('You can not add listings till you specify your valid email.', 'socialauth'));
            $this->redirect(array('/usercpanel/main/index', 'from' => 'userads'));
        } elseif (!$user->phone) {
            Yii::app()->user->setFlash('error', tt('You can not add listings till you specify your phone number.', 'socialauth'));
            $this->redirect(array('/usercpanel/main/index', 'from' => 'userads'));
        }

        $model->active = Apartment::STATUS_DRAFT;
        $model->owner_active = Apartment::STATUS_ACTIVE;
        $model->owner_id = Yii::app()->user->id;

        if (issetModule('tariffPlans') && issetModule('paidservices')) {
            $return = TariffPlans::checkAllowUserActivateAd($model->owner_id, true, '>=');
            if ($return === false) {
                $model->owner_active = Apartment::STATUS_INACTIVE;

                $tariffPlanInfo = TariffPlans::getTariffInfoByUserId(Yii::app()->user->id);

                Yii::app()->user->setFlash('error', Yii::t('module_tariffPlans', 'Exhausted the limit of {limit} active ads, deactivate other ads or <a href="{link}">change tariff plan</a>', array('{limit}' => $tariffPlanInfo['limitObjects'], '{link}' => Yii::app()->createAbsoluteUrl('/tariffPlans/main/index'))));
                $this->redirect(array('/tariffPlans/main/index'));
                Yii::app()->end;

            }
        }

        $model->setDefaultType();
        $model->date_manual_updated = date(HSite::$dateFormat);
        HApartment::checkChildParam($model);
        $model->save(false);

        $this->redirect(array('update', 'id' => $model->id));
    }

    public function loadModelUserAd($id)
    {
        $model = $this->loadModel($id);

        if (!$model->isOwner() || $model->deleted)
            throw404();

        return $model;
    }

    public function actionUpdate($id)
    {
        if ($this->_isAPICall && !$this->isPut()) {
            throw new CHttpException(400, Yii::t('app', 'Invalid PUT request'));
        }

        $checkAllowUserActivateAd = null;
        $this->setActiveMenu('add_ad');
        $show = Yii::app()->request->getParam('show');

        $model = $this->loadModelUserAd($id);
        if (issetModule('bookingcalendar')) {
            $model = $model->with(array('bookingCalendar'));
        }

        $this->performAjaxValidation($model);

        if (isset($_GET['type'])) {
            $model->type = HApartment::getRequestType();

            $priceTypesArr = HApartment::getPriceArray($model->type);
            if (!empty($priceTypesArr)) {
                reset($priceTypesArr);
                $model->price_type = key($priceTypesArr);
            }
        }

        $priceTypesArr = HApartment::getPriceArray($model->type);

        $model = HGeo::setForAd($model);

        if (issetModule('metroStations')) {
            $model->metroStations = MetroStations::getMetroStations($model->id);
        }

        if ($model->parent_id) {
            $parentIdInfo = Apartment::model()->findByPk($model->parent_id);
            $data = array(
                '{id}' => tt('ID', 'apartments') . ':' . $parentIdInfo->id,
                '{title}' => $parentIdInfo->getStrByLang('title'),
                '{address}' => $parentIdInfo->getStrByLang('address'),
            );
            $model->parent_id_autocomplete = strtr(Apartment::$_parentAutoCompleteTemplate, $data);
        }

        $seasonalPricesModel = null;
        if (issetModule('seasonalprices')) {
            $seasonalPricesModel = new Seasonalprices;
            $seasonalPricesModel->setDefaults();
        }

        $arrayPut = ($this->_isAPICall && $this->isPut()) ? $this->getPutData() : null;

        if (isset($_POST[$this->modelName]) || !empty($arrayPut)) {
            $originalActive = $model->active;

            if (!empty($arrayPut)) {
                $arrayPut = $this->normalizePutData($arrayPut);
                $model->attributes = $arrayPut['UserAds'];
            } else {
                $model->attributes = $_POST[$this->modelName];
            }

            // video, panorama, lat, lon
            HApartment::saveOther($model);

            $model->scenario = 'savecat';

            if (issetModule('tariffPlans') && issetModule('paidservices')) {
                $checkAllowUserActivateAd = TariffPlans::checkAllowUserActivateAd($model->owner_id, true, '>=', true);

                if ($checkAllowUserActivateAd === false) {
                    $tariffInfo = TariffPlans::getTariffInfoByUserId($model->owner_id);
                    $limit = $tariffInfo['limitObjects'];
                    Yii::app()->user->setFlash('error', Yii::t('module_tariffPlans', 'Exhausted the limit of {limit} active ads, deactivate other ads or <a href="{link}">change tariff plan</a>', array('{limit}' => $limit, '{link}' => Yii::app()->createAbsoluteUrl('/tariffPlans/main/index'))));

                    $model->owner_active = Apartment::STATUS_INACTIVE;
                }
            }

            $isUpdate = Yii::app()->request->getPost('is_update');
            $isAutoSave = Yii::app()->request->getPost('is_auto_save');

            $model->isAjaxLoadOnUpdate = $isUpdate;
            if ($isAutoSave) {
                $model->isAjaxLoadOnUpdate = true;
            }

            $model->date_manual_updated = date(HSite::$dateFormat);

            if ($isUpdate) {
                $model->active = $originalActive;

                if (isset($priceTypesArr) && !empty($priceTypesArr)) {
                    reset($priceTypesArr);
                    $model->price_type = key($priceTypesArr);
                }

                $model->clearErrors();
                $model->save(false);
            } elseif ($isAutoSave) {
                $model->active = $originalActive;

                $model->clearErrors();
                $model->save(false);
                Yii::app()->end();
            } elseif ($model->validate(null, true)) {
                if ($checkAllowUserActivateAd === false) {
                    $model->owner_active = Apartment::STATUS_INACTIVE;
                }

                if (param('useUseradsModeration', 1)) {
                    $model->active = Apartment::STATUS_MODERATION;
                } else {
                    $model->active = Apartment::STATUS_ACTIVE;
                }

                if (issetModule('metroStations')) {
                    MetroStations::setMetroStations($model->id, $model->metroStations);
                }

                if ($model->save(false)) {
                    if ($this->_isAPICall) {
                        $sessionId = uniqid(Yii::app()->session->sessionId);
                        $filePathName = 'temp__' . $sessionId;
                        $dir = Yii::getPathOfAlias('webroot.uploads.guestad.' . $filePathName);

                        if (isset($arrayPut['UserAds']['imagesList']) && !empty($arrayPut['UserAds']['imagesList'])) {
                            # check maximum file upload for ad
                            $imagesList = array_slice($arrayPut['UserAds']['imagesList'], 0, Images::getGuestAdMaxPhotos());

                            if (!empty($imagesList)) {
                                $allowedExtensions = param('allowedImgExtensions', array('jpg', 'jpeg', 'gif', 'png'));

                                //$sizeLimit = param('maxImgFileSize', 8 * 1024 * 1024);
                                $sizeLimit = Images::getMaxSizeLimit();

                                $path = Yii::getPathOfAlias('webroot.uploads.guestad.' . $filePathName . '.' . Images::ORIGINAL_IMG_DIR);
                                $pathMod = Yii::getPathOfAlias('webroot.uploads.guestad.' . $filePathName . '.' . Images::MODIFIED_IMG_DIR);

                                $oldUMask = umask(0);
                                if (!is_dir($path)) {
                                    @mkdir($path, 0777, true);
                                }
                                if (!is_dir($pathMod)) {
                                    @mkdir($pathMod, 0777, true);
                                }
                                umask($oldUMask);

                                if (is_writable($path) && is_writable($pathMod)) {
                                    touch($path . DIRECTORY_SEPARATOR . 'index.htm');
                                    touch($pathMod . DIRECTORY_SEPARATOR . 'index.htm');

                                    foreach ($imagesList as $key => $imagePath) {
                                        $pathInfo = pathinfo($imagePath);
                                        $extension = $pathInfo['extension'];
                                        $basename = $pathInfo['basename']; # with extension
                                        //$filename = $pathInfo['filename']; # without extension

                                        if ($allowedExtensions && in_array(strtolower($extension), $allowedExtensions)) {
                                            $imageContent = getRemoteDataInfo($imagePath, false, 20);
                                            if ($imageContent && mb_strlen($imageContent, 'UTF-8') > 20000) { # 10KB
                                                $newFileName = $key + 1 . '__' . uniqid() . '.' . $extension;

                                                $photoPath = $path . DIRECTORY_SEPARATOR . $newFileName;
                                                if (file_put_contents($photoPath, $imageContent)) {
                                                    if (!file_exists($photoPath)) {
                                                        @unlink($photoPath);
                                                    } else {
                                                        $resultMemoryCheck = HSite::allowUploadAndResizeImage($photoPath);

                                                        if ($resultMemoryCheck['result'] === true) {
                                                            $resize = new CImageHandler();
                                                            if ($resize->load($photoPath)) {
                                                                $resize->thumb(param('maxImageWidth', 1024), param('maxImageHeight', 768), Images::KEEP_PHOTO_PROPORTIONAL)
                                                                    ->save();
                                                            } else {
                                                                @unlink($photoPath);
                                                            }
                                                        } else {
                                                            @unlink($photoPath);
                                                        }

                                                        unset($imageContent);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            if (is_dir($dir)) {
                                $path = Yii::getPathOfAlias('webroot.uploads.objects.' . $model->id . '.' . Images::ORIGINAL_IMG_DIR);
                                $pathMod = Yii::getPathOfAlias('webroot.uploads.objects.' . $model->id . '.' . Images::MODIFIED_IMG_DIR);
                                $oldUMask = umask(0);
                                if (!is_dir($path)) {
                                    @mkdir($path, 0777, true);
                                }
                                if (!is_dir($pathMod)) {
                                    @mkdir($pathMod, 0777, true);
                                }
                                umask($oldUMask);

                                if (is_writable($path) && is_writable($pathMod)) {
                                    touch($path . DIRECTORY_SEPARATOR . 'index.htm');
                                    touch($pathMod . DIRECTORY_SEPARATOR . 'index.htm');

                                    $files = getFilesNameArrayInPathWithoutHtml(Yii::getPathOfAlias('webroot.uploads.guestad.' . $filePathName . '.' . Images::ORIGINAL_IMG_DIR));

                                    if (count($files)) {
                                        Images::deleteByObjectId($model);

                                        $sorter = 1;
                                        foreach ($files as $file) {
                                            $imageModel = new Images;
                                            $imageModel->id_object = $model->id;
                                            $imageModel->id_owner = Yii::app()->user->id;
                                            $imageModel->file_name = $file;
                                            $imageModel->sorter = $sorter;

                                            if ($sorter == 1)
                                                $imageModel->is_main = 1;

                                            $imageModel->save(false);
                                            $sorter++;

                                            @copy($dir . DIRECTORY_SEPARATOR . Images::ORIGINAL_IMG_DIR . DIRECTORY_SEPARATOR . $file, Yii::getPathOfAlias('webroot.uploads.objects') . DIRECTORY_SEPARATOR . $model->id . DIRECTORY_SEPARATOR . Images::ORIGINAL_IMG_DIR . DIRECTORY_SEPARATOR . $file);
                                        }

                                        if (file_exists($dir)) {
                                            @rrmdir($dir);
                                        }
                                    }
                                }
                            }
                        }

                        if (issetModule('seasonalprices') && $model->type == Apartment::TYPE_RENT) {
                            if (!$model->is_price_poa && ($model->price && $model->price_type)) {
                                $prices = array();
                                $prices[] = array(
                                    'price' => $model->price,
                                    'price_type' => $model->price_type,
                                    'in_currency' => issetModule('currency') ? Currency::getDefaultCurrencyModel()->char_code : ''
                                );

                                if (is_array($prices) && !empty($prices)) {
                                    $sql = 'DELETE FROM {{seasonal_prices}} WHERE apartment_id="' . $model->id . '"';
                                    Yii::app()->db->createCommand($sql)->execute();

                                    foreach ($prices as $price) {
                                        $price_model = new Seasonalprices();
                                        $price_model->attributes = $price;
                                        $price_model->apartment_id = $model->id;
                                        $price_model->save(false);
                                    }
                                }
                            }
                        }

                        $result = array();
                        $result['object'] = 'apartment';
                        $result['count'] = 1;
                        $result['data'] = array('id' => $model->id);

                        $this->render('//modules/api/views/render_index', $result);
                    }

                    Yii::app()->user->setFlash('success', tc('Success'));
                    if ($model->isChild()) {
                        if (isset($model->parent) && $model->parent->owner_id == $model->owner_id && isset($model->parent->objType) && !empty($model->parent->objType)) {
                            Yii::app()->user->setFlash('success', tc('child_success_add_' . $model->parent->objType->id));

                            $this->redirect(array('update', 'id' => $model->parent_id));
                        }
                    }

                    if (isset($_POST['save_close_btn'])) {
                        $this->redirect(array('index'));
                    } else {
                        $this->redirect(array('update', 'id' => $model->id));
                    }
                }
            } else {
                $model->active = $originalActive;
            }
        }

        HApartment::getCategoriesForUpdate($model);

        if ($model->active == Apartment::STATUS_DRAFT) {
            Yii::app()->user->setState('menu_active', 'apartments.create');
            $this->render('create', array(
                'model' => $model,
                'supportvideoext' => ApartmentVideo::model()->supportExt,
                'supportvideomaxsize' => ApartmentVideo::model()->fileMaxSize,
                'seasonalPricesModel' => $seasonalPricesModel,
                'show' => $show,
                'supportdocumentext' => ApartmentDocuments::model()->supportExt,
            ));
            return;
        }

        $this->render('update', array(
                'model' => $model,
                'supportvideoext' => ApartmentVideo::model()->supportExt,
                'supportvideomaxsize' => ApartmentVideo::model()->fileMaxSize,
                'seasonalPricesModel' => $seasonalPricesModel,
                'show' => $show,
                'supportdocumentext' => ApartmentDocuments::model()->supportExt,
            )
        );
    }

    public function actionDrafts()
    {
        Yii::app()->user->setState('searchUrl', NULL);

        $model = new Apartment('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Apartment'])) {
            $model->attributes = $_GET['Apartment'];
        }
        $model = $model->onlyAuthOwner()->notDeleted()->drafts();
        $model->scopeChilds('grid');

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('index_drafts', array('model' => $model), false, true);
        } else {
            $this->render('index_drafts', array('model' => $model));
        }
    }

    public function actionDelete($id)
    {
        $model = $this->loadModelUserAd($id);

        if ($this->_isAPICall) {
            if ($this->isDelete()) {
                $model->delete();

                $result = array();
                $result['object'] = 'apartment';
                $result['count'] = 1;
                $result['data'] = array('id' => $model->id);

                $this->render('//modules/api/views/render_index', $result);
            } else {
                throw new CHttpException(400, Yii::t('app', 'Invalid DELETE request'));
            }
        } else {
            $model->delete();
            $this->redirect(isset($_GET['returnUrl']) ? $_GET['returnUrl'] : array('index'));
        }
    }

    public function actionClone($id)
    {
        if (param("enableUserAdsCopy", 0)) {
            $this->loadModelUserAd($id)->makeClone();
            // if AJAX request (triggered by deletion via grid view), we should not redirect the browser
            if (!Yii::app()->request->isAjaxRequest) {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
            }
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    public function actionGmap($id)
    {
        $model = $this->loadModelUserAd($id);

        $result = CustomGMap::actionGmap($id, $model, $this->renderPartial('//../modules/apartments/views/backend/_marker', array('model' => $model), true));
        if ($result) {
            return $this->renderPartial('//../modules/apartments/views/backend/_gmap', $result, true);
        }
    }

    public function actionYmap($id)
    {
        $model = $this->loadModelUserAd($id);

        $result = CustomYMap::init()->actionYmap($id, $model, $this->renderPartial('//../modules/apartments/views/backend/_marker', array('model' => $model), true));
        if ($result) {
            return $this->renderPartial('//../modules/apartments/views/backend/_ymap', $result, true);
        }
    }

    public function actionOSmap($id)
    {
        $model = $this->loadModelUserAd($id);

        $result = CustomOSMap::actionOsmap($id, $model, $this->renderPartial('//../modules/apartments/views/backend/_marker', array('model' => $model), true));
        if ($result) {
            return $this->renderPartial('//../modules/apartments/views/backend/_osmap', $result, true);
        }
    }

    public function actionSavecoords($id)
    {
        if (param('useGoogleMap', 1) || param('useYandexMap', 1) || param('useOSMMap', 1)) {
            $apartment = $this->loadModelUserAd($id);
            if (isset($_POST['lat']) && isset($_POST['lng'])) {
                $apartment->lat = floatval($_POST['lat']);
                $apartment->lng = floatval($_POST['lng']);
                $apartment->update(array('lat', 'lng'));
            }
            Yii::app()->end();
        }
    }

    public function actionView($id = 0, $url = '')
    {
        $this->redirect(array('/apartments/main/view', 'id' => $id));
    }

    public function actionChooseNewOwner()
    {
        $apId = intval(Yii::app()->request->getParam('id'));

        if (!$apId || !Yii::app()->user->type == User::TYPE_AGENCY)
            throw404();

        $modelApartment = Apartment::model()->findByPk($apId);
        if (!$modelApartment || !$modelApartment->isOwner())
            throw404();

        $model = new ChangeOwner;


        $modelUser = new User('search');
        $modelUser->meAndMyAgents()->active();
        $modelUser->unsetAttributes();  // clear any default values
        if (isset($_GET['User'])) {
            $modelUser->attributes = $_GET['User'];
        }


        if (Yii::app()->request->isPostRequest) {
            if (isset($_POST)) {
                $model->futureOwner = (isset($_POST['itemsSelected']) && isset($_POST['itemsSelected'][0])) ? $_POST['itemsSelected'][0] : '';
                $model->futureApartments = array($apId);

                if ($model->validate()) {
                    $sql = 'UPDATE {{apartment}} SET owner_id=:ownerId WHERE id=:apId';
                    Yii::app()->db->createCommand($sql)->execute(array(':ownerId' => $model->futureOwner, ':apId' => $apId));

                    $sql = 'UPDATE {{images}} SET id_owner=:ownerId WHERE id=:apId';
                    Yii::app()->db->createCommand($sql)->execute(array(':ownerId' => $model->futureOwner, ':apId' => $apId));

                    Yii::app()->cache->flush();

                    Yii::app()->user->setFlash('success', tc('Success'));

                    Yii::app()->controller->redirect(array('/usercpanel/main/index'));
                }
            }
        }

        $this->render('change_owner', array(
            'apId' => $apId,
            'model' => $model,
            'modelApartment' => $modelApartment,
            'modelUser' => $modelUser,
        ));
    }
}
