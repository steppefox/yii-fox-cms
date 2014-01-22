<div>
	<h1>
		<?=$this->pageCaption;?>	</h1>
</div>

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>$this->targetModel.'-form',
	'enableAjaxValidation'=>true,
	'type'=>'horizontal',
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<?=$form->textFieldRow($model,'parent_NewsCategory_id',array('class'=>'span5','maxlength'=>10)); ?>

	<?=$form->textFieldRow($model,'title_ru',array('class'=>'span5','maxlength'=>255)); ?>
	<?=$form->textFieldRow($model,'title_kz',array('class'=>'span5','maxlength'=>255)); ?>
	<?=$form->textFieldRow($model,'title_en',array('class'=>'span5','maxlength'=>255)); ?>
	<?=$form->textFieldRow($model,'title_ko',array('class'=>'span5','maxlength'=>255)); ?>

	<?=$form->textFieldRow($model,'description_ru',array('class'=>'span5','maxlength'=>255)); ?>
	<?=$form->textFieldRow($model,'description_kz',array('class'=>'span5','maxlength'=>255)); ?>
	<?=$form->textFieldRow($model,'description_en',array('class'=>'span5','maxlength'=>255)); ?>
	<?=$form->textFieldRow($model,'description_ko',array('class'=>'span5','maxlength'=>255)); ?>

	<?=$form->checkBoxRow($model,'is_visible'); ?>
	<?=$form->datepickerRow($model,'created_at',array('class'=>'span5','maxlength'=>10)); ?>
	<?=$form->datepickerRow($model,'updated_at',array('class'=>'span5','maxlength'=>10)); ?>

	<div class="form-actions">
		<button class="btn btn-success" type="submit">
			<?php echo $model->isNewRecord ? 'Создать' : 'Сохранить'?>
		</button>
	</div>

<?php $this->endWidget(); ?>
