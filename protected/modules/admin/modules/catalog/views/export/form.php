
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
                <div class="section-header">Export opties</div>
                <div class="section-content">

	    <div class="row">
	        <?php echo $form->labelEx($model,'product_id'); ?>
				<?php if($model->isNewRecord): ?>
				<?php echo CHtml::dropDownList('category_id','', ProductCategory::model()->getDropdownTree(),
						array(
							'prompt'=>'',
							'ajax' => array(
								'type'=>'POST', //request type
								'url'=>$this->createUrl('productDropDown'),
								'update'=>'#ProductExport_product_id',
							)
						)); ?>
					<?php //echo $form->dropDownList($model,'product_id', ProductCategory::model()->getDropdownTree()); ?>
					<?php echo $form->dropDownList($model,'product_id', array(), array('prompt'=>'--selecteer categorie--')); ?>
				<?php else: ?>
					<?php echo $model->product->name; ?>
				<?php endif; ?>
	        <?php echo $form->error($model,'product_id'); ?>
	    </div>
		
			<div class="row">
	        <?php echo $form->labelEx($model,'beslist'); ?>
	        <?php echo $form->checkBox($model,'beslist'); ?>
	        <?php echo $form->error($model,'beslist'); ?>
	    </div>
									
			<div class="row">
	        <?php echo $form->labelEx($model,'kieskeurig'); ?>
	        <?php echo $form->checkBox($model,'kieskeurig'); ?>
	        <?php echo $form->error($model,'kieskeurig'); ?>
	    </div>
									
			<div class="row">
	        <?php echo $form->labelEx($model,'vergelijk'); ?>
	        <?php echo $form->checkBox($model,'vergelijk'); ?>
	        <?php echo $form->error($model,'vergelijk'); ?>
	    </div>

								</div>
		</div>

	</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->

</div>
	
</div>