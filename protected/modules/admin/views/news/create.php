<?php
$this->breadcrumbs=array(
	News::modelTitle()=>array('index'),
	'Создание',
);

?>

<h1>
	Создание News</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>