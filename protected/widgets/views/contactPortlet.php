<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'contact-form',
    'enableAjaxValidation'=>true,
	'clientOptions'=>array(
        'validateonsubmit'=>true,
        'validateonchange'=>true,
        'validateOnType'=>false,
    ),
)); ?>

	<?php //echo $form->errorSummary($model); ?>

	<div style="float:left; width: 40%;">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
            
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div style="float:left; width: 45%;">
		<?php echo $form->label($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>4, 'cols'=>24)); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>

		<?php echo CHtml::submitButton('Verzenden »'); ?>


<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>