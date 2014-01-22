<?php

class Controller extends CController
{

    public $layout = '//layouts/main';
    public $menu = array();
    /**
     * Meta data the every page gets set in the view
     */
    public $metaDescription = '';
    public $metaKeywords = '';
    /**
     * Application breadcrumbs
     */
    public $breadcrumbs=array();

    public $registerCss = array();
    public $registerJs = array(
        'header'=>array(),
        'footer'=>array()
    );
    public $baseUrl = '';

    /**
     * Fix for IE in AngularJS applications
     */
    public $angularApplication;

    public function init()
    {
        $this->baseUrl = Yii::app()->getBaseUrl(true);
        $this->registerCss[] = array(
            'path'=>$this->baseUrl.'/public/plugin/bootstrap/css/bootstrap.min.css',
            'media'=>'all'
        );
        $this->registerCss[] = array(
            'path'=>$this->baseUrl.'/public/css/main.css',
            'media'=>'all'
        );
        $this->registerCss[] = array(
            'path'=>$this->baseUrl.'/public/css/normalize.css',
            'media'=>'all'
        );

        $this->registerJs['header'][] = $this->baseUrl.'/public/js/jquery-1.10.2.min.js';
    }

}