<?php 
$visible = Yii::app()->user->getState("newsTable",$this->getDefaultTableAttributes());

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'type' => 'striped bordered',
	'id'=>'news-grid',
	'dataProvider' => $model->search(),
	'template' => "{summary}{items}{pager}",
	'filter'=>$model,
	'ajaxUrl'=>'/inversion/news/list',
	'columns' => array(
		array('name' => 'id', 'header' => '#'),
		array(
 			'name' => 'parent_NewsCategory_id',
 			'class' => 'bootstrap.widgets.TbEditableColumn',
 			'editable' => array(
 				'type' => 'text',
 				'url' => '/inversion/news/editable'
 			),
 			'visible'=>in_array('parent_NewsCategory_id', $visible)?true:false,
 		),
		array(
 			'name' => 'title_ru',
 			'class' => 'bootstrap.widgets.TbEditableColumn',
 			'editable' => array(
 				'type' => 'text',
 				'url' => '/inversion/news/editable'
 			),
 			'visible'=>in_array('title_ru', $visible)?true:false,
 		),
		array(
 			'class'=>'bootstrap.widgets.TbToggleColumn',
 			'toggleAction'=>'news/toggle',
 			'name' => 'is_visible',
 			'filter'=>array(0=>"Выкл",1=>"Вкл"),
 			'visible'=>in_array('is_visible', $visible)?true:false,
 		),
		array(
			'htmlOptions' => array('nowrap' => 'nowrap'),
			'class' => 'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}'
		),
	),
)); ?>

