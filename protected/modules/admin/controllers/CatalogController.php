<?php

class CatalogController extends AdminController
{

    protected $targetModel = 'Catalog';

    public function actionCreate()
    {
        $model = new $this->targetModel;
        $this->performAjaxValidation($model);
        if (isset($_POST[$this->targetModel])){
            $model->attributes = Yii::app()->request->getPost($this->targetModel,array());
            if ($model->save()){
                Yii::app()->user->setFlash('contentSaved', 'The page was successfully created!');
                $this->redirect('list');
            }
        }

        $this->render('form', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);
        if (isset($_POST[$this->targetModel])){
            $model->attributes = Yii::app()->request->getPost($this->targetModel,array());
            if ($model->save()){
                Yii::app()->user->setFlash('contentSaved', 'The page was successfully saved!');
                $this->redirect(array('list'));
            }
        }

        $this->render('form', array(
            'model' => $model,
        ));
    }

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

    public function actionList()
    {
        $this->render('list');
    }

    public function loadModel($id)
    {
        $model = CActiveRecord::model($this->targetModel)->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $this->targetModel.'-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
