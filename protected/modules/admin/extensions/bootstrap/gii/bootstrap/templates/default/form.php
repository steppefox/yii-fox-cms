<div>
	<h1>
		<?='<?='?>$this->pageCaption;<?='?>'?>
	</h1>
</div>

<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>\$this->targetModel.'-form',
	'enableAjaxValidation'=>true,
	'type'=>'horizontal',
)); ?>\n"; ?>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
?>
	<?php echo "<?=".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?>

<?php
}
?>
	<div class="form-actions">
		<button class="btn btn-success" type="submit">
			<?php echo "<?php echo \$model->isNewRecord ? 'Создать' : 'Сохранить'?>\n"?>
		</button>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
