<?php
/**
 * inherit following variable from 'Controller':
 * @var User $model
 */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'user-form',
    'enableAjaxValidation'=>true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'validateOnType' => false,
    ),
    'focus'=>array($model,'login'),
)); ?>
 
<div class="onecolumn">

	<div class="content">

	    <div class="row">
	        <?php echo $form->labelEx($model,'login'); ?>
	        <?php echo $form->textField($model,'login',array('maxlength'=>60)); ?>
	        <?php echo $form->error($model,'login'); ?>
	    </div>
	    
	    <div class="row">
	        <?php echo $form->labelEx($model,'password'); ?>
	        <?php echo $form->passwordField($model,'password',array('maxlength'=>32)); ?>
	        <?php echo $form->error($model,'password'); ?>
	    </div>
	    
	    <div class="row">
	        <?php echo $form->labelEx($model,'password_repeat'); ?>
	        <?php echo $form->passwordField($model,'password_repeat',array('maxlength'=>32)); ?>
	        <?php echo $form->error($model,'password_repeat'); ?>
	    </div>
	    
	    <div class="row">
	        <?php echo $form->labelEx($model,'nicename'); ?>
	        <?php echo $form->textField($model,'nicename',array('maxlength'=>50)); ?>
	        <?php echo $form->error($model,'nicename'); ?>
	    </div>
	    
	    <div class="row">
	        <?php echo $form->labelEx($model,'email'); ?>
	        <?php echo $form->textField($model,'email',array('maxlength'=>50)); ?>
	        <?php echo $form->error($model,'email'); ?>
	    </div>

	    <div class="row">
	        <?php echo $form->labelEx($model,'role'); ?>
	        <?php echo $form->dropDownList($model,'role', User::model()->getRoles(), array('prompt'=> '---Select one---')); ?>
	        <?php echo $form->error($model,'role'); ?>
	    </div>

	</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->