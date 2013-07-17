<?php

class DefaultController extends AdminController
{
	public function actionIndex()
	{
		$this->render('index');
	}

    public function actionStatVisitors()
    {
        $stats = new Statistics();
        echo CJavaScript::jsonEncode($stats->getVisitorsAndPageviews());
    }

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
}