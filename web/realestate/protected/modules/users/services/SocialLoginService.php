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

namespace application\modules\users\services;

use application\modules\users\services\dto\SocialLoginDto;
use User;
use Yii;

class SocialLoginService
{
    /**
     * @return \application\modules\users\services\dto\SocialLoginDto|false|null
     */
    public function authenticate($token)
    {
        if (function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
            $result = file_get_contents('http://ulogin.ru/token.php?token=' . $token .
                '&host=' . $_SERVER['HTTP_HOST']);
            //если недоступна file_get_contents, пробуем использовать curl
        } elseif (in_array('curl', get_loaded_extensions())) {
            $request = curl_init('http://ulogin.ru/token.php?token=' . $token .
                '&host=' . $_SERVER['HTTP_HOST']);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($request);
        }

        $data = $result ? json_decode($result, true) : array();

        //проверяем, чтобы в ответе были данные, и не было ошибки
        if ($data and !isset($data['error'])) {
            $dto = new SocialLoginDto();
            $dto->identity = isset($data['identity']) ? $data['identity'] : '';
            $dto->network = isset($data['network']) ? $data['network'] : '';
            $dto->first_name = isset($data['first_name']) ? $data['first_name'] : '';
            $dto->last_name = isset($data['last_name']) ? $data['last_name'] : '';
            $dto->email = isset($data['email']) ? $data['email'] : '';

            return $dto;
        }

        return false;
    }

    public function createUserByDto(SocialLoginDto $dto, &$redirectUrl = '')
    {
        $existId = User::getIdByUid($dto->identity, $dto->network);

        if ($existId) {
            if($this->loginByUserId($existId, $redirectUrl)){
                return true;
            }
        } else {
            $email = $dto->email ? $dto->email : User::getRandomEmail();
            $user = User::createUser(['email' => $email, 'username' => $dto->first_name], true);

            if (!$user || !isset($user['id'])) {
                $redirectUrl = Yii::app()->createAbsoluteUrl('/site/login') . '?soc_error_save=1';
                return false;
            }

            $setSocialUid = User::setSocialUid($user['id'], $dto->identity, $dto->network);
            if ($setSocialUid && $this->loginByUserId($user['id'], $redirectUrl)) {
                return true;
            }

            User::model()->findByPk($user['id'])->delete();

            logs([
                'socialAuth' => $setSocialUid,
                'user_id' => $user['id'],
                'dto' => $dto,
            ]);

            $redirectUrl = $redirectUrl ? $redirectUrl : Yii::app()->createAbsoluteUrl('/site/login') . '?soc_error_save=2';
            return false;
        }

        $redirectUrl = Yii::app()->createAbsoluteUrl('/site/login') . '?soc_error_save=4';
        return false;
    }

    private function loginByUserId($userId, &$redirectUrl = '')
    {
        $loginForm = new \LoginForm();

        $resultLogin = $loginForm->loginById($userId);

        User::updateUserSession();
        User::updateLatestInfo(Yii::app()->user->id, Yii::app()->controller->currentUserIp);

        if ($resultLogin) {
            if ($resultLogin === 'deactivate') {
                $redirectUrl = Yii::app()->createAbsoluteUrl('/site/login') . '?deactivate=1';
                return false;
            }

            $redirectUrl = Yii::app()->createAbsoluteUrl('/usercpanel/main/index') . '?soc_success=1';
            return true;
        }

        $redirectUrl = Yii::app()->createAbsoluteUrl('/site/login') . '?soc_error_save=3';
        return false;
    }
}