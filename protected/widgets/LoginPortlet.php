<?php
Yii::import('zii.widgets.CPortlet');

class LoginPortlet extends CPortlet
{
	/**
	 * @var string the portlet title. Defaults to 'Login'.
	 */
	public $title='Login';
	/**
	 * @var boolean whether to enable remember login feature. Defaults to false.
	 * If you set this to true, please make sure you also set CWebUser.allowAutoLogin
	 * to be true in the application configuration.
	 */
	public $enableRememberMe=false;
	/**
	 * @var string user identity class. Defaults to 'application.components.UserIdentity'.
	 */
	public $identityClass='application.components.UserIdentity';

	/**
	 * Renders the body content in the portlet.
	 * This is required by XPortlet.
	 */
	protected function renderContent()
	{
		$user=new LoginForm($this->identityClass);
		if(isset($_POST['LoginForm']))
		{
			$user->attributes=$_POST['LoginForm'];
			if($user->validate() && $this->login($user))
				$this->controller->refresh();
		}
		$this->render('loginPortlet',array('user'=>$user));
	}

	/**
	 * Logs in a user.
	 * @param XLoginForm the login form
	 * @return boolean whether the login is successful
	 */
	protected function login($user)
	{
		$class=Yii::import($this->identityClass);
		$identity=new $class($user->username,$user->password);
		if($identity->authenticate())
		{
			if($this->enableRememberMe && $user->rememberMe)
				$duration=3600*24*30;   // 30 days
			else
				$duration=0;
			Yii::app()->user->login($identity,$duration);
			return true;
		}
		else
			$user->addError('password','Incorrect password.');
	}
}
