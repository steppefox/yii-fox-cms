<?php

class AdminModule extends CWebModule
{

	public function init()
	{

        $this->setImport(array(
            'application.models.*',
            'admin.models.*',
            'admin.components.*',
            'rights.*',
            'rights.components.*'
        ));
        //The backend should use a differend user session as frontend
        // Yii::app()->user->setStateKeyPrefix('admin_');

	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
