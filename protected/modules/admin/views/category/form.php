<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'content-category-form',
    'enableAjaxValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'validateOnType' => false,
    ),
    'focus'=>array($model,'title'),
)); ?>
 
<div class="onecolumn">

	<div class="content">

	    <div class="row">
	        <?php echo $form->labelEx($model,'title'); ?>
	        <?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>100)); ?>
	        <?php echo $form->error($model,'title'); ?>
	    </div>
	
	    <div class="row">
	        <?php echo $form->labelEx($model,'alias'); ?>
	        <?php echo $form->textField($model,'alias',array('size'=>60,'maxlength'=>100)); ?>
	        <?php echo $form->error($model,'alias'); ?>
	    </div>
	
	    <div class="row">
	        <?php echo $form->labelEx($model,'description'); ?>
	        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	        <?php echo $form->error($model,'description'); ?>
	    </div>

	    <div class="row">
	        <?php echo $form->labelEx($model,'parent_id'); ?>
	        <?php echo $form->dropDownList($model,'parent_id', CHtml::listData($model->validParents, 'id', 'title'), array('prompt'=> '---Root---')); ?>
	        <?php echo $form->error($model,'parent_id'); ?>
	    </div>
	    
	    <div class="row">
	        <?php echo $form->labelEx($model,'active'); ?>
	        <?php echo $form->checkBox($model,'active'); ?>
	        <?php echo $form->error($model,'active'); ?>
	    </div>

	</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->