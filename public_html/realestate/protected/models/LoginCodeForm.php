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

class LoginCodeForm extends CFormModel
{
    public $code;

    const CODE_ACTIVE_MIN = 5;

    public function rules()
    {
        $rules = array(
            array('code', 'filter', 'filter' => 'trim'),
            array('code', 'required'),
            array('code', 'validateCode'),
        );

        return $rules;
    }

    public function validateCode($attribute, $params)
    {
        $userModel = User::model()->findByPk(Yii::app()->user->getState('userLoginId'));
        if (!$userModel) {
            $this->addError($attribute, tc('Incorrect code'));
        } else {
            $code = $this->{$attribute};

            if ($userModel->login_code != $code) {
                $this->addError($attribute, tc('Incorrect code'));
            } else {
                $loginCodeExpired = strtotime($userModel->login_code_expired);
                if ($loginCodeExpired < time()) {
                    $this->addError($attribute, tc('The code has expired. Enter your username and password again on the authorization page.'));
                }
            }
        }
    }

    public function attributeLabels()
    {
        return array(
            'code' => Yii::t('common', 'Code'),
        );
    }

    public function sendCode()
    {
        try {
            $userModel = User::model()->findByPk(Yii::app()->user->getState('userLoginId'));

            if ($userModel) {
                $dt = new DateTime();
                $dt->modify('+'.LoginCodeForm::CODE_ACTIVE_MIN.' minute');
                $this->code = LoginCodeForm::generateLoginCode();

                $userModel->login_code = $this->code;
                $userModel->login_code_expired = $dt->format('Y-m-d H:i:s');

                $userModel->update(array('login_code', 'login_code_expired'));

                $notifier = new Notifier;
                $notifier->raiseEvent('onNewLoginFormCode', $this, ['forceEmail' => $userModel->email]);

                $this->unsetAttributes(['code']);
                return true;
            }
        } catch (Exception $ex) {
            return false;
        }

        return false;
    }

    public function clearCode()
    {
        $userModel = User::model()->findByPk(Yii::app()->user->getState('userLoginId'));
        Yii::app()->user->getState('userLoginId', null);

        if ($userModel) {
            $userModel->login_code = new CDbExpression('NULL');
            $userModel->login_code_expired = new CDbExpression('NULL');

            $userModel->update(array('login_code', 'login_code_expired'));
        }
    }

    public static function generateLoginCode()
    {
        return mt_rand(1000, 9999);
    }
}
