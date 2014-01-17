<?php
$this->breadcrumbs=array(
	News::modelTitle()=>array('list'),
	'Редактирование',
);

$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModal')); ?>

	<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4>Настройка таблицы</h4>
	</div>

	<div class="modal-body">
		<form id="userTableSetupForm">
			<?php echo CHtml::checkBoxList(
			    'Table',
			    Yii::app()->user->getState('newsTable',$this->getDefaultTableAttributes()),
			    	$this->module->getTableAttributesNames(new News,$this->tableAttributes),
			    array(
			    	'container'=>'div',
			    	'separator'=>'',
			    	'checkAll'=>'Select all tasks',
			    	'checkAllLast'=>true,
					'template'=>'<div class="row-fluid"><div class="span1">{input}</div><div class="span11">{label}</div></div>',
			    )


			); ?>
		</form>

	</div>

	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'type' => 'primary',
			'label' => 'Save changes',
			'url' => '#',
			'htmlOptions' => array(
				'data-dismiss' => 'modal',
				'onclick'=>'setupTable()'
			),
		)); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'label' => 'Close',
			'url' => '#',
			'htmlOptions' => array('data-dismiss' => 'modal'),
		)); ?>
	</div>

<?php $this->endWidget(); ?>

<h1><?=News::modelTitle()?></h1>
<?php $this->widget('bootstrap.widgets.TbButton', array(
	'label' => 'Настройка таблицы',
	'type' => 'primary',
	'htmlOptions' => array(
	'data-toggle' => 'modal',
	'data-target' => '#myModal',
	),
)); ?>
<a class="btn btn-success" href="<?=$this->createUrl('create')?>">
	+ Создать
</a>
<?php $this->renderPartial('_list',compact('model'))?>

<script>
function setupTable(){
	$.ajax({
		url: '<?=$this->createUrl("tableSetup")?>',
		type: 'POST',
		data: $("#userTableSetupForm").serializeArray(),
		success: function(e){
			window.location.reload()
		}
	})
}
</script>