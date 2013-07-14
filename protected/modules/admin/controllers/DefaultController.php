<?php

class DefaultController extends AdminController
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('login', 'logout', 'error'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index', 'visitorLog', 'statVisitors', 'statBrowser', 'statCountry', 'statSource', 'statPlace'),
				'users'=>array('@'),
                                'expression' => '!$user->isGuest && $user->role >= User::ROLE_MODERATOR',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * this page request al the other by ajax requests
	 */
	public function actionIndex()
	{
            try 
            {
                $stats = new Statistics();
                $pagepath = $stats->getPagepath();
                $totals = $stats->getTotals();
            }
            catch(Exception $e)
            {
                $totals = array();
                $pagepath = array();
            }
            
            $this->render('index', array('pagepath'=>$pagepath, 'totals'=>$totals));
	}
        
        public function actionStatVisitors()
        {
            $stats = new Statistics();
            echo CJavaScript::jsonEncode($stats->getVisitorsAndPageviews());
        }
        
        public function actionStatBrowser()
        {
            $stats = new Statistics();
            echo CJavaScript::jsonEncode($stats->getBrowser());
        }
        
        public function actionStatPlace()
        {
            $stats = new Statistics();
            echo CJavaScript::jsonEncode($stats->getPlace());
        }
        
        public function actionStatCountry()
        {
            $stats = new Statistics();
            echo CJavaScript::jsonEncode($stats->getCountry());
        }
        
        public function actionStatSource()
        {
            $stats = new Statistics();
            echo CJavaScript::jsonEncode($stats->getSource());
        }
        

        public function actionVisitorLog()
        {
            $stats = new Statistics();

            print_r($stats->getLog());
            //$this->render('visitorLog', array('stats'=>$stats));
        }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
            $this->layout ='column1';
		
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout ='//layouts/column1';
		
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}