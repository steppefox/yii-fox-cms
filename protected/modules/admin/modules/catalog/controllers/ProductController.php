<?php

class ProductController extends AdminController
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update','delete', 'index', 'autoCompleteRelated', 'shippingTable', 'addShippingRule'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    
    public function actionAddShippingRule()
    {
        //Stop JQuery
        Yii::app()->clientScript->scriptMap=array(
            'jquery.js'=>false,
            'jquery.min.js'=>false,
            'jquery-ui.min.js'=>false,
            'jquery.ba-bbq.js'=>false,
        );
        
        if(Yii::app()->request->isAjaxRequest)
        {
            $model = new ShippingCost;
            $model->setId( uniqid('new_') );
            $this->renderPartial('shippingRule', array('model'=>$model, 'form'=>new CActiveForm));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }
    
    public function actionShippingTable()
    {
        //Stop JQuery
        Yii::app()->clientScript->scriptMap=array(
            'jquery.js'=>false,
            'jquery.min.js'=>false,
            'jquery-ui.min.js'=>false,
            'jquery.ba-bbq.js'=>false,
        );
        
        $models = ShippingCost::model()->findAll(array('index'=>'weight', 'order'=>'weight'));
        if(isset($_POST['ShippingCost']))
        {
            $valid=true;
            foreach($_POST['ShippingCost'] as $i=>$item)
            {
                if(isset($models[$i])) //excisting models
                {
                    $models[$i]->attributes=$item;
                }
                else
                {
                    $model = new ShippingCost;
                    $model->attributes = $item;
                    $models[$i] = $model;
                }
                $valid=$models[$i]->validate() && $valid;
            }
            
            if($valid)  // all items are 
            {
                foreach($models as $i=>$item)
                {
                    if ($_POST['ShippingCost'][$i]['markedDeleted'])
                        $item->delete();
                    else
                        $item->save();
                }
                   
                Yii::app()->end();
            }
                
        }
        
        $this->renderPartial('shippingcostForm',array('models'=>$models), false, true);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new Product;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Product']))
        {
            $model->attributes=$_POST['Product'];
            if($model->withRelated->save(array('mediaLinks', 'relatedProducts', 'propertyLinks')))
            {
                Yii::app()->user->setFlash('success', 'The product was successfully created!');
                $this->redirect(array('index','category_id'=>$model->category_id));
            }
        }

        $this->render('form',array(
            'model'=>$model,
        ));
    }
    
    /**
     * This mehtod will be called by the autocomplete function in the product form
     */
    public function actionAutoCompleteRelated()
    {
        header('Content-type: application/json');

        $returnVal[] =array();

	if (isset($_GET['term'])) {
		$qtxt ="SELECT id, name FROM product WHERE name LIKE :name";
		$command =Yii::app()->db->createCommand($qtxt);
		$command->bindValue(":name", '%'.$_GET['term'].'%', PDO::PARAM_STR);
		$res =$command->query();
                foreach($res as $row)
                {
                    $returnVal[] = array('label'=>$row['name'],'id'=>$row['id']);
                }
	}

	echo CJSON::encode($returnVal);
	Yii::app()->end();
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        if(isset($_GET['pixp']))
            $model->stock_price = $_GET['pixp'];
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Product']))
        {
            $model->attributes=$_POST['Product'];
            if($model->withRelated->save(array('mediaLinks', 'relatedProducts', 'propertyLinks')))
            {
                Yii::app()->user->setFlash('success', 'The product was successfully saved!');
                $this->redirect(array('index','category_id'=>$model->category_id));
            }
        }

        $this->render('form',array('model'=>$model));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        //if(Yii::app()->request->isPostRequest)
        //{
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        //}
        //else
       //     throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model=new Product('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Product']))
            $model->attributes=$_GET['Product'];
        
        if (isset($_GET['category_id']))
            $model->category_id = $_GET['category_id'];
        //else
            //$model->category_id = 0;

        $categories = ProductCategory::model()->getTree();
        
        $this->render('index',array(
            'model'=>$model,
            'categories'=>$categories,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model=Product::model()->findByPk($id);
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='product-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}