<?php

/**
 * The Customer controller.
 * @author Michael
 *
 */
class CustomerController extends AdminController
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'update', 'delete'),
                'expression' => '!$user->isGuest && $user->role >= User::ROLE_MODERATOR',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex()
    {
        $model = new Customer('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Customer']))
            $model->attributes = $_GET['Customer'];

        $this->render('index', array('model' => $model));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate($id)
    {
            $model=$this->loadModel($id);

            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if(isset($_POST['Customer']))
            {
                    $model->attributes=$_POST['Customer'];
                    if($model->save())
                    {
                            Yii::app()->user->setFlash('customerSubmit','De klant is succesvol aangepast');
                            $this->redirect(array('index'));
                    }
            }

            $this->render('form',array(
                    'model'=>$model,
            ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete($id)
    {
            if(isset($_GET['id']))
            {

                $this->loadModel($id)->delete();

                // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                if(!isset($_GET['ajax']))
                {
                        Yii::app()->user->setFlash('customerSubmit','De klant is succesvol verwijderd');
                        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
                }

            }
            else
                throw new CHttpException(400,'Foutive aanvraag. Please do not repeat this request again.');
    }

    public function loadModel($id)
    {
        $model = Customer::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

}