<div style="float: left; width: 350px;" class="ui-widget ui-widget-content ui-corner-all ui-resizable propertyGroupBox">
    <div class="ui-dialog-titlebar ui-widget-header ui-helper-clearfix" style="padding: 10px;">
        <span class="ui-dialog-title">Filter</span>
        <div style="float:right;">
        <?php if($model->isNewRecord): ?>
            <?php echo CHtml::link(Yii::t('zii', 'Delete'), '#', array('class'=>'delPropGroup')); ?>
        <?php else: ?>
            Verwijderen:
            <?php echo $form->checkBox($model,"[$model->id]markedDeleted"); ?>
        <?php endif; ?>
        </div>
    </div>
    
    <div class="ui-dialog-content ui-widget-content" style="padding: 10px;">


        <div class="row">
            <?php echo $form->labelEx($model,'[]name'); ?>
            <?php echo $form->textField($model,"[$model->id]name",array('size'=>28,'maxlength'=>100)); ?>
            <?php echo $form->error($model,"[$model->id]name"); ?>
        </div>
        
        <div class="row">
            <?php echo $form->labelEx($model,'[]description'); ?>
            <?php echo $form->textArea($model, "[$model->id]description", array('cols'=>28, 'rows'=>4)); ?>
            <?php echo $form->error($model,"[$model->id]description"); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'[]position'); ?>
            <?php echo $form->textField($model, "[$model->id]position"); ?>
            <?php echo $form->error($model,"[$model->id]position"); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model,'[]type'); ?>
            <?php echo $form->dropDownList($model,"[$model->id]type", $model->typeOptions); ?>
            <?php echo $form->error($model,"[$model->id]type"); ?>
        </div>
        
        <div class="row">
            <?php  
                echo CHtml::link('Eigenschap toevoegen', $this->createUrl('addProperty', array('group_id'=>$model->id)), array(
                    'onclick'=>"$.ajax({url: $(this).attr('href'),success: function(data) { $('#filterProperties_".$model->id."').prepend(data); }}); return false;",
                    'class'=>'button',
                )); ?><Br /><br>
            <?php 
            /*
            $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                        'name' => 'btnAddProperty'.$model->id,
                        'buttonType' => 'link',
                        'url' => $this->createUrl('index'),
                        'caption' => 'Eigenschap toevoegen',
                        'options' => array('icons' => array('primary' => 'ui-icon-plus')),
                    )
            ); */
            ?>
            <?php echo CHtml::errorSummary($model->properties); ?>
            <div style="width: 100%;" class="scrollbox">

               <ul style="height: 200px;" id="filterProperties_<?php echo $model->id; ?>">

            
            <?php foreach($model->properties as $property): ?>
            
                <?php $this->renderPartial('property', array('property'=>$property)); ?>
            
            <?php endforeach; ?>
                
                </ul>
            </div>
        </div>

    </div>

</div>