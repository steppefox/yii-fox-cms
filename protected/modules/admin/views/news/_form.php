<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>$this->targetModel.'-form',
	'enableAjaxValidation'=>true,
	'type'=>'horizontal',
)); ?>

	<p>Поля помеченные <span class="required">*</span> обязательны к заполнению.</p>

	<?php echo $form->errorSummary($model); ?>

	<?=$form->textFieldRow($model,'parent_NewsCategory_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?=$form->textFieldRow($model,'title_ru',array('class'=>'span5','maxlength'=>255)); ?>

	<?=$form->checkBoxRow($model,'is_visible'); ?>

	<div class="form-actions">
		<button class="btn btn-success" type="submit">
			<?php echo $model->isNewRecord ? 'Создать' : 'Сохранить'?>
		</button>
	</div>

<?php $this->endWidget(); ?>
