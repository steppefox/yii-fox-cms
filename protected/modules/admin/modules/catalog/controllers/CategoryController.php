<?php 

class CategoryController extends AdminController 
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update','index','delete', 'order', 'addPropertyGroup', 'addProperty'),
                'users'=>array('@'),
            ),

            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Set the position of every category in the tree
     * TODO: execute less queries to make faster
     * TODO: make transaction scope to validate all queries executed
     */
    public function actionOrder()
    {
        if(Yii::app()->request->isPostRequest && Yii::app()->request->isAjaxRequest)
        {
            if(!isset($_POST['list']))
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');

            $position = 1;
            foreach($_POST['list'] as $id => $parent_id)
            {
                if($parent_id == 'root')
                    $parent_id = null;
                ProductCategory::model()->updateByPk($id, array('parent_id'=>$parent_id, 'position'=>$position));
                $position++;
            }
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
    
    public function actionAddPropertyGroup()
    {
        if(Yii::app()->request->isAjaxRequest)
        {
            //Stop JQuery
            Yii::app()->clientScript->scriptMap=array(
                'jquery.js'=>false,
                'jquery.min.js'=>false,
                'jquery-ui.min.js'=>false,
                'jquery.ba-bbq.js'=>false,
            );
        
            $model = new PropertyGroup;
            $model->id = uniqid('new_');
            $this->renderPartial('propertyGroup', array('model'=>$model, 'form'=>new CActiveForm));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
    
    public function actionAddProperty($group_id)
    {
        if(Yii::app()->request->isAjaxRequest)
        {
            //Stop JQuery
            Yii::app()->clientScript->scriptMap=array(
                'jquery.js'=>false,
                'jquery.min.js'=>false,
                'jquery-ui.min.js'=>false,
                'jquery.ba-bbq.js'=>false,
            );
        
            $model = new Property();
            $model->id = uniqid('new_');
            $model->property_group_id = $group_id;
            $this->renderPartial('property', array('property'=>$model));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
    
    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new ProductCategory;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['ProductCategory']))
        {
            $model->attributes=$_POST['ProductCategory'];
            if($model->withRelated->save(array('propertyGroups'=>array('properties'))))
            {
                Yii::app()->user->setFlash('success', 'The category was successfully added!');
                $this->redirect(array('product/index','id'=>$model->id));
            }
        }

        $this->render('form',array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = ProductCategory::model()->with('propertyGroups')->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'No Category was selected');

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['ProductCategory']))
        {
            $model->attributes=$_POST['ProductCategory'];
            if($model->withRelated->save(array('propertyGroups'=>array('properties'))))
            {
                Yii::app()->user->setFlash('success', 'The category was successfully saved!');
                $this->redirect(array('product/index','id'=>$model->id));
            }
        }

        $this->render('form',array(
            'model'=>$model,
        ));
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
            $model = $this->loadModel($id);
            if($model->itemCount == 0)
                    $model->delete();
            else
                throw new CHttpException(500, 'Category must be empty before deletion');

            echo 1;
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            //if(!isset($_GET['ajax']))
                //$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
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
        $model = ProductCategory::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'Category was not found');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'product-category-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}