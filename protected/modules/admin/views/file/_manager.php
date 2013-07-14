<?php $class = $model->metaData->relations['mediaLinks']->className; ?>

<?php Yii::app()->controller->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => 'req_res',
            // additional javascript options for the dialog plugin
            'options' => array(
                'width' => '80%',
                'height' => '600',
                'resizable' => true,
                'modal' => true,
                'title' => Yii::t('backend', 'Add Files'),
                'autoOpen' => false,
                'buttons' => array(
                    Yii::t('backend', 'Insert') => "js:function() {
                        $.post('".$this->createUrl('/admin/file/addFile', array('class'=>$class))."', $('#form-selected').serialize(),
                            function(data) {
                                $('#media-table tbody').append(data);
                                
                            });
                        $(this).dialog('close'); 
                    }",
                    Yii::t('backend', 'Cancel') => 'js:function() { $(this).dialog("close"); }',
                ),
            ),
        ));

?>

<?php

    $filesystem = new FileSystem('files');

    $models = Media::model()->findByAttributes(array('path'=>'files'));

    $this->renderPartial('application.modules.admin.views.file.gallery', array('model'=>$model, 'filesystem'=>$filesystem));

?>

<?php
        //$this->renderPartial('application.modules.admin.views.file.fileBrowser');

   Yii::app()->controller->endWidget();
        
        Yii::app()->clientScript->registerScript('delete-file', "$('.delete-file').live('click', function() { $(this).closest('tr').remove(); })");
        
        ?>

<div class="one_column">
    <div class="section">
        <div class="section-header"><?php echo Yii::t('backend', 'Images and Downloads'); ?></div>
        <div class="section-content">

            <div class="row buttons">
                
                <?php 
                
                $this->widget('zii.widgets.jui.CJuiButton',
                    array(
                            'name'=>'button',
                            'buttonType' => 'button',
                            'caption'=>Yii::t('backend', 'Add Files'),
                            'options' => array('icons' => array('primary' => 'ui-icon-arrowthickstop-1-n')),
                            'onclick'=>"js:function(){ $('#req_res').dialog('open'); return false;}",
                            )
                    );

                ?>
                
            </div>
            <div class="row">
    <?php echo CHtml::errorSummary($model->mediaLinks); ?>
            <table id="media-table" class="items">
                <thead>
                <tr>
                    <th>Preview</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Remove</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($model->mediaLinks as $productMedia): ?>
                    
                    <tr>
                        <td><?php echo ($productMedia->media != null) ? CHtml::image($productMedia->media->getImageUrl('thumb')) : "Error"; ?>
                            <?php echo CHtml::activeHiddenField($productMedia, "[$productMedia->id]media_id"); ?>
                        </td>
                        <td><?php echo CHtml::activeTextField($productMedia, "[$productMedia->id]name") . CHtml::error($productMedia,"name"); ?></td>
                        <td><?php echo CHtml::activeTextArea($productMedia, "[$productMedia->id]description", array("cols"=>"40")); ?></td>
                        <td><?php echo CHtml::activeDropDownList($productMedia, "[$productMedia->id]type", call_user_func(array($class, 'getMediaTypes'))); ?></td>
                        <td><?php echo CHtml::activeCheckbox($productMedia, "[$productMedia->id]markedDeleted"); ?></td>
                    </tr>
                
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>