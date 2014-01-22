<div>
	<h1>
		<?php echo '<?php echo $model::modelTitle()?>'?>: <?php echo '<?php echo '?>$this->pageCaption;<?php echo '?>'?>
	</h1>
</div>

<?php echo "<?php \$form=\$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>\$this->targetModel.'-form',
	'enableAjaxValidation'=>true,
	'type'=>'horizontal',
	'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'validateOnType' => false,
    ),
    'focus'=>array(\$model,'title_ru'),
    'htmlOptions' => array(
        'class' => 'form form-horizontal',
        'enctype'=>'multipart/form-data'
    ),
)); ?>\n"; ?>

	<div class="form-actions">
		<button class="btn btn-success" type="submit">
			<?php echo  "<?php echo  \$model->isNewRecord ? 'Создать' : 'Сохранить'?>\n"?>
		</button>
		или
		<a href="<?php echo "<?php echo \$this->returnUrl?\$this->returnUrl:\$this->createUrl(\$this->id.'/list')?>"?>">
			назад
		</a>
		<?"<? \$this->widget('admin.widgets.WLang.WLang'); ?>"?>
	</div>

	<?php echo "<?//=\$form->errorSummary(\$model); ?>\n"; ?>

<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
?>
	<?php echo "<?php echo ".$this->generateActiveRow($this->modelClass,$column)."; ?>\n"; ?>

<?php
}
?>
	<div class="form-actions">
		<button class="btn btn-success" type="submit">
			<?php echo "<?php echo \$model->isNewRecord ? 'Создать' : 'Сохранить'?>\n"?>
		</button>
		или
		<a href="<?php echo "<?php echo \$this->returnUrl?\$this->returnUrl:\$this->createUrl(\$this->id.'/list')?>"?>">
			назад
		</a>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>