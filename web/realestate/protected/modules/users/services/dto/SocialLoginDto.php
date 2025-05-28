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

namespace application\modules\users\services\dto;

class SocialLoginDto
{
    public $network; // соц. сеть, через которую авторизовался пользователь
    public $identity; // уникальная строка определяющая конкретного пользователя соц. сети
    public $first_name; // имя пользователя
    public $last_name; // фамилия пользователя
    public $email;
}