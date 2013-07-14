<?php 
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
                'id'=>'folderDialog',
                'options'=>array(
                    'title'=>Yii::t('job','Create Folder'),
                    'autoOpen'=>false,
                    'modal'=>'true',
                    'width'=>'400px',
                    'height'=>'auto',
                ),
                )); ?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'folder-form',
    'enableAjaxValidation'=>true,
)); 
//I have enableAjaxValidation set to true so i can validate on the fly the
?>
    
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>
 
    <div class="row">
        <?php echo $form->labelEx($model,'path'); ?>
        <?php echo $form->dropDownList($model,'path',FileSystem::getWriteblePathDropdown(), array('prompt'=>'---select---')); ?>
        <?php echo $form->error($model,'path'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name',array('size'=>34,'maxlength'=>90)); ?>
        <?php echo $form->error($model,'name'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::ajaxSubmitButton(Yii::t('backend','Create Folder'),CHtml::normalizeUrl(array('/admin/file/createFolder')),array('success'=>'js: function(data) {
                        if(data)
                            $("#folderDialog").html(data);
                        else
                        {
                            $("#folderDialog").dialog("close");
                            location.reload();
                        }
                    }'),array('id'=>'submitFolderDialog')); ?>
    </div>
    
<?php $this->endWidget(); ?>
</div>

<?php $this->endWidget('zii.widgets.jui.CJuiDialog');?>