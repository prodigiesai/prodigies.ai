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

class CallForm extends CFormModel {
	public $name;
	public $phone;
	public $time;
    public $verifyCode;

	public function rules() {
		return array(
			array('name, phone', 'required'),
            array('verifyCode', 'required'),
            array('verifyCode', 'CustomCaptchaValidatorFactory')
		);
	}

	public function attributeLabels() {
		return array(
		    'name' => tt('Name', 'contactform'),
			'phone' => tt('Phone', 'contactform'),
            'verifyCode' => tc('Verify Code'),
		);
	}
}
