<?php echo "<?php \n"; ?>
$visible = Yii::app()->user->getState("<?=strtolower($this->modelClass)?>Table",$this->getDefaultTableAttributes());

$this->widget('bootstrap.widgets.TbExtendedGridView', array(
	'type' => 'striped bordered',
	'id'=>'<?=strtolower($this->modelClass)?>-grid',
	'dataProvider' => $model->search(),
	'template' => "{summary}{items}{pager}",
	'filter'=>$model,
	'ajaxUrl'=>'/inversion/<?=strtolower($this->modelClass)?>/list',
	'columns' => array(
<?php
foreach ($this->tableSchema->columns as $column) :
if($column->name=='id')	:?>
		array('name' => 'id', 'header' => '#'),
<?elseif($column->dbType=='text'):?>
		array(
 			'name' => '<?=$column->name?>',
 			'class' => 'bootstrap.widgets.TbEditableColumn',
 			'editable' => array(
 				'type' => 'textarea',
 				'url' => '/inversion/<?=strtolower($this->modelClass)?>/editable'
 			),
 			'visible'=>in_array('<?=$column->name?>', $visible)?true:false,
 		),
<?elseif($column->type=='string' && !strstr($column->name, 'passw') && !strstr($column->name, '_json')):?>
		array(
 			'name' => '<?=$column->name?>',
 			'class' => 'bootstrap.widgets.TbEditableColumn',
 			'editable' => array(
 				'type' => 'text',
 				'url' => '/inversion/<?=strtolower($this->modelClass)?>/editable'
 			),
 			'visible'=>in_array('<?=$column->name?>', $visible)?true:false,
 		),
<?elseif($column->dbType=='int(10) unsigned' && mb_strpos($column->name,'_at',0,'UTF-8')!==FALSE):?>
		array(
 			'name' => '<?=$column->name?>',
 			'class' => 'bootstrap.widgets.TbEditableColumn',
 			'editable' => array(
 				'type' => 'date',
 				'format'=>"dd-mm-yyyy",
 				'url' => '/inversion/<?=strtolower($this->modelClass)?>/editable'
 			),
 			'visible'=>in_array('<?=$column->name?>', $visible)?true:false,
 		),
<?elseif(mb_strpos($column->name,'is_',0,'UTF-8')!==FALSE || $column->name=='status'):?>
		array(
 			'class'=>'bootstrap.widgets.TbToggleColumn',
 			'toggleAction'=>'<?=strtolower($this->modelClass)?>/toggle',
 			'name' => '<?=$column->name?>',
 			'filter'=>array(0=>"Выкл",1=>"Вкл"),
 			'visible'=>in_array('<?=$column->name?>', $visible)?true:false,
 		),
<?else:?>
		array(
 			'name' => '<?=$column->name?>',
 			'class' => 'bootstrap.widgets.TbEditableColumn',
 			'editable' => array(
 				'type' => 'text',
 				'url' => '/inversion/<?=strtolower($this->modelClass)?>/editable'
 			),
 			'visible'=>in_array('<?=$column->name?>', $visible)?true:false,
 		),
<?endif?>
<?endforeach?>
		array(
			'htmlOptions' => array('nowrap' => 'nowrap'),
			'class' => 'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}'
		),
	),
)); ?>

<?
// $this->widget('bootstrap.widgets.TbExtendedGridView', array(
// 	'type' => 'striped bordered',
// 	'dataProvider' => $model->search(),
// 	'template' => "{summary}{items}{pager}",
// 	'filter'=>$model,
// 	'ajaxUrl'=>'/inversion/city/admin',
// 	'columns' => array(
// 		array('name' => 'id', 'header' => '#'),
// 		array(
// 			'name' => 'title',
// 			'class' => 'bootstrap.widgets.TbEditableColumn',
// 			'editable' => array(
// 				'type' => 'text',
// 				'url' => '/inversion/city/editable'
// 			)
// 		),
// 		array(
// 			'class'=>'bootstrap.widgets.TbToggleColumn',
// 			'toggleAction'=>'city/toggle',
// 			'name' => 'status',
// 			'filter'=>array(0=>"Выкл",1=>"Вкл"),
// 		),
// 		array(
// 			'htmlOptions' => array('nowrap' => 'nowrap'),
// 			'class' => 'bootstrap.widgets.TbButtonColumn',
// 		)
// 	)
// ));?>