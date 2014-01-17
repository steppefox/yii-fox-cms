<?php
$this->breadcrumbs=array(
	News::modelTitle()=>array('index'),
	'Редактирование '.$model->id,
);
?>

<h1>Редактирование News <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>