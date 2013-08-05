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
        return parent::beforeAction($action);
    }

    public function allowedActions(){
        return '';
    }

    public function filterRights( $filterChain ) {
        $filter = new RightsFilter;
        $filter->allowedActions = $this->allowedActions();
        $filter->filter( $filterChain );
    }

    public function filters() {
        return array(
            'rights',
        );
    }

    public function accessDenied(){
        throw new CHttpException(403,'Доступ запрещен');

    }

}