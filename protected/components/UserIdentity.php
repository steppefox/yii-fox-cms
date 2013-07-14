<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

    private $_id;

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $record = User::model()->findByAttributes(array('login' => $this->username));
        if($record != null)
            $record->scenario = 'login';
        if ($record === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$record->validatePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else if ($record->administration_id != Yii::app()->administration->id && $record->administration_id != 1)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else
        {
            $this->_id = $record->id;
            $this->setState('administration', $record->administration_id);
            $this->setState('role', $record->role);
            $this->setState('name', $record->nicename);
            $this->errorCode = self::ERROR_NONE;
        }
        return!$this->errorCode;
    }

    public function getRole()
    {
        return 0;
    }

    public function getId()
    {
        return $this->_id;
    }

}