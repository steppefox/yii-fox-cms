
<div id="main">
	
<div class="toolbar">
    <div class="left">
        <?php
        $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name' => 'btnBack',
                    'buttonType' => 'link',
                    'url' => $this->createUrl('index'),
                    'caption' => Yii::t('backend', 'Back'),
                    'options' => array('icons' => array('primary' => 'ui-icon-circle-triangle-w')),
                )
        );
        ?>
    </div>
    <div class="right">
        <?php echo XHtml::cloudButton(
                'btnSave', 
                Yii::t('backend', 'Save'), 
                'ui-icon-disk', null, 
                'js:function(){ $("form#review-form").submit(); return false; }', 
                'green'
        ); ?>
        <?php echo XHtml::cloudButton(
                'btnCancel', 
                Yii::t('backend', 'Cancel'), 
                'ui-icon-cancel',
                $this->createUrl('index'), 
                null, 
                'blue'
        ); ?>
        <?php if(!$model->isNewRecord)
            echo XHtml::cloudButton(
                'btnDelete', 
                Yii::t('zii', 'Delete'), 
                'ui-icon-trash', 
                $this->createUrl('delete', array('id'=>$model->primaryKey)), 
                "js:function(){ return confirm('".Yii::t('zii','Are you sure you want to delete this item?')."'); }", 
                'red'
        ); ?>
    </div>

</div>

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'review-form',
            'enableAjaxValidation' => false,
        ));
?>

<div id="content_form">
<div class="form">
 
<div class="onecolumn">

	<div class="content">
		
		<div class="section">
                <div class="section-header">Review bewerken</div>
                <div class="section-content">

	    <div class="row">
	        <?php echo $form->labelEx($model,'rate'); ?>
	        <?php echo $form->dropDownList($model,'rate', $model->rates); ?>
	        <?php echo $form->error($model,'rate'); ?>
	    </div>
		
			<div class="row">
	        <?php echo $form->labelEx($model,'approved'); ?>
	        <?php echo $form->checkBox($model,'approved'); ?>
	        <?php echo $form->error($model,'approved'); ?>
	    </div>
	
	    <div class="row">
	        <?php echo $form->labelEx($model,'description'); ?>
	        <?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	        <?php echo $form->error($model,'description'); ?>
	    </div>
	    
								</div>
		</div>

	</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->

</div>
	
</div>