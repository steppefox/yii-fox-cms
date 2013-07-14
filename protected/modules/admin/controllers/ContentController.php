<?php

class ContentController extends AdminController
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
    
    public function actions()
    {
        return array(
            'deleteMedia'=>array(
                'class'=>'application.modules.admin.controllers.media.DeleteAction',
                'modelName'=>'content',
            ),
            'addMedia'=>array(
                'class'=>'application.modules.admin.controllers.media.AddAction',
                'modelName'=>'content',
                'typeId'=>'1',
            )
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

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('create', 'update', 'index', 'delete', 'view', 'deleteFile', 'upload'),
                'expression' => '!$user->isGuest && $user->role >= User::ROLE_MODERATOR',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }
    
    /**
     * Add a new product to database
     * $_REQUEST['file']
     */ 
    public function actionUpload($id)
    {
        $model=new ContentMedia;
        $model->content_id = $id;
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
        //Needs to load record first or afterDelete function wont be called.
        Media::model()->findByPk($id)->delete();
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Content;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Content']))
        {
            $model->attributes = $_POST['Content'];
            //$model->categories = (!isset($_POST['Categories'])) ? array() : $_POST['Categories'];
            //$model->setRelationRecords('categories',is_array(@$_POST['Categories']) ? $_POST['Categories'] : array());
            //$model->setRelationRecords('contentMedia',is_array(@$_POST['ContentMedia']) ? $_POST['ContentMedia'] : array());
            //$model->setHasManyRelation('mediaItems', (is_array(@$_POST['Media'])) ? $_POST['Media'] : array()); //set attributes HAS_MANY

            if ($model->withRelated->save(array('mediaLinks', 'categories')))
            {
                Yii::app()->user->setFlash('contentSaved', 'The page was successfully created!');
                $this->redirect(array('index', 'id' => $model->id));
            }
        }

        $this->render('form', array(
            'model' => $model,
        ));
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

        if (isset($_POST['Content']))
        {
            $model->attributes = $_POST['Content'];
            //$model->categories = is_array(@$_POST['Categories']) ? $_POST['Categories'] : array(); //set attributes 
            //$model->setRelationRecords('categories',is_array(@$_POST['Categories']) ? $_POST['Categories'] : array());
            //$model->setRelationRecords('contentMedia',is_array(@$_POST['ContentMedia']) ? $_POST['ContentMedia'] : array());
            //$model->setHasManyRelation('contentMedia', (is_array(@$_POST['ContentMedia'])) ? $_POST['ContentMedia'] : array()); //set attributes HAS_MANY

            if ($model->withRelated->save(array('mediaLinks', 'categories')))
            {
                Yii::app()->user->setFlash('contentSaved', 'The page was successfully saved!');
                $this->redirect(array('index', 'id' => $model->id));
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
        $model = $this->loadModel($id);
        if (!$model->static)
        {
            // we only allow deletion via POST request
            $model->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Content('search');
        $model->unsetAttributes();  // clear any default values

        if (isset($_GET['Content']))
            $model->attributes = $_GET['Content'];
        if (isset($_GET['category_id']))
            $model->searchCategory = $_GET['category_id'];
        else
           $model->searchCategory = 0;

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Content::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'content-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
