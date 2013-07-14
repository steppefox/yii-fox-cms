<div id="main">

<?php
$form = $this->beginWidget('CActiveForm', array(
            'id' => 'category-form',
            'enableAjaxValidation' => false,
        ));
?>
    
<?php Yii::app()->clientScript->registerScript('delete-prop', "$('.delProp').live('click', function() { $(this).parent().remove(); })"); ?>
    <?php Yii::app()->clientScript->registerScript('delete-propGroup', "$('.delPropGroup').live('click', function() { $(this).closest('.propertyGroupBox').remove(); })"); ?>

<!--  <div id="main">-->
<div class="toolbar">
    <div class="left">
        <?php
        $this->widget('zii.widgets.jui.CJuiButton',
                array(
                    'name' => 'btnBack',
                    'buttonType' => 'link',
                    'url' => $this->createUrl('product/index'),
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
                'js:function(){ $("form#category-form").submit(); return false; }', 
                'green'
        ); ?>
        <?php echo XHtml::cloudButton(
                'btnCancel', 
                Yii::t('backend', 'Cancel'), 
                'ui-icon-cancel',
                $this->createUrl('product/index'), 
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

<div id="content_form">
    <div class="form">

        <div class="one_half">
            
            
            
        <div class="section">
            <div class="section-header">Eigenschappen</div>
            <div class="section-content">

                <div class="row">
                    <?php echo $form->labelEx($model,'name'); ?>
                    <?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
                    <?php echo $form->error($model,'name'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model,'position'); ?>
                    <?php echo $form->textField($model,'position'); ?>
                    <?php echo $form->error($model,'position'); ?>
                </div>

                <div class="row">
                    <?php echo $form->labelEx($model,'parent_id'); ?>
                    <?php echo $form->dropDownList($model,'parent_id', $model->dropDownTree, array('prompt'=> '---Root---')); ?>
                    <?php echo $form->error($model,'parent_id'); ?>
                </div>

							<div class="row">
                    <?php echo $form->labelEx($model,'active'); ?>
                    <?php echo $form->checkBox($model,'active'); ?>
                    <?php echo $form->error($model,'active'); ?>
                </div>
            </div>
        </div>
            
        </div>
        <div class="one_half">
            <div class="section">
                <div class="section-header">Afbeelding</div>
                <div class="section-content">
                    <div class="row">
                        
                        <?php $this->widget('application.modules.admin.widgets.imageSelector.ImageSelector',
                            array(
                                //'name' => 'media_id',
                                'model'=>$model,
                                'attribute'=>'media_id'
                            )
                        ); ?>
                        
										</div>
								</div>
						</div>
				</div>
        
        <div class="one_column">
            <div class="section">
                <div class="section-header">Filters</div>
                <div class="section-content">
                    <div class="row buttons">

                        <?php
                        
                        $this->widget('zii.widgets.jui.CJuiButton',
                                array(
                                    'name' => 'btnAddPropertyGroup',
                                    'buttonType' => 'link',
                                    'url' => $this->createUrl('addPropertyGroup'),
                                    'caption' => Yii::t('backend', 'Add Filter'),
                                    'options' => array('icons' => array('primary' => 'ui-icon-plus')),
                                    'onclick' => "js:function(){ $.ajax({
                                        url: $(this).attr('href'),
                                        success: function(data) { $('#categoryFilters').prepend(data); } 
                                        });
                                        return false; }",
                                                )
                        ); 
                        ?>
                      
                        
                        
                    </div>
                    <div class="row">
                        <?php echo CHtml::errorSummary($model->propertyGroups); ?>
                        <div id="categoryFilters">
                        <?php foreach($model->propertyGroups as $propertyGroup): ?>
                        
                            <?php $this->renderPartial('propertyGroup', array('model'=>$propertyGroup, 'form'=>$form)); ?>
                        
                        <?php endforeach; ?>
                        
                        </div>
                        <div style="clear:both;"></div>
                    </div>

                </div>
            </div>
        </div>


                            </div>
                        </div>
<?php $this->endWidget(); ?>

</div>
