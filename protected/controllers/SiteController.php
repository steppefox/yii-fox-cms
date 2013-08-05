<?php

class SiteController extends Controller
{

    /**
     * Render the homepage
     */
    public function actionIndex()
    {

        // $this->pageTitle = (!empty($model->meta_title)) ? $model->meta_title : $model->title;
        // $this->metaKeywords = $model->meta_keywords;
        // $this->metaDescription = $model->meta_description;

        $this->render('index', array('model'=>$model));
    }

    public function actionLogin(){
        if(Yii::app()->user->isGuest){
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
        }else{
            $this->redirect(Yii::app()->getBaseUrl(true));
        }
    }

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error)
        {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionLogout(){
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->getBaseUrl(true));
    }

}