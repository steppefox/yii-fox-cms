
<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the BootCrudCode object
 * echo $this->baseControllerClass."\n";
 */
?>
<?php echo "<?php\n"; ?>
class <?php echo $this->controllerClass; ?> extends AdminController
{
	public $targetModel = '<?=$this->modelClass?>';

	public $defaultAction = "list";

	public function actionForm(){
		if($id=Yii::app()->request->getQuery('id')){
			$model=$this->loadModel($id);
		}else{
			$model = new $this->targetModel;
			$model->created_at = time();
		}

		$this->performAjaxValidation($model);

		if(isset($_POST[$this->targetModel])){
			$model->attributes=$_POST[$this->targetModel];
			<?php foreach($this->tableSchema->columns as $column): ?>
			<?php if (strstr($column->name, 'image')!==false || strstr($column->name, 'file')!==false): ?>
			$model-><?=$column->name?> = FileHelper::loadFile($model,'<?=$column->name?>',$returnJson=true);
			<?php endif ?>
			<?php endforeach ?>

			if($model->save()){
			 	Yii::app()->user->setFlash('success', 'Сохранено');
				$this->redirect(array('list'));
			}else{
				 Yii::app()->user->setFlash('error', 'Ошибка при сохранении!');
			}
		}

		if($model->isNewRecord){
			$this->pageCaption = 'Добавление записи';
			$this->breadcrumbs=array(
				$model::modelTitle()=>array('index'),
				'Создание',
			);
		}else{
			$this->pageCaption = 'Редактирование записи';
			$this->breadcrumbs=array(
				$model::modelTitle()=>array('index'),
				'Редактирование',
			);
		}

		$this->render('form',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest){
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}else{
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
	}

	public function actionIndex()
	{
		$this->redirect("list");
	}

	public function actionList()
	{
		$model=new $this->targetModel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET[$this->targetModel]))
			$model->attributes=$_GET[$this->targetModel];
		if(Yii::app()->request->isAjaxRequest){
			$this->renderPartial('list',array(
				'model'=>$model,
				'onlyTable'=>true,
			),false,true);
		}else{
			$this->breadcrumbs = array(
				$model::modelTitle()
			);
			$this->render('list',array(
				'model'=>$model,
			));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=CActiveRecord::model($this->targetModel)->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']===$this->targetModel.'-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
