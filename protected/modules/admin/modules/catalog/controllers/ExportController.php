<?php

class ExportController extends AdminController
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
                'actions'=>array('create','update','delete', 'index', 'toBeslist', 'toVergelijk', 'toKieskeurig','ProductDropdown'),
                'users'=>array('@'),
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
		
		public function actionIndex()
		{
			$model = new ProductExport('search');
      $model->unsetAttributes();  // clear any default values
      if (isset($_GET['ProductExport']))
        $model->attributes = $_GET['ProductExport'];
				
			//$model = ExportProduct::model()->with('product')->together()->findAll();
			
			$this->render('index', array('model'=>$model));
		}
		
		/**
		 * Will add a product to the export database 
		 */
		public function actionCreate()
		{
			//post a product_id pul 3 booleans
			$model = new ProductExport();
			if(isset($_POST['ProductExport']))
			{
				$loadmodel = ProductExport::model()->findByPk($_POST['ProductExport']['product_id']);
				if($loadmodel != null)
					$model = $loadmodel;
				
				$model->attributes = $_POST['ProductExport'];
				if($model->save())
				{
					Yii::app()->user->setFlash('success', 'Product is toegevoegd aan de export lijst');
					$this->redirect(array('index'));
				}
				
			}
			
			$this->render('form', array('model'=>$model));
		}
		
		public function actionProductDropdown()
		{
			$data=Product::model()->findAll('category_id=:category_id', 
                  array(':category_id'=>(int) $_POST['category_id']));
 
			$data=CHtml::listData($data,'id','name');
			foreach($data as $value=>$name)
			{
					echo CHtml::tag('option',
										array('value'=>$value),CHtml::encode($name),true);
			}
		}
		
		/**
		 * Will update the export options of a product 
		 */
		public function actionUpdate($id)
		{
			//post a product_id pul 3 booleans
			$model = ProductExport::model()->findByPk($id);
			if($model==null)
				throw new CHttpException(404, 'Er zijn geen export opties gevonden voor dit product');
			
			if(isset($_POST['ProductExport']))
			{
				$model->attributes = $_POST['ProductExport'];
				if($model->save())
				{
					Yii::app()->user->setFlash('success', 'Product is toegevoegd aan de export lijst');
					$this->redirect(array('index'));
				}
				
			}
			
			$this->render('form', array('model'=>$model));
		}
		
		/**
		 * Will remove a product from the export database 
		 */
		public function actionDelete($id)
		{
			if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            ProductExport::model()->deleteByPk($id);

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
    
    public function actionToBeslist()
    {
      //build csv
			$criteria = new CDbCriteria();
			$criteria->compare('beslist', true);
			$products = ProductExport::model()->with('product')->together()->findAll($criteria);
			
			$fp = fopen(Yii::getPathOfAlias('webroot').'/files/shared/export/beslist.csv', 'w');

			fputcsv($fp, ProductExport::beslistHeaders());
			foreach ($products as $product) {
					fputcsv($fp, $product->toBeslistLine());
			}

			fclose($fp);
			echo "export voor Beslist is gemaakt";
    }
    
		public function actionToKieskeurig()
		{
			//build csv
			$criteria = new CDbCriteria();
			$criteria->compare('kieskeurig', true);
			$products = ProductExport::model()->with('product')->together()->findAll($criteria);

			$fp = fopen(Yii::getPathOfAlias('webroot').'/files/shared/export/kieskeurig.csv', 'w');

			fputcsv($fp, ProductExport::kieskeurigHeaders());
			foreach ($products as $product) {
					fputcsv($fp, $product->toKieskeurigLine());
			}

			fclose($fp);
			echo "export voor Kieskeurig is gemaakt";
		}
		
		public function actionToVergelijk()
		{
			//build csv
			$criteria = new CDbCriteria();
			$criteria->compare('vergelijk', true);
			$products = ProductExport::model()->with('product')->together()->findAll($criteria);
			
			$fp = fopen(Yii::getPathOfAlias('webroot').'/files/shared/export/vergelijk.csv', 'w');

			fputcsv($fp, ProductExport::vergelijkHeaders());
			foreach ($products as $product) {
					fputcsv($fp, $product->toVergelijkLine());
			}

			fclose($fp);
			echo "export voor Vergelijk.nl is gemaakt";
		}
}
?>
