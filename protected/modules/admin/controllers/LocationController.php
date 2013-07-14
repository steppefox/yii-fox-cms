<?php
/*
 * Administration are location that have users
 * The administration with id 1 is the Headquater
 */
class LocationController extends AdminController
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
                'actions' => array('index', 'update', 'enable'),
                'expression' => '!$user->isGuest && $user->role >= User::ROLE_MANAGER',
            ),
            array('allow',
                'actions' => array('create', 'delete'),
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

        
        $model = new Administration;
        $this->performAjaxValidation($model);

        if (isset($_POST['Administration']))
        {
            $model->attributes = $_POST['Administration'];
            if ($model->save())
            {
                Yii::app()->user->setFlash('locationSaved', 'Administration: "' . $model->name . '" was successfully added!');
                $this->redirect(array('location/index'));
            }
        }
        
        if (Yii::app()->request->isAjaxRequest)
        {
            $this->renderPartial('form', array('model' => $model), false, true);
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        //Yii::app()->clientScript->scriptMap['jquery.js'] = false;
        //Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;
        //Yii::app()->clientScript->scriptMap['jquery-ui.min.js'] = false;

        $model = $this->loadModel($id);
        //$this->performAjaxValidation($model);

        if (isset($_POST['Administration']))
        {
            $model->attributes = $_POST['Administration'];
            if ($model->save())
            {
                Yii::app()->user->setFlash('locationSaved', 'Administration: "' . $model->name . '" was successfully modified!');
                $this->redirect(array('location/index'));
            }
        }

        $this->render('form', array('model' => $model));
    }

    /**
     * Toggle the active state of the location
     * @param integer $id
     */
    public function actionEnable($id)
    {
        $model = $this->loadModel($id);
        $model->active = !$model->active; //switch active/inactive
        $enabled = ($model->active) ? "enabled" : "disabled";
        if ($model->save())
        {
            Yii::app()->user->setFlash('locationSaved', $model->name . ' was successfully ' . $enabled);
            $this->redirect(array('index'));
        }
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Administration('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Administration']))
            $model->attributes = $_GET['Administration'];
        if (Yii::app()->administration->id != 1)
            $model->id = Yii::app()->administration->id;
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'location-form')
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
        $model = Administration::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}
