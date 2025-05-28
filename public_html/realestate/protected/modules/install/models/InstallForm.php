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

class InstallForm extends CFormModel
{
    public $template;
    public $agreeLicense;
    public $supportLicense;
    public $dbHost = 'localhost';
    public $dbPort = '3306';
    public $dbUser = 'root';
    public $dbPass;
    public $dbName;
    public $dbPrefix;
    public $language;
    public $adminName;
    public $adminPass;
    public $adminEmail;
    public $siteName = 'Open Real Estate CMS';
    public $siteKeywords = 'Open Real Estate CMS - Keywords';
    public $siteDescription = 'Open Real Estate CMS - Description';

    public function init()
    {
        $this->language = Yii::app()->language;
        $this->dbPrefix = 'ore_' . InstallForm::randomDBPrefix(2) . '_';
        return parent::init();
    }

    public static $_activeLangs = array('en' => 'en', 'ru' => 'ru', 'de' => 'de', 'es' => 'es', 'ar' => 'ar', 'tr' => 'tr');

    public function getLangs()
    {
        return array(
            'ru' => 'Russian / Русский / Russisch / Ruso / الروسية / Rus',
            'en' => 'English / Английский / Englisch / Inglés / الإنجليزية / İngiliz',
            'de' => 'German / Немецкий / Deutsch / Alemán / ألماني / Alman',
            'es' => 'Spanish / Испанский / Spanisch / Español / الأسبانية / İspanyol',
            'ar' => 'Arab / Арабский / Arabisch / Árabe / العربية / Arap',
            'tr' => 'Turkish / Турецкий / Türkisch / Turca / تركي / Türk',
        );
    }

    public function rules()
    {
        return array(
            array('template, dbUser, dbHost, dbName, adminPass, adminEmail, adminName, dbPrefix, siteName', 'required'),
            array('agreeLicense', 'required', 'requiredValue' => true, 'message' => tFile::getT('module_install', 'You should agree with "The license agreement"')),
            array('supportLicense', 'required', 'requiredValue' => true, 'message' => tFile::getT('module_install', 'You should agree with "The support services"')),
            array('adminEmail', 'email'),
            array('template', 'in', 'range' => Themes::getAllTemplatesList()),
            array('dbUser, dbPass, dbName', 'length', 'max' => 30),
            array('dbHost', 'length', 'max' => 50),
            array('adminPass', 'length', 'max' => 20, 'min' => 6),
            array('dbPort', 'length', 'max' => 5),
            array('dbPort', 'numerical', 'allowEmpty' => true, 'integerOnly' => true),
            array('dbPrefix', 'length', 'max' => 7, 'min' => 1),
            array('dbPrefix', 'match', 'pattern' => '#^[a-zA-Z0-9_]{1,7}$#', 'message' => tFile::getT('module_install', 'It is allowed to use the characters "a-zA-Z0-9_" without spaces')),
            array('dbPrefix, dbPort, siteName, siteKeywords, siteDescription', 'safe'),
            array('language', 'in', 'range' => InstallForm::$_activeLangs, 'allowEmpty' => false),
        );
    }

    public function attributeLabels()
    {
        if (isFree()) {
            $lang = tFile::getT('module_install', 'Site language');
        } else {
            $lang = tFile::getT('module_install', 'Preferred site language');
        }

        return array(
            'template' => tFile::getT('module_install', 'Template'),
            'agreeLicense' => tFile::getT('module_install', 'I agree with') . ' ' . CHtml::link(tFile::getT('module_install', 'License agreement'), (Yii::app()->language == 'ru') ? 'https://open-real-estate.info/ru/license' : 'https://open-real-estate.info/en/license', array('target' => '_blank')),
            'supportLicense' => tFile::getT('module_install', 'I agree with') . ' ' . CHtml::link(tFile::getT('module_install', 'Support agreement'), (Yii::app()->language == 'ru') ? 'https://open-real-estate.info/ru/technical-support-rules' : 'https://open-real-estate.info/en/technical-support-rules', array('target' => '_blank')),
            'dbHost' => tFile::getT('module_install', 'Database server'),
            'dbPort' => tFile::getT('module_install', 'Database port'),
            'dbUser' => tFile::getT('module_install', 'Database user name'),
            'dbPass' => tFile::getT('module_install', 'Database user password'),
            'dbName' => tFile::getT('module_install', 'Database name'),
            'dbPrefix' => tFile::getT('module_install', 'Prefix for tables'),
            'adminName' => tFile::getT('module_install', 'Administrator name'),
            'adminPass' => tFile::getT('module_install', 'Administrator password'),
            'adminEmail' => tFile::getT('module_install', 'Administrator email'),
            'language' => $lang,
            'siteName' => tFile::getT('module_install', 'siteName'),
            'siteKeywords' => tFile::getT('module_install', 'siteKeywords'),
            'siteDescription' => tFile::getT('module_install', 'siteDescription'),
        );
    }

    public static function randomDBPrefix($length = 10)
    {
        $chars = range('a', 'z');
        shuffle($chars);
        return implode('', array_slice($chars, 0, $length));
    }
}
