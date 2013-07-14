<?php

class UserController extends AdminController
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
            array('allow',
                'actions' => array('create', 'update', 'delete'),
                'expression' => '!$user->isGuest && $user->role >= User::ROLE_ADMIN',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;

        $model = new User('register');
        $this->performAjaxValidation($model);

        if (isset($_POST['User']))
        {
            $model->attributes = $_POST['User'];
            $model->administration_id = $_GET['administration_id'];
            if ($model->save())
            {
                Yii::app()->user->setFlash('locationSaved', 'User: "' . $model->login . '" was successfully added!');
                $this->redirect(array('location/index'));
            }
        }

        if (Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial('form', array('model' => $model), false, true);
        }
    }
    
    public function actionDelete($id)
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
        Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;

        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);

        if (isset($_POST['User']))
        {
            $model->attributes = $_POST['User'];
            if ($model->save())
            {
                Yii::app()->user->setFlash('locationSaved', 'User: "' . $model->login . '" was successfully modified!');
                $this->redirect(array('location/index'));
            }
        }

        if (Yii::app()->request->isAjaxRequest)
        {
            $model->password = '';
            $this->renderPartial('form', array('model' => $model), false, true);
        }
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = User::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}