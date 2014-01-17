<?php
class UserIdentity extends CUserIdentity {
    private $_id;
    const ERROR_EMAIL_INVALID=3;
    const ERROR_STATUS_NOTACTIV=4;
    const ERROR_STATUS_BAN=5;

    public function authenticate() {
        if (strpos($this->username,"@")) {
            $user=User::model()->findByAttributes(array('email'=>$this->username));
        }

        if ($user===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(!CPasswordHelper::verifyPassword($this->password,$user->password) && $this->password!==$user->password)
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else if($user->status==0)
            $this->errorCode=self::ERROR_STATUS_NOTACTIV;
        else if($user->status==-1)
            $this->errorCode=self::ERROR_STATUS_BAN;
        else {
            $this->_id=$user->id;
            $this->username=$user->login;
            $this->errorCode=UserIdentity::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->_id;
    }
}
