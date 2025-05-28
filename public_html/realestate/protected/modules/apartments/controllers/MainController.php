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

    public $modelName = 'Apartment';

    public function actions()
    {
        $return = array();
        if (param('useJQuerySimpleCaptcha', 0)) {
            $return['captcha'] = array(
                'class' => 'jQuerySimpleCCaptchaAction',
                'backColor' => 0xFFFFFF,
            );
        } else {
            $return['captcha'] = array(
                'class' => 'MathCCaptchaAction',
                'backColor' => 0xFFFFFF,
            );
        }

        return $return;
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

    public function actionIndex()
    {
        if (!$this->_isAPICall) {
            throw new CHttpException(404, tc('The requested page does not exist.'));
        } else {
            $criteria = SearchHelper::getCriteriaForMainSearch();

            $result = \application\modules\apartments\helpers\ApartmentsHelper::getApartments(500, 0, 0, $criteria, true);

            $apartments = HApartment::findAllWithCache($result['criteria']);

            if (!empty($apartments)) {
                foreach ($apartments as $key => &$item) {
                    if (isset($item->images) && !empty($item->images)) {
                        $imagesList = array();
                        $imagesList['imgCount'] = $item->count_img;
                        foreach ($item->images as $image) {
                            $imagesList[] = array(
                                'file_name_path' => Images::getFullSizeUrl($image)
                            );
                        }

                        /* @var $item Apartment */
                        $item->setAttribute('imagesList', $imagesList);
                    }
                    unset($item);
                }
            }

            //$result['model'] = new Apartment;
            $result['data'] = $apartments;
            $result['count'] = $result['apCount'];
            $result['showCount'] = false;
            $result['customWidgetTitle'] = $result['callFromWidget'] = $result['isH1Widget'] = '';

            HSite::setCanonicalTag();

            $this->render('widgetApartments_list', $result);
        }
    }

    public function actionView($id = 0, $url = '', $printable = 0)
    {
        //$this->showSearchForm = false;
        $this->htmlPageId = 'viewlisting';

        $lastNews = $lastArticles = array();
        $apartmentVisitCount = $apartment = NULL;

        $seo = NULL;
        if (($id || $url) && issetModule('seo')) {
            $url = $url ? $url : $id;
            $seo = SeoFriendlyUrl::getForView($url, $this->modelName);

            if ($seo) {
                $this->setSeo($seo);
                $id = $seo->model_id;
            }
        }

        if ($id) {
            $with = array('windowTo', 'objType', 'images');
            if (issetModule('seo')) {
                $with = CMap::mergeArray($with, array('images.images_seo'));
            }
            if (issetModule('seasonalprices')) {
                $with = CMap::mergeArray($with, array('seasonalPrices'));
            }
            if (issetModule('paidservices')) {
                $with = CMap::mergeArray($with, array('paidActiveDisableSimilarListings'));
            }
            //$apartment = Apartment::model()->with($with)->findByPk($id); # запросов меньше, но медленнее
            $apartment = Apartment::model()->findByPk($id);
        }

        if (!$apartment) {
            throw404();
        }

        /* @var $apartment Apartment */

        // избавляемся от дублей
        $apartmentUrl = $apartment->getUrl(false);
        if (!$printable && !$this->_isAPICall && issetModule('seo') && $apartment->seo && strpos(Yii::app()->request->url, $apartmentUrl) !== 0) {
            $this->redirect($apartmentUrl, true, 301);
        }

        if($printable){
            Yii::app()->clientScript->registerLinkTag('canonical', null, $apartmentUrl);
        }

        if (issetModule('seasonalprices')) {
            if (!in_array($apartment->type, HApartment::availableApTypesIds())) {
                throw404();
            }
        } else {
            if (!in_array($apartment->type, HApartment::availableApTypesIds()) || (!in_array($apartment->price_type, array_keys(HApartment::getPriceArray(Apartment::PRICE_SALE, true))) && !$apartment->is_price_poa)) {
                throw404();
            }
        }

        if ($apartment->owner_id != 1 && $apartment->owner_active == Apartment::STATUS_INACTIVE) {
            if (!(isset(Yii::app()->user->id) && $apartment->isOwner()) && !Yii::app()->user->checkAccess('backend_access')) {
                Yii::app()->user->setFlash('notice', tt('apartments_main_index_propertyNotAvailable', 'apartments'));
                throw404();
            }
        }

        if (($apartment->active == Apartment::STATUS_INACTIVE || $apartment->active == Apartment::STATUS_MODERATION) && !Yii::app()->user->checkAccess('backend_access') && !(isset(Yii::app()->user->id) && $apartment->isOwner())) {
            Yii::app()->user->setFlash('notice', tt('apartments_main_index_propertyNotAvailable', 'apartments'));
            //$this->redirect(Yii::app()->homeUrl);
            throw404();
        }

        if ($apartment->active == Apartment::STATUS_MODERATION && $apartment->owner_active == Apartment::STATUS_ACTIVE && $apartment->isOwner()) {
            Yii::app()->user->setFlash('error', tc('Awaiting moderation'));
        }

        if ($apartment->deleted) {
            Yii::app()->user->setFlash('error', tt('Listing is deleted', 'apartments'));
        }

        if($apartment->is_free_to){
            $dateFree = CDateTimeParser::parse($apartment->is_free_to, 'yyyy-MM-dd');
            if ($dateFree && $dateFree < (time() - 60 * 60 * 24)) {
                $apartment->is_special_offer = 0;
                $apartment->update(array('is_special_offer'));
            }
        }

        if (!Yii::app()->request->isAjaxRequest && !$this->_isAPICall) {
            $ipAddress = Yii::app()->request->userHostAddress;
            $userAgent = Yii::app()->request->userAgent;
            Apartment::setApartmentVisitCount($apartment, $ipAddress, $userAgent);
        }

        if (!$this->_isAPICall) {
            $lastNews = Entries::getLastNews();
            $lastArticles = Article::getLastArticles();
            $apartmentVisitCount = Apartment::getApartmentVisitCount($apartment);
        } else {
            $imagesList = array();
            $imagesList['imgCount'] = $apartment->count_img;
            foreach ($apartment->images as $image) {
                $imagesList[] = array(
                    'file_name_path' => Images::getFullSizeUrl($image)
                );
            }
            $apartment->setAttribute('imagesList', $imagesList);
        }

        #######################################################################
        # для соц. кнопок
        if (!$this->_isAPICall) {
            if ($apartment->getStrByLang("title"))
                Yii::app()->clientScript->registerMetaTag(strip_tags($apartment->getStrByLang("title")), null, null, array('property' => 'og:title'));

            if ($apartment->getStrByLang("description"))
                Yii::app()->clientScript->registerMetaTag(truncateText(strip_tags($apartment->getStrByLang("description")), 50), null, null, array('property' => 'og:description'));

            Yii::app()->clientScript->registerMetaTag($apartment->getUrl(), null, null, array('property' => 'og:url'));
            Yii::app()->clientScript->registerMetaTag('website', null, null, array('property' => 'og:type'));

            if (Yii::app()->theme->name == Themes::THEME_ATLAS_NAME)
                $res = Images::getMainThumb(640, 400, $apartment->images);
            else
                $res = Images::getMainThumb(300, 200, $apartment->images);

            if (isset($res['thumbUrl']) && $res['thumbUrl']) {
                Yii::app()->clientScript->registerMetaTag($res['thumbUrl'], null, null, array('property' => 'og:image'));
                Yii::app()->clientScript->registerLinkTag('image_src', null, $res['thumbUrl']);
            }
        }
        #######################################################################

        if (issetModule('metroStations')) {
            $apartment->metroStationsTitle = MetroStations::getApartmentStationsTitle($apartment->id);
        }

        if ($printable) {
            $this->layout = '//layouts/print';
            $this->render('view_print', array(
                'model' => $apartment,
                'phone' => self::getPhoneImageTag($apartment->id)
            ));
        } else {
            if (Yii::app()->request->isAjaxRequest) {
                $this->renderPartial('view', array(
                    'model' => $apartment,
                    'statistics' => $apartmentVisitCount,
                    'lastEntries' => $lastNews,
                    'lastArticles' => $lastArticles,
                ));
            } else {
                HSite::setCanonicalTag();

                $this->render('view', array(
                    'model' => $apartment,
                    'statistics' => $apartmentVisitCount,
                    'lastEntries' => $lastNews,
                    'lastArticles' => $lastArticles,
                ));
            }
        }
    }

    public function actionGmap($id, $model = null)
    {
        if ($model === null) {
            $model = $this->loadModel($id);
        }
        $result = CustomGMap::actionGmap($id, $model, $this->renderPartial('//../modules/apartments/views/backend/_marker', array('model' => $model), true), true);

        if ($result) {
            return $this->renderPartial('backend/_gmap', $result, true);
        }
        return '';
    }

    public function actionYmap($id, $model = null)
    {
        if ($model === null) {
            $model = $this->loadModel($id);
        }
        $result = CustomYMap::init()->actionYmap($id, $model, $this->renderPartial('//../modules/apartments/views/backend/_marker', array('model' => $model), true));

        if ($result) {
            //return $this->renderPartial('backend/_ymap', $result, true);
        }
        return '';
    }

    public function actionOSmap($id, $model = null)
    {
        if ($model === null) {
            $model = $this->loadModel($id);
        }
        CustomOSMap::actionOSmap($id, $model, $this->renderPartial('//../modules/apartments/views/backend/_marker', array('model' => $model), true));

//        if ($result) {
//            return $this->renderPartial('backend/_osmap', $result, true);
//        }
    }

    public function actionGeneratePhone($id = null, $width = 160, $font = 3)
    {
        if (Yii::app()->request->isAjaxRequest ) {
            if ($id) {
                echo self::getPhoneImageTag($id, $width, $font);
            }
        }

        Yii::app()->end();
    }

    public static function getPhoneImageTag($id = null, $width = 160, $font = 3) {
        $userInfo = $phone = null;
        $from = Yii::app()->request->getParam('from');

        if ($from == 'userlist') {
            if (param('useShowUserInfo')) {
                $userInfo = User::model()->with(array('countAdRel'))->findByPk($id, array('select' => 'phone'));

                # показываем телефон только если есть объекты
                if (!empty($userInfo) && ($userInfo->countAdRel > 0) && isset($userInfo->phone)) {
                    $phone = $userInfo->phone;
                }
            }
        } else {
            $apartmentInfo = Apartment::model()->findByPk($id, array('select' => 'owner_id, phone, parse_owner_info_phone'));

            if (!empty($apartmentInfo)) {
                if ($apartmentInfo->parse_from && $apartmentInfo->parse_owner_info_phone) {
                    $phone = $apartmentInfo->parse_owner_info_phone;
                } else {
                    $phone = $apartmentInfo->phone;
                }
            }

            if (!$phone && param('useShowUserInfo')) {
                if (!empty($apartmentInfo) && isset($apartmentInfo->owner_id)) {
                    $userInfo = User::model()->with(array('countAdRel'))->findByPk($apartmentInfo->owner_id, array('select' => 'phone'));
                } else {
                    $userInfo = User::model()->with(array('countAdRel'))->findByPk($id, array('select' => 'phone'));
                }

                # показываем телефон только если есть объекты
                if (!empty($userInfo) && ($userInfo->countAdRel > 0) && isset($userInfo->phone)) {
                    $phone = $userInfo->phone;
                }
            }
        }

        if (!$phone)
            $phone = '---';

        if ($phone) {
            $image = imagecreate($width, 20);
            imagecolorallocate($image, 255, 255, 255);
            $textcolor = imagecolorallocate($image, 37, 75, 137); //Yii::getPathOfAlias('webroot.protected.modules.apartments.font').'/tahoma.ttf'

            imagettftext($image, 11, 0, 0, 14, $textcolor, Yii::getPathOfAlias('webroot.protected.modules.apartments.font') . '/tahoma.ttf', $phone);

            if (ob_get_contents())
                ob_clean();

            ob_start();
            imagepng($image);
            imagedestroy($image);
            $rawPhone = ob_get_clean();

            return CHtml::tag(
                'noindex', array(), CHtml::link(
                    CHtml::image(
                        'data:image/png;base64,' . base64_encode($rawPhone) . '', tt('Owner phone', 'apartments')
                    ), 'tel:' . preparePhoneToCall($phone), array(
                        'itemprop' => 'telephone',
                        'class' => 'tel',
                        'rel' => 'nofollow',
                        'title' => tt('Owner phone', 'apartments')
                    )
                )
            );
        }
    }

    public function actionAllListings()
    {
        $userId = (int)Yii::app()->request->getParam('id');
        if ($userId) {
            $this->userListingId = $userId;

            $data = HUser::getDataForListings($userId);

            // find count
            $apCount = Apartment::model()->count($data['criteria']);

            if (Yii::app()->request->isAjaxRequest) {
                $this->renderPartial('_user_listings', array(
                    'criteria' => $data['criteria'],
                    'apCount' => $apCount,
                    'username' => $data['userName'],
                ));
            } else {
                $this->render('_user_listings', array(
                    'criteria' => $data['criteria'],
                    'apCount' => $apCount,
                    'username' => $data['userName'],
                ));
            }
        }
    }

    public function actionSendEmail($id)
    {
        $apartment = Apartment::model()->findByPk($id);

        if (!$apartment) {
            throw404();
        }

        if (!param('use_module_request_property'))
            throw404();

        $model = new SendMailForm;

        if (isset($_POST['SendMailForm'])) {
            $model->attributes = $_POST['SendMailForm'];

            if (!Yii::app()->user->isGuest) {
                $model->senderEmail = Yii::app()->user->email;
                $model->senderName = Yii::app()->user->username;
            }

            $model->ownerId = $apartment->user->id;
            $model->ownerEmail = $apartment->user->email;
            $model->ownerName = $apartment->user->username;

            $model->apartmentUrl = $apartment->getUrl();

            if ($model->validate()) {
                $notifier = new Notifier;
                $notifier->raiseEvent('onRequestProperty', $model, array('forceEmail' => $model->ownerEmail, 'replyTo' => $model->senderEmail));

                Yii::app()->user->setFlash('success', tt('Thanks_for_request', 'apartments'));
                $model = new SendMailForm; // clear fields
            } else {
                $model->unsetAttributes(array('verifyCode'));
                Yii::app()->user->setFlash('error', tt('Error_send_request', 'apartments'));
            }
        }

        if (Yii::app()->request->isAjaxRequest) {
            //Yii::app()->clientscript->scriptMap['*.js'] = false;
            Yii::app()->clientscript->scriptMap['jquery.js'] = false;
            Yii::app()->clientscript->scriptMap['jquery.min.js'] = false;
            Yii::app()->clientscript->scriptMap['jquery-ui.min.js'] = false;

            $this->renderPartial('send_email', array(
                'apartment' => $apartment,
                'isFancy' => true,
                'model' => $model,
            ), false, true);
        } else {
            $this->render('send_email', array(
                'apartment' => $apartment,
                'isFancy' => false,
                'model' => $model,
            ));
        }
    }

    public function actionSavecoords($id)
    {
        if (param('useGoogleMap', 1) || param('useYandexMap', 1) || param('useOSMMap', 1)) {
            $apartment = $this->loadModel($id);
            if (isset($_POST['lat']) && isset($_POST['lng'])) {
                $apartment->lat = (float)$_POST['lat'];
                $apartment->lng = (float)$_POST['lng'];
                $apartment->save(false);
            }
            Yii::app()->end();
        }
    }

    public function actionGetVideoFile()
    {
        $id = (int)Yii::app()->request->getParam('id');
        $apId = (int)Yii::app()->request->getParam('apId');

        if ($id && $apId) {
            $sql = 'SELECT video_file, video_html
					FROM {{apartment_video}}
					WHERE id = "' . $id . '"
					AND apartment_id = "' . $apId . '"';

            $result = Yii::app()->db->createCommand($sql)->queryRow();

            if ($result['video_file']) {
                $this->renderPartial('_video_file', array(
                    'video' => $result['video_file'],
                    'apartment_id' => $apId,
                    'id' => $id,
                ), false, true
                );
            } elseif ($result['video_html']) {
                echo CHtml::decode($result['video_html']);
            }
        }
    }

    public function actionGetParentObject()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw404();
        }
        if(!isset($_GET['q'])){
            return;
        }

        $q = filter_var($_GET['q'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $objTypeID = (int)Yii::app()->request->getParam('objTypeID');

        if ($q && $objTypeID) {
            $user = HUser::getModel();
            $addWhere = '';

            if (!param('parentIdAll') && !in_array($user->role, array(User::ROLE_ADMIN, User::ROLE_MODERATOR))) {
                $addWhere = " AND owner_id = " . Yii::app()->user->id;
            }

            $sql = "
                    SELECT id, title_" . Yii::app()->language . " AS title, address_" . Yii::app()->language . " AS address FROM {{apartment}} 
                    WHERE 
                    obj_type_id=:obj_id 
                    AND (id LIKE :keyword OR title_" . Yii::app()->language . " LIKE :keyword OR address_" . Yii::app()->language . " LIKE :keyword)
                    " . $addWhere . " 
                    LIMIT 30";
            $list = Yii::app()->db->createCommand($sql)->queryAll(
                true, array(
                    ':obj_id' => $objTypeID,
                    ':keyword' => '%' . strtr($q, array('%' => '\%', '_' => '\_', '\\' => '\\\\')) . '%',
                )
            );

            $returnVal = '';
            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    $data = array(
                        '{id}' => tt('ID', 'apartments') . ':' . $value['id'],
                        '{title}' => $value['title'],
                        '{address}' => $value['address'],
                    );
                    $returnVal .= strtr(Apartment::$_parentAutoCompleteTemplate, $data) . '|' . $value['id'] . "\n";
                }
            }

            unset($list);
            echo $returnVal;
        }
    }

    public function actionLoadLocationData()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            throw404();
        }

        $id = Yii::app()->request->getParam('id');

        if($id == null){
            HAjax::jsonError('Not id');
        }

        $model = new Apartment();
        $model->parent_id = $id;
        $model->obj_type_id = Yii::app()->request->getParam('obj_type_id');

        HAjax::jsonOk('', [
            'locationHtml' => $this->renderPartial('//../modules/apartments/views/backend/fields/location', [
                'model' => $model,
                'form' => new CustomForm()
            ], true),
        ]);
    }

    public function actionViewDetailsViewsStats()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = (int)Yii::app()->request->getParam('id');
            $apartment = Apartment::model()->findByPk($id);
            if ($apartment) {
                if ($apartment->isOwner(true)) {
                    $forDays = 7;
                    $maxVal = 0;

                    for ($i = 0; $i < $forDays; $i++) {
                        $day = date("Y-m-d", strtotime('-' . $i . ' days'));
                        $periodArr[] = $day;
                        $searchDayString[] = 'date_created = "' . $day . '"';
                    }

                    $dataStats = array();

                    $sql = 'SELECT COUNT(id) as count, STR_TO_DATE(date_created, "%Y-%m-%d") as date_created2 FROM {{apartment_statistics}} WHERE apartment_id = ' . $apartment->id . ' GROUP BY date_created2 HAVING date_created2 >= CURDATE() - INTERVAL ' . $forDays . ' DAY';
                    $resStats = Yii::app()->db->createCommand($sql)->queryAll();
                    if (!empty($resStats) && is_array($resStats)) {
                        $resStats = CHtml::listData($resStats, 'date_created2', 'count');
                    }

                    $dataStats[0] = array('', tt('Views', 'apartments'), array('role' => 'annotation'));
                    $i = 1;
                    $periodArr = array_reverse($periodArr);
                    foreach ($periodArr as $day) {
                        $dataStats[$i][0] = Yii::app()->dateFormatter->format('d MMMM', CDateTimeParser::parse($day, 'yyyy-MM-dd'));

                        $value = array_key_exists($day, $resStats) ? (int)$resStats[$day] : 0;

                        $maxVal = ($maxVal < $value) ? $value : $maxVal;

                        $dataStats[$i][1] = $value;
                        $dataStats[$i][2] = $value;
                        $i++;
                    }

                    $this->excludeJs();
                    $this->renderPartial('view_details_stats', array(
                        'apartment' => $apartment,
                        'dataStats' => $dataStats,
                        'resStats' => $resStats,
                        'maxVal' => $maxVal,
                    ), false, true);
                    Yii::app()->end();
                }
            }
        }

        throw404();
    }

    public function actionGetGeo($id = 0)
    {
        $address = Yii::app()->request->getParam('address');

        if ($address) {
            $coords = Geocoding::getCoordsByAddress($address, '');
            // если нужно принудительно использовать гугл геокодр
            // $coords = Geocoding::getCoordsByAddress($address, false, true);

            if (isset($coords['lat']) && isset($coords['lng'])) {
                if ($id) {
                    $apartment = $this->loadModel($id);
                    if ($apartment) {
                        $apartment->lat = floatval($coords['lat']);
                        $apartment->lng = floatval($coords['lng']);
                        $apartment->update(array('lat', 'lng'));
                    }
                }

                HAjax::jsonOk(tc('Coordinates found'), $coords);
            } else {
                HAjax::jsonError(tc('Coordinates not found'), $coords);
            }
        }

        HAjax::jsonError();
    }

    public function actionRemoveGeo($id = 0)
    {
        if ($id) {
            $apartment = $this->loadModel($id);
            if ($apartment) {
                $apartment->lat = 0;
                $apartment->lng = 0;
                $apartment->update(array('lat', 'lng'));
                HAjax::jsonOk(tc('Coordinates remowed'));
            }
        }

        HAjax::jsonError();
    }
}
