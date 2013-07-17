<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AdminController extends Controller
{

    public $layout = 'main';

    public function init()
    {
        $this->pageTitle = Yii::app()->name . " - " . Yii::t('backend', 'Admin');
        Yii::app()->user->loginUrl = array('/site/login');
        Yii::app()->homeUrl = array('default/');
    }

    public function beforeAction($action){
        $status = false;
        if(parent::beforeAction($action)){
            if(Yii::app()->user->checkAccess('admin.*')){
                $status = true;
            }
        }

        if($status===TRUE){
            return $status;
        }else{
            throw new CHttpException(403,'Доступ запрещен');
        }

    }

}