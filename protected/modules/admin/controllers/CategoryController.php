<?php

class CategoryController extends AdminController
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
        	array('allow',  // allow admin
                'actions'=>array('renderTree'),
                'expression'=>'!$user->isGuest && $user->role >= User::ROLE_MODERATOR',
            ),
            array('allow',  // allow admin
                'actions'=>array('create','update','delete'),
                'expression'=>'!$user->isGuest && $user->role >= User::ROLE_ADMIN',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    
	/**
	 * Renders JSON data for jsTree
	 */
	public function actionRenderTree()
	{
		echo Category::model()->getJsTree();
	}

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;

        $model = new Category;
        $this->performAjaxValidation($model);

        if (isset($_GET['parentid']))
            $model->parent_id = $_GET['parentid'];


        if(isset($_POST['Category']))
        {
            $model->attributes=$_POST['Category'];
            if($model->save())
            {
            	Yii::app()->user->setFlash('categorySaved','Category is successfully saved');
                $this->redirect(array('content/index'));
            }
        }
        if (Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial('form',array('model'=>$model,));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;

        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);

        if(isset($_POST['Category']))
        {
            $model->attributes=$_POST['Category'];
            if($model->save())
            {
            	Yii::app()->user->setFlash('categorySaved','Category is successfully saved');
                $this->redirect(array('content/index'));
            }
        }
        if (Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial('form',array('model'=>$model,));
        }
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=Category::model()->findByPk((int)$id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='content-category-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}