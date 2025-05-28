<?php
require_once(dirname(__FILE__) . '/../helpers/common.php');
require_once(dirname(__FILE__) . '/../helpers/strings.php');

Yii::setPathOfAlias('editable', dirname(__FILE__) . '/../extensions/x-editable');

$preload = array(
    'log',
    'configuration'
);
if (YII_DEBUG) {
    $preload[] = 'debug';
}
$config = array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Open Real Estate',
    'sourceLanguage' => 'en',
    'language' => 'en',
    'theme' => 'atlas',
    'preload' => $preload,
    'aliases' => array(
        'bootstrap' => 'ext.bootstrap',
    ),
    'onBeginRequest' => array('BeginRequest', 'updateStatusAd'),
    // autoloading model and component classes
    'import' => array(
        'editable.*',
        'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.setReturnUrl.ESetReturnUrlFilter',
        'ext.idna.IdnaConvert',
        'ext.select2.Select2',
        'ext.chosen.Chosen',
        'bootstrap.behaviors.*',
        'bootstrap.helpers.*',
        'bootstrap.widgets.*',
        'application.models.*',
        'application.components.*',
        'application.components.traits.*',
        'application.helpers.*',
        'application.modules.configuration.components.*',
        'application.modules.notifier.components.Notifier',
        'application.modules.booking.models.*',
        'application.modules.comments.models.Comment',
        'application.modules.comments.models.CommentForm',
        'application.modules.windowto.models.WindowTo',
        'application.modules.apartments.models.*',
        'application.modules.entries.models.*',
        'application.extensions.image.Image',
        'application.modules.selecttoslider.models.SelectToSlider',
        'application.modules.similarads.models.SimilarAds',
        'application.modules.menumanager.models.*',
        'application.modules.referencecategories.models.ReferenceCategories',
        'application.modules.apartments.components.*',
        'application.modules.apartmentCity.models.ApartmentCity',
        'application.modules.apartmentObjType.models.ApartmentObjType',
        'application.modules.translateMessage.models.TranslateMessage',
        'application.components.behaviors.ERememberFiltersBehavior',
        'application.modules.service.models.Service',
        'application.modules.antispam.components.*',
        'application.modules.antispam.components.yiiReCaptcha.*',
        'application.modules.images.models.*',
        'application.modules.images.components.*',
        'application.modules.lang.models.*',
        'zii.behaviors.CTimestampBehavior',
        'application.modules.apartmentsComplain.models.ApartmentsComplain',
        'application.modules.apartmentsComplain.models.ApartmentsComplainReason',
        'application.modules.comparisonList.models.ComparisonList',
        'application.modules.articles.models.Article',
        'application.modules.infopages.models.InfoPages',
        'application.modules.reviews.models.Reviews',
        'application.modules.bookingtable.models.Bookingtable',
        'application.modules.bookingtable.models.HBooking',
        'application.modules.themes.models.Themes',
        'application.modules.clients.models.Clients',
        'application.modules.formdesigner.models.*',
        'application.modules.blockIp.models.BlockIp',
        'application.modules.customHtml.models.CustomHtml',
        'application.modules.users.models.*',
        'application.modules.badwords.models.Badwords',
    ),
    'modules' => array(
        'entries',
        'referencecategories',
        'referencevalues',
        'apartments',
        'apartmentObjType',
        'apartmentCity',
        'comments',
        'booking',
        'windowto',
        'contactform',
        'articles',
        'usercpanel',
        'users',
        'quicksearch',
        'configuration',
        'timesin',
        'timesout',
        'adminpass',
        'specialoffers',
        'install',
        'selecttoslider',
        'similarads',
        'menumanager',
        'userads',
        'translateMessage',
        'service',
        'antispam',
        'rss',
        'images',
        'apartmentsComplain',
        'formdesigner',
        'comparisonList',
        'guestad',
        'reviews',
        'bookingtable',
        'modules',
        'infopages',
        'themes',
        'notifier',
        'clients',
        'blockIp',
        'stats',
        'customHtml',
        'loanCalculator',
        'favorite',
        'badwords',

    // uncomment the following to enable the Gii tool
    /* 'gii'=>array(
      'class'=>'system.gii.GiiModule',
      'password'=>'admin1',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters'=>array('127.0.0.1','::1'),
      'generatorPaths'=>array(
      'bootstrap.gii', // since 0.9.1
      ),
      ), */
    ),
    'controllerMap' => array(
        'min' => array(
            'class' => 'ext.minScript.controllers.ExtMinScriptController',
        ),
    ),
    'components' => array(
        //X-editable config
        'editable' => array(
            'class' => 'editable.EditableConfig',
            'form' => 'plain', //form style: 'bootstrap', 'jqueryui', 'plain'
            'mode' => 'popup', //mode: 'popup' or 'inline'
            'defaults' => array(//default settings for all editable elements
                'emptytext' => 'Click to edit'
            )
        ),
        'loid' => array(
            'class' => 'application.extensions.lightopenid.loid',
        ),
        'eauth' => array(
            // yii-eauth-1.1.8
            'class' => 'ext.eauth.EAuth',
            'popup' => true, // Use popup windows instead of redirect to site of provider
        ),
        'user' => array(
            'class' => 'WebUser',
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => array('login'),
        ),
        'configuration' => array(
            'class' => 'Configuration',
            'cachingTime' => 0, // caching configuration for 180 days
        ),
        'cache' => array(
            'class' => 'system.caching.CFileCache',
        /* 'class'=>'system.caching.CMemCache',
          //'useMemcached' => true,
          'servers'=>array(
          array('host'=>'127.0.0.1', 'port'=>11211),
          ), */
        ),
        'request' => array(
            'class' => 'application.components.CustomHttpRequest',
            'enableCsrfValidation' => true,
            'noCsrfValidationRoutes' => array(
                // иначе при возврате с платежки (PayPal) "The CSRF token could not be verified."
                '^payment/main/income.*$',
                'payment/income/income',
            ),
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'class' => 'application.components.CustomUrlManager',
        ),
        'mailer' => array(
            'class' => 'application.extensions.YiiMailer.YiiMailer',
        ),
        //'db'=>require(dirname(__FILE__) . '/db.php'),
        'errorHandler' => array(
            'errorAction' => YII_DEBUG ? null : 'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>(YII_DEBUG) ? array(
                array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'application',
                    'levels'=>'error, warning, trace, profile, info',
                ),
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ) : array(),
        ),
        'debug' => array(
            'class' => 'ext.yii2-debug.Yii2Debug',
            'allowedIPs' => array('127.0.0.1', '::1', '172.18.1.1', '172.18.0.1'),
            'enabled' => YII_DEBUG,
        ),
        'messages' => array(
            'class' => 'DbMessageSource',
            'forceTranslation' => true,
            'onMissingTranslation' => array('CustomEventHandler', 'handleMissingTranslation'),
        ),
        'messagesInFile' => array(
            'class' => 'CPhpMessageSource',
            'forceTranslation' => true,
        ),
        'bootstrap' => array(
            'class' => 'bootstrap.components.BsApi', // assuming you extracted bootstrap under extensions
        ),
        'reCaptcha' => array(
            'name' => 'reCaptcha',
            'class' => 'application.modules.antispam.components.yiiReCaptcha.ReCaptcha',
            'key' => 'dev', # https://www.google.com/recaptcha/admin#createsite
            'secret' => 'dev',
        ),
    ),
    'params' => array(
        'module_rss_itemsPerFeed' => 20,
        'allowedImgExtensions' => array('jpg', 'jpeg', 'gif', 'png'),
        'allowedImgMimeTypes' => array('image/gif', 'image/jpeg', 'image/png'),
        'watermarkFileTypes' => 'gif, png, jpg',
        'maxImgFileSize' => 8 * 1024 * 1024, // maximum file size in bytes
        'minImgFileSize' => 5 * 1024, // min file size in bytes
        'langToInstall' => 'en',
        'countListingsInComparisonList' => 6, # максимум объявлений в списке сравнения
        'searchMaxField' => 15, // максимальное кол-во полей в поиске,
        'useMinify' => true,
        'useLangPrefixIfOneLang' => 0, // использовать префикс языка в url если активен только 1 язык
        'booking_max_guest' => 10, // максимальное кол-во гостей при бронировнии
    ),
);

$addons['components'] = array(
    'session' => array(
        'class' => 'CDbHttpSession',
        'connectionID' => 'db',
        'sessionTableName' => '{{users_sessions}}',
        'autoCreateSessionTable' => false, //!!!
    ),
    'clientScript' => array(
        'class' => 'ext.minScript.components.ExtMinScript',
        'minScriptLmCache' => (YII_DEBUG) ? 0 : 3600,
        'minScriptDisableMin' => array(
            '/[-\.]min\.(?:js|css)$/i',
            '/[-\.]pack\.(?:js|css)$/i',
            '/bootstrap.js$/i', 
            '/jquery.js$/i', 
            '/jquery.panorama360.js$/i', 
            '/ckeditor.js$/i', 
            #'/listview/i',
            #'/generate/i'
        ),
    ),
);

$addons['import'] = array(
    'application.modules.configuration.models.ConfigurationModel',
);

if (oreInstall::isInstalled()) {
    $config = CMap::mergeArray($config, $addons);
}

$db = require(dirname(__FILE__) . '/db.php');
if ($db === 1) {
    $db = array();
}

return CMap::mergeArray($config, $db);
