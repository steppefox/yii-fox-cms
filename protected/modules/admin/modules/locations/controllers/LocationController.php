<?php

/**
 * The LocationController.
 * @author Michael
 *
 */
class LocationController extends AdminController
{

    /**
     * this crap is for the flash upload thinky.. it loses the session otherwise
     */
    function init(){
        parent::init();
      if(isset($_POST['SESSION_ID']))
                    {
        $session=Yii::app()->getSession();
        $session->close();
        $session->sessionID = $_POST['SESSION_ID'];
        $session->open();
      }
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
                'actions' => array('index', 'view', 'update', 'upload', 'deleteFile', 'create', 'delete', 'changePositions'),
                'expression' => '!$user->isGuest',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }
    
    public function actionUpload($id)
    {
        $model=new LocationMedia;
        $model->location_id = $id;
        if(isset($_POST['file']))
        {
            $model->file=CUploadedFile::getInstanceByName('file');
            if(!$model->save())
                throw new CHttpException(500);
            echo 1;
            Yii::app()->end();
        }
    }
    
    public function actionDeleteFile($id)
    {
        Media::model()->findByPk($id)->delete();
    }

    public function actionIndex()
    {
        $model = new Location('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Location']))
            $model->attributes = $_GET['Location'];

        $this->render('index', array('model' => $model));
    }

    /**
     * Add a new product to database
     */
    public function actionCreate()
    {
        $model = new Location;

        if (isset($_POST['Location']))
        {
            $model->attributes = $_POST['Location'];
            $model->setRelationRecords('locationMedia',is_array(@$_POST['LocationMedia']) ? $_POST['LocationMedia'] : array());

            if ($model->save())
            {
                Yii::app()->user->setFlash('locationSaved', 'The product was successfully saved!');
                $this->redirect(array('index'));
            }
        }

        $this->render('form', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Location']))
        {
            $model->attributes = $_POST['Location'];
            $model->setRelationRecords('locationMedia',is_array(@$_POST['LocationMedia']) ? $_POST['LocationMedia'] : array());

            if ($model->save())
            {
                Yii::app()->user->setFlash('locationSaved', 'Locatie details zijn met succes opgeslagen!');
                $this->redirect(array('index'));
            }
        } 

        $this->render('form', array(
            'model' => $model,
        ));

    }

    public function loadModel($id)
    {
        $model = Location::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}