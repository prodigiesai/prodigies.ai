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

class ConsoleUser extends CApplicationComponent implements IWebUser
{

    public $primaryKey;
    private $admin;

    public function loginRequired()
    {
        return true;
    }

    public function init()
    {
        parent::init();

        if (!isset($this->primaryKey))
            throw new Exception('You must set the "primary key" of the user
                to execute the console application.');

        $user = User::model()->findByPk($this->primaryKey);

        if ($user) {
            if ($user->role != User::ROLE_ADMIN) {
                throw new Exception('User does not have an Admin role.');
            }

            $this->admin = $user;
        } else
            throw new Exception('Could not find Admin User to execute console application.');
    }

    /**
     * Returns a value that uniquely represents the identity.
     * @return mixed a value that uniquely represents the identity (e.g. primary key value).
     */
    public function getId()
    {
        return $this->admin->id;
    }

    /**
     * Returns the display name for the identity (e.g. username).
     * @return string the display name for the identity.
     */
    public function getName()
    {
        return $this->admin->username;
    }

    /**
     * Returns a value indicating whether the user is a guest (not authenticated).
     * @return boolean whether the user is a guest (not authenticated)
     */
    public function getIsGuest()
    {
        return false;
    }

    /**
     * Performs access check for this user.
     * @param string $operation the name of the operation that need access check.
     * @param array $params name-value pairs that would be passed to business rules associated
     * with the tasks and roles assigned to the user.
     * @return boolean whether the operations can be performed by this user.
     */
    public function checkAccess($operation, $params = array())
    {
        return true;
    }

    public function getIsAdmin()
    {
        return true;
    }

    public function setFlash($key, $value, $defaultValue = null)
    {

    }

    public function hasState()
    {

    }

    public function setState()
    {

    }
}
