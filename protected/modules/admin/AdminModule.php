<?php

class AdminModule extends CWebModule
{

	public function init()
	{
		if(Yii::app()->user->isGuest){
			Yii::app()->user->loginRequired();
		}

        $this->setImport(array(
            'application.models.*',
            'admin.models.*',
            'admin.components.*',
            'rights.*',
            'rights.components.*',
            'admin.extensions.imperavi.ImperaviRedactorWidget',
        ));

        Yii::app()->setComponents(array(
            'bootstrap' => array(
                'class' => 'admin.extensions.bootstrap.components.Bootstrap',
            )
        ));
        Yii::app()->bootstrap->init();
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
